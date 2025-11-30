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

    /**
     * Check if current user is in the superior hierarchy of an employee
     * This traverses the superior chain to find if current user is a supervisor
     */
    private function isInSuperiorHierarchy(Employee $employee, $currentUserId, $maxDepth = 10)
    {
        if (!$employee->superior_id) {
            return false;
        }
        
        $current = $employee;
        $depth = 0;
        
        while ($current && $current->superior_id && $depth < $maxDepth) {
            // Load superior relationship with user if not already loaded
            if (!$current->relationLoaded('superior')) {
                $current->load('superior.user');
            } elseif ($current->superior && !$current->superior->relationLoaded('user')) {
                $current->superior->load('user');
            }
            
            // If superior doesn't exist, stop
            if (!$current->superior) {
                break;
            }
            
            // Check if current user is the superior (compare user_id)
            if ($current->superior->user_id && $current->superior->user_id == $currentUserId) {
                return true;
            }
            
            // Move up the hierarchy
            $current = $current->superior;
            $depth++;
        }
        
        return false;
    }

    public function getData(Request $request)
    {
        $date = $request->query('date');
        $employeeId = $request->query('id'); // optional supervisor filter
        $currentUser = Auth::user();
        
        // Get can_approve from Employee if exists, otherwise from User
        $currentEmployee = Employee::where('email', $currentUser->email)->first();
        $canApprove = $currentEmployee ? $currentEmployee->can_approve : $currentUser->can_approve;
        $isAdmin = $currentEmployee ? $currentEmployee->is_admin : $currentUser->is_admin;

        if (!$canApprove && !$isAdmin) {
            return response()->json([]);
        }

        // Get employees that this user can approve (superior hierarchy)
        $employees = Employee::with(['user','division','subDivision','role','position','superior.user'])
            ->where('division_id', $currentUser->division_id) // Use current user's division
            ->get()
            ->filter(function($employee) use ($currentUser) {
                // Only approve employees where current user is in the superior hierarchy
                // Even admin must follow the hierarchy structure
                if (!$employee->superior_id) {
                    return false; // No supervisor means no one can approve
                }
                
                // Check if current user is in the superior chain
                return $this->isInSuperiorHierarchy($employee, $currentUser->id);
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
        
        // Get can_approve from Employee if exists, otherwise from User
        $currentEmployee = Employee::where('email', $user->email)->first();
        $canApprove = $currentEmployee ? $currentEmployee->can_approve : $user->can_approve;
        $isAdmin = $currentEmployee ? $currentEmployee->is_admin : $user->is_admin;
        
        if (!$canApprove && !$isAdmin) {
            return response()->json([
                'userid' => $user?->id,
                'result' => [],
            ]);
        }
        
        // Get employees that this user can approve
        $employees = Employee::with(['user','division','subDivision','role','position','superior.user'])
            ->where('division_id', $user->division_id) // Use current user's division
            ->get()
            ->filter(function($employee) use ($user) {
                // Only approve employees where current user is in the superior hierarchy
                // Even admin must follow the hierarchy structure
                if (!$employee->superior_id) {
                    return false; // No supervisor means no one can approve
                }
                
                // Check if current user is in the superior chain
                return $this->isInSuperiorHierarchy($employee, $user->id);
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

    public function debug(Request $request)
    {
        $currentUser = Auth::user();
        $currentEmployee = Employee::where('email', $currentUser->email)->first();
        
        $employees = Employee::with(['user','superior.user'])
            ->where('division_id', $currentUser->division_id)
            ->get();
        
        $result = [];
        foreach ($employees as $employee) {
            $canApprove = $this->isInSuperiorHierarchy($employee, $currentUser->id);
            $result[] = [
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'superior_id' => $employee->superior_id,
                'superior_name' => $employee->superior ? $employee->superior->name : 'NULL',
                'superior_user_id' => $employee->superior ? $employee->superior->user_id : 'NULL',
                'current_user_id' => $currentUser->id,
                'can_approve' => $canApprove,
            ];
        }
        
        return response()->json([
            'current_user' => [
                'id' => $currentUser->id,
                'name' => $currentUser->name,
                'email' => $currentUser->email,
            ],
            'employees' => $result,
        ]);
    }
}


