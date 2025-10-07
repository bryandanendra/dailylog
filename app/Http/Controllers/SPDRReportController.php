<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\Employee;
use App\Models\Category;
use App\Models\Task;
use App\Models\Dweling;
use App\Models\Builder;
use App\Models\Status;
use App\Models\WorkStatus;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class SPDRReportController extends Controller
{
    public function index()
    {
        return view('reports.spdr');
    }

    public function getData(Request $request)
    {
        $date = $request->get('date');
        
        if (!$date) {
            return response()->json([]);
        }

        // Get SPDR role ID
        $spdrRole = \App\Models\Role::where('title', 'SPDR Estimating')->first();
        if (!$spdrRole) {
            return response()->json([]);
        }

        // Get only SPDR role employees with their logs for the specific date
        $employees = Employee::with(['user', 'division', 'subDivision', 'role', 'position'])
            ->where('role_id', $spdrRole->id) // Only SPDR role
            ->whereHas('logs', function($query) use ($date) {
                $query->where('date', $date);
            })
            ->get();

        $result = [];

        // Get SPDR category ID
        $spdrCategory = Category::where('title', 'SPDR')->first();
        if (!$spdrCategory) {
            return response()->json([]);
        }

        foreach ($employees as $employee) {
            $logs = Log::with(['category', 'task', 'builder', 'dweling', 'status'])
                ->where('employee_id', $employee->id)
                ->where('date', $date)
                ->where('category_id', $spdrCategory->id) // Only SPDR category logs
                ->get();

            $leaveRecords = []; // You can implement leave logic here if needed

            $result[] = [
                'employee_id' => $employee->id,
                'title' => $employee->user->name,
                'division' => $employee->division->title ?? 'N/A',
                'subdivision' => $employee->subDivision->title ?? 'N/A',
                'role' => $employee->role->title ?? 'N/A',
                'position' => $employee->position->title ?? 'N/A',
                'division_id' => $employee->division_id,
                'archive' => $employee->archive,
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

        return response()->json($result);
    }

    public function getHoliday(Request $request)
    {
        $date = $request->get('date');
        
        // You can implement holiday checking logic here
        // For now, return false (no holiday)
        return response()->json(false);
    }

    public function print(Request $request)
    {
        $date = $request->get('date');
        $holiday = $request->get('holiday', 0);
        
        if (!$date) {
            return response()->json(['error' => 'Date is required'], 400);
        }

        // Get SPDR data for the date
        $spdrData = $this->getSPDRDataForDate($date);
        $leaveList = $this->getLeaveListForDate($date);
        
        // Generate PDF
        $pdf = Pdf::loadView('reports.spdr-pdf', [
            'date' => $date,
            'holiday' => $holiday,
            'spdrData' => $spdrData,
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
        $filename = 'SPDR_Report_' . Carbon::parse($date)->format('Y-m-d') . '.pdf';
        
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
    
    
    private function getSPDRDataForDate($date)
    {
        // Get SPDR category ID first
        $spdrCategory = Category::where('title', 'SPDR')->first();
        if (!$spdrCategory) {
            return [];
        }

        // Get SPDR role ID
        $spdrRole = \App\Models\Role::where('title', 'SPDR Estimating')->first();
        if (!$spdrRole) {
            return [];
        }

        // Get only SPDR role employees with SPDR logs for the specific date
        $employees = Employee::with(['user', 'division', 'subDivision', 'role', 'position'])
            ->where('role_id', $spdrRole->id) // Only SPDR role
            ->whereHas('logs', function($query) use ($date, $spdrCategory) {
                $query->where('date', $date)
                      ->where('category_id', $spdrCategory->id);
            })
            ->get();

        $result = [];

        foreach ($employees as $employee) {
            $logs = Log::with(['category', 'task', 'builder', 'dweling', 'status'])
                ->where('employee_id', $employee->id)
                ->where('date', $date)
                ->where('category_id', $spdrCategory->id) // Only SPDR category logs
                ->get();

            $totalMinutes = $logs->sum('duration');
            $totalHours = $totalMinutes / 60;

            $result[] = [
                'name' => $employee->user->name,
                'division' => $employee->division->title ?? 'N/A',
                'sub_division' => $employee->subDivision->title ?? 'N/A',
                'role' => $employee->role->title ?? 'N/A',
                'position' => $employee->position->title ?? 'N/A',
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
                'total_hours' => $totalHours
            ];
        }

        return $result;
    }
    
    private function getLeaveListForDate($date)
    {
        // Get SPDR role ID
        $spdrRole = \App\Models\Role::where('title', 'SPDR Estimating')->first();
        if (!$spdrRole) {
            return [];
        }

        // Get SPDR role employees who are on leave (no logs for the date)
        $employeesOnLeave = Employee::with(['user', 'division', 'subDivision', 'role', 'position'])
            ->where('role_id', $spdrRole->id) // Only SPDR role
            ->where('archive', null)
            ->whereDoesntHave('logs', function($query) use ($date) {
                $query->where('date', $date);
            })
            ->get();

        $leaveList = [];

        foreach ($employeesOnLeave as $employee) {
            $leaveList[] = [
                'name' => $employee->user->name,
                'division' => $employee->division->title ?? 'N/A',
                'sub_division' => $employee->subDivision->title ?? 'N/A',
                'role' => $employee->role->title ?? 'N/A',
                'level' => $employee->position->title ?? 'N/A',
                'reason' => 'No Assignments'
            ];
        }

        return $leaveList;
    }
}
