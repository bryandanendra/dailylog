<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    public function getHoliday(Request $request)
    {
        $date = $request->query('date');
        $isHoliday = Holiday::where('date', $date)->exists();
        return response()->json($isHoliday);
    }

    public function getleave()
    {
        // return master leave reasons (id,title)
        $reasons = [
            ['id' => 1, 'title' => 'Annual Leave'],
            ['id' => 2, 'title' => 'Half-Day Off'],
            ['id' => 3, 'title' => 'Joint Leave'],
            ['id' => 4, 'title' => 'AWOL'],
            ['id' => 5, 'title' => 'Public Holiday Replacement'],
            ['id' => 6, 'title' => 'Sick Leave'],
            ['id' => 7, 'title' => 'Special Leave'],
            ['id' => 8, 'title' => 'Unpaid Leave'],
            ['id' => 9, 'title' => 'Weekend'],
            ['id' => 10, 'title' => 'Public Holiday'],
            ['id' => 11, 'title' => 'SPDR Commercial Assignment'],
            ['id' => 12, 'title' => 'No Assignments'],
            ['id' => 13, 'title' => 'Half-Day'],
            ['id' => 14, 'title' => 'Inactive'],
            ['id' => 15, 'title' => 'Maternity Leave'],
        ];
        return response()->json($reasons);
    }

    public function getData(Request $request)
    {
        $date = $request->query('date');
        $employeeId = $request->query('id'); // optional supervisor filter
        $currentUser = Auth::user();

        // Get employees that this user can approve (superior hierarchy)
        $employees = Employee::with(['user','division','subDivision','role','position','superior'])
            ->where('division_id', $currentUser->division_id) // Use current user's division
            ->get()
            ->filter(function($employee) use ($currentUser) {
                // Check if current user can approve this employee's logs
                $levels = config('approval.position_levels');
                $approverLevel = $levels[$currentUser->position->title ?? ''] ?? 0;
                $employeeLevel = $levels[$employee->position->title ?? ''] ?? 0;
                
                $isSuperior = $employee->superior_id && $employee->superior?->user_id === $currentUser->id;
                $higherPos = $approverLevel > $employeeLevel;
                $sameDiv = $currentUser->division_id && $employee->division_id && $currentUser->division_id === $employee->division_id;
                
                return ($isSuperior || $higherPos) && $sameDiv && (bool) $currentUser->can_approve;
            });

        $result = [];
        foreach ($employees as $employee) {
            if ($employeeId && (int)$employeeId !== (int)$employee->id) {
                // when supervisor clicked, show only that employee
                continue;
            }
            $logs = Log::with(['category','task','builder','dweling','status','employee.superior'])
                ->where('employee_id', $employee->id)
                ->where('date', $date)
                ->get();

            $result[] = [
                'id' => $employee->id,
                'title' => $employee->user->name,
                'division' => $employee->division->title ?? 'N/A',
                'subdivision' => $employee->subDivision->title ?? 'N/A',
                'role' => $employee->role->title ?? 'N/A',
                'position' => $employee->position->title ?? 'N/A',
                'position_id' => $employee->position_id,
                'archive' => $employee->archive ? 1 : 0,
                'supervisor' => $employee->superior?->user->name ?? 'N/A',
                'log' => $logs->map(function($log){
                    return [
                        'id' => $log->id,
                        'title' => $log->subject,
                        'description' => $log->description,
                        'qty' => $log->qty,
                        'category' => $log->category->title ?? '',
                        'task' => $log->task->title ?? '',
                        'builder' => $log->builder->title ?? '',
                        'dweling' => $log->dweling->title ?? '',
                        'status' => $log->status->title ?? '',
                        'duration' => (int) $log->duration,
                        'note' => $log->note ?? '',
                        'wtime' => $log->work_time ? 'Overtime' : 'Regular',
                        'approved' => $log->approved ? 1 : 0,
                        'approved_note' => $log->approved_note,
                        'approved_emoji' => $log->approved_emoji,
                    ];
                })->toArray(),
                'leave' => [],
            ];
        }

        return response()->json($result);
    }

    public function check(Request $request)
    {
        $id = $request->query('id');
        $log = Log::findOrFail($id);
        return response()->json([[
            'title' => $log->subject,
            'description' => $log->description,
            'qty' => $log->qty,
            'category' => optional($log->category)->title,
            'task' => optional($log->task)->title,
            'builder' => optional($log->builder)->title,
            'dweling' => optional($log->dweling)->title,
            'status' => optional($log->status)->title,
            'duration' => (int) $log->duration,
            'note' => $log->note,
            'wtime' => $log->work_time ? 'Overtime' : 'Regular',
            'approved' => $log->approved ? 1 : 0,
            'approved_note' => $log->approved_note,
            'approved_emoji' => $log->approved_emoji,
        ]]);
    }

    public function update(Request $request)
    {
        $data = json_decode($request->query('str', '{}'), true);
        $log = Log::findOrFail($data['id']);

        $key = $data['key'];
        $val = $data['val'];

        // Map UI keys
        $map = [
            'category_id' => 'category_id',
            'task_id' => 'task_id',
            'builder_id' => 'builder_id',
            'dweling_id' => 'dweling_id',
            'status_id' => 'status_id',
            'title' => 'subject',
            'description' => 'description',
            'qty' => 'qty',
            'duration' => 'duration',
            'note' => 'note',
            'wtime' => 'work_time',
        ];

        $column = $map[$key] ?? $key;
        $log->{$column} = $val;
        $log->save();

        return response()->json(true);
    }

    public function submit(Request $request)
    {
        $date1 = $request->query('date1');
        $date2 = $request->query('date2');
        $rows = json_decode($request->query('data','[]'), true);

        $approvedLogs = []; // Track approved logs for notification

        foreach ($rows as $row) {
            $log = Log::find($row['id']);
            if (!$log) continue;

            // authorize
            if (!Auth::user()->can('approve-log', $log)) continue;

            $wasApproved = $log->approved;
            $log->approved = $row['approved'] ? true : false;
            $log->approved_date = $row['approved_date'] ?? $date2;
            $log->approved_note = $row['approved_note'] ?? null;
            $log->approved_emoji = $row['approved_emoji'] ?? null;
            $log->approved_by = Auth::id();
            $log->save();

            // Track newly approved logs for notification
            if (!$wasApproved && $log->approved) {
                if (!isset($approvedLogs[$log->employee_id])) {
                    $approvedLogs[$log->employee_id] = [
                        'employee' => $log->employee,
                        'count' => 0,
                        'date' => $log->date
                    ];
                }
                $approvedLogs[$log->employee_id]['count']++;
            }
        }

        // Create notifications for approved logs
        foreach ($approvedLogs as $employeeId => $data) {
            $approverName = Auth::user()->name;
            $taskCount = $data['count'];
            $taskWord = $taskCount === 1 ? 'task' : 'tasks';
            $date = \Carbon\Carbon::parse($data['date'])->format('d/m/Y');
            
            Notification::create([
                'employee_id' => $employeeId,
                'title' => 'Daily Log Approved',
                'message' => "{$approverName} has approved {$taskCount} {$taskWord} from your daily log on {$date}.",
                'date' => now(),
                'read_status' => false
            ]);
        }

        return response()->json(true);
    }

    public function getUnapprovedLogs()
    {
        $user = Auth::user();
        
        // Get employees that this user can approve
        $employees = Employee::with(['user','division','subDivision','role','position','superior'])
            ->where('division_id', $user->division_id) // Use current user's division
            ->get()
            ->filter(function($employee) use ($user) {
                $levels = config('approval.position_levels');
                $approverLevel = $levels[$user->position->title ?? ''] ?? 0;
                $employeeLevel = $levels[$employee->position->title ?? ''] ?? 0;
                
                $isSuperior = $employee->superior_id && $employee->superior?->user_id === $user->id;
                $higherPos = $approverLevel > $employeeLevel;
                $sameDiv = $user->division_id && $employee->division_id && $user->division_id === $employee->division_id;
                
                return ($isSuperior || $higherPos) && $sameDiv && (bool) $user->can_approve;
            });

        $employeeIds = $employees->pluck('id')->toArray();
        
        $logs = Log::with('employee')
            ->where('approved', false)
            ->whereIn('employee_id', $employeeIds)
            ->get()
            ->map(function($l){
                return [
                    'manager_id' => optional($l->employee->superior)->user_id,
                    'log_date' => $l->date,
                ];
            });

        return response()->json([
            'userid' => $user?->id,
            'result' => $logs,
        ]);
    }

    public function leavesave(Request $request)
    {
        // Stub for UI compatibility
        return response()->json($this->getleave()->getData());
    }

    public function select(Request $request)
    {
        // Stub minimal autocomplete source (no-op backend; UI already populated via existing tables in app)
        $tbl = $request->query('tbl');
        return response()->json([]);
    }
}


