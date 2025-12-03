<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;
use App\Models\Employee;
use App\Models\Division;
use App\Models\SubDivision;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class DivisionReportController extends Controller
{
    /**
     * Display division report page for logged-in user's division
     */
    public function index()
    {
        // Get logged-in user's employee data
        $user = Auth::user();
        $employee = Employee::where('email', $user->email)->first();
        
        if (!$employee) {
            abort(403, 'Employee data not found. Please contact administrator.');
        }
        
        if (!$employee->division_id) {
            abort(403, 'You are not assigned to any division. Please contact administrator.');
        }
        
        // Get user's division
        $division = Division::findOrFail($employee->division_id);
        
        // Get subdivisions for this division
        $subdivisions = SubDivision::where('division_id', $division->id)
            ->orderBy('title')
            ->get();

        return view('reports.division', compact('division', 'subdivisions'));
    }

    /**
     * Get data for division report (filtered by user's division)
     */
    public function getData(Request $request)
    {
        $date = $request->get('date');
        
        if (!$date) {
            return response()->json([]);
        }

        // Get logged-in user's division
        $user = Auth::user();
        $employee = Employee::where('email', $user->email)->first();
        
        if (!$employee || !$employee->division_id) {
            return response()->json([]);
        }

        $divisionId = $employee->division_id;
        $subdivisionId = $request->get('subdivision_id'); // Optional filter

        // Build query for employees in same division
        $employeesQuery = Employee::with(['user', 'division', 'subDivision', 'role', 'position'])
            ->where('division_id', $divisionId)
            ->whereHas('logs', function($query) use ($date) {
                $query->where('date', $date);
            });

        // Filter by subdivision if provided
        if ($subdivisionId) {
            $employeesQuery->where('subdivision_id', $subdivisionId);
        }

        $employees = $employeesQuery->get();

        $result = [];

        foreach ($employees as $emp) {
            $logs = Log::with(['category', 'task', 'builder', 'dweling', 'status'])
                ->where('employee_id', $emp->id)
                ->where('date', $date)
                ->get();

            if ($logs->count() > 0) {
                $leaveRecords = []; // Can be extended with leave logic

                $result[] = [
                    'employee_id' => $emp->id,
                    'title' => $emp->user->name,
                    'division' => $emp->division->title ?? 'N/A',
                    'subdivision' => $emp->subDivision->title ?? 'N/A',
                    'role' => $emp->role->title ?? 'N/A',
                    'position' => $emp->position->title ?? 'N/A',
                    'division_id' => $emp->division_id,
                    'subdivision_id' => $emp->subdivision_id,
                    'archive' => $emp->archive,
                    'log' => $logs->map(function($log) {
                        return [
                            'id' => $log->id,
                            'title' => $log->subject,
                            'description' => $log->description,
                            'qty' => $log->qty ?? '',
                            'category' => $log->category->title ?? 'N/A',
                            'task' => $log->task->title ?? 'N/A',
                            'builder' => $log->builder->title ?? 'N/A',
                            'dweling' => $log->dweling->title ?? 'N/A',
                            'status' => $log->status->title ?? 'N/A',
                            'duration' => $log->duration,
                            'note' => $log->note ?? '',
                            'wtime' => 'Regular' // Default work status
                        ];
                    })->toArray(),
                    'leave' => $leaveRecords
                ];
            }
        }

        return response()->json($result);
    }

    /**
     * Check if date is a holiday (placeholder)
     */
    public function getHoliday(Request $request)
    {
        $date = $request->get('date');
        
        // TODO: Implement actual holiday checking logic
        // For now, return false (no holiday)
        return response()->json(false);
    }

    /**
     * Print/Export division report to PDF
     */
    public function print(Request $request)
    {
        $date = $request->get('date');
        $holiday = $request->get('holiday', 0);
        
        if (!$date) {
            return response()->json(['error' => 'Date is required'], 400);
        }

        // Get logged-in user's division
        $user = Auth::user();
        $employee = Employee::where('email', $user->email)->first();
        
        if (!$employee || !$employee->division_id) {
            return response()->json(['error' => 'Employee data not found'], 403);
        }

        $division = Division::findOrFail($employee->division_id);
        $subdivisionId = $request->get('subdivision_id');
        $subdivision = $subdivisionId ? SubDivision::find($subdivisionId) : null;

        // Get report data for user's division only
        $reportData = $this->getReportDataForDate($date, $employee->division_id, $subdivisionId);
        $leaveList = $this->getLeaveListForDate($date, $employee->division_id, $subdivisionId);
        
        // Generate PDF
        $pdf = Pdf::loadView('reports.division-pdf', [
            'date' => $date,
            'holiday' => $holiday,
            'division' => $division,
            'subdivision' => $subdivision,
            'reportData' => $reportData,
            'leaveList' => $leaveList
        ]);
        
        // Set PDF options
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'Arial'
        ]);
        
        // Generate filename
        $filename = $division->title . '_Report_' . Carbon::parse($date)->format('Y-m-d');
        if ($subdivision) {
            $filename .= '_' . $subdivision->title;
        }
        $filename .= '.pdf';
        
        // Save PDF to storage
        $pdfPath = storage_path('app/public/reports/' . $filename);
        
        // Create directory if it doesn't exist
        if (!file_exists(dirname($pdfPath))) {
            mkdir(dirname($pdfPath), 0755, true);
        }
        
        // Save PDF
        $pdf->save($pdfPath);
        
        // Return URL to access the PDF
        $pdfUrl = url('storage/reports/' . $filename);
        
        return response()->json($pdfUrl);
    }

    /**
     * Get report data for specific date and division
     */
    private function getReportDataForDate($date, $divisionId, $subdivisionId = null)
    {
        $employeesQuery = Employee::with(['user', 'division', 'subDivision', 'role', 'position'])
            ->where('division_id', $divisionId)
            ->whereHas('logs', function($query) use ($date) {
                $query->where('date', $date);
            });

        if ($subdivisionId) {
            $employeesQuery->where('subdivision_id', $subdivisionId);
        }

        $employees = $employeesQuery->get();

        $result = [];

        foreach ($employees as $emp) {
            $logs = Log::with(['category', 'task', 'builder', 'dweling', 'status'])
                ->where('employee_id', $emp->id)
                ->where('date', $date)
                ->get();

            $totalMinutes = $logs->sum('duration');
            $totalHours = $totalMinutes / 60;

            $result[] = [
                'name' => $emp->user->name,
                'division' => $emp->division->title ?? 'N/A',
                'sub_division' => $emp->subDivision->title ?? 'N/A',
                'role' => $emp->role->title ?? 'N/A',
                'position' => $emp->position->title ?? 'N/A',
                'logs' => $logs->map(function($log) {
                    return [
                        'subject' => $log->subject,
                        'description' => $log->description,
                        'qty' => $log->qty,
                        'category' => $log->category->title ?? 'N/A',
                        'task' => $log->task->title ?? 'N/A',
                        'builder' => $log->builder->title ?? 'N/A',
                        'dweling' => $log->dweling->title ?? 'N/A',
                        'status' => $log->status->title ?? 'N/A',
                        'duration' => $log->duration,
                        'note' => $log->note,
                        'work_status' => 'Regular'
                    ];
                })->toArray(),
                'total_hours' => round($totalHours, 2)
            ];
        }

        return $result;
    }

    /**
     * Get leave list for specific date and division
     */
    private function getLeaveListForDate($date, $divisionId, $subdivisionId = null)
    {
        $employeesQuery = Employee::with(['user', 'division', 'subDivision', 'role', 'position'])
            ->where('division_id', $divisionId)
            ->where('archive', null)
            ->whereDoesntHave('logs', function($query) use ($date) {
                $query->where('date', $date);
            });

        if ($subdivisionId) {
            $employeesQuery->where('subdivision_id', $subdivisionId);
        }

        $employeesOnLeave = $employeesQuery->get();

        $leaveList = [];

        foreach ($employeesOnLeave as $emp) {
            $leaveList[] = [
                'name' => $emp->user->name,
                'division' => $emp->division->title ?? 'N/A',
                'sub_division' => $emp->subDivision->title ?? 'N/A',
                'role' => $emp->role->title ?? 'N/A',
                'level' => $emp->position->title ?? 'N/A',
                'reason' => 'No Assignments'
            ];
        }

        return $leaveList;
    }
}
