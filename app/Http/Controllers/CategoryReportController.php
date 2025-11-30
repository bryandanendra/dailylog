<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Log;
use App\Models\Category;
use Barryvdh\DomPDF\Facade\Pdf;

class CategoryReportController extends Controller
{
    public function index()
    {
        // Check report access based on role
        if (!hasReportAccess('category')) {
            abort(403, 'Access denied. You do not have permission to access this report.');
        }
        
        return view('reports.category');
    }

    public function getData(Request $request)
    {
        $date = $request->get('date');
        
        if (!$date) {
            return response()->json([]);
        }

        $currentUser = auth()->user();
        $currentEmployee = Employee::where('user_id', $currentUser->id)->first();

        if (!$currentEmployee) {
            return response()->json(['category' => []]);
        }

        // Get employees based on approval hierarchy logic
        // Same logic as ApprovalController
        $employees = Employee::with(['user', 'division', 'subDivision', 'role', 'position'])
            ->where('archive', false)
            ->whereHas('logs', function($query) use ($date) {
                $query->where('date', $date);
            });

        // Filter based on can_approve permission and hierarchy
        if ($currentUser->can_approve) {
            // Get current user's position level
            $currentPositionLevel = config('approval.position_levels')[$currentEmployee->position->title] ?? 0;
            
            $employees = $employees->where(function($query) use ($currentEmployee, $currentPositionLevel) {
                // Include current user's own logs
                $query->where('id', $currentEmployee->id)
                    // OR same division employees
                    ->orWhere(function($q) use ($currentEmployee, $currentPositionLevel) {
                        $q->where('division_id', $currentEmployee->division_id)
                            ->where(function($q2) use ($currentEmployee, $currentPositionLevel) {
                                // Subordinates (employees whose superior is current user)
                                $q2->where('superior_id', $currentEmployee->id)
                                // OR employees with lower position level in same division
                                ->orWhereHas('position', function($pq) use ($currentPositionLevel) {
                                    $positionTitles = collect(config('approval.position_levels'))
                                        ->filter(function($level) use ($currentPositionLevel) {
                                            return $level < $currentPositionLevel;
                                        })
                                        ->keys()
                                        ->toArray();
                                    $pq->whereIn('title', $positionTitles);
                                });
                            });
                    });
            });
        } else {
            // Regular employees can only see their own logs
            $employees = $employees->where('id', $currentEmployee->id);
        }

        $employees = $employees->get();

        $result = [];

        foreach ($employees as $employee) {
            $logs = Log::with(['category', 'task', 'builder', 'dweling', 'status'])
                ->where('employee_id', $employee->id)
                ->where('date', $date)
                ->get();

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
                })->toArray()
            ];
        }

        // Group by category
        $categoryData = [];
        $categories = Category::all();
        
        foreach ($categories as $category) {
            $categoryEmployees = [];
            
            foreach ($result as $employee) {
                $categoryLogs = array_filter($employee['log'], function($log) use ($category) {
                    return $log['category'] === $category->title;
                });
                
                if (!empty($categoryLogs)) {
                    $categoryEmployees[] = [
                        'employee' => $employee['title'] . ',' . $employee['division'] . ',' . $employee['subdivision'],
                        'items' => array_values($categoryLogs)
                    ];
                }
            }
            
            if (!empty($categoryEmployees)) {
                $categoryData[] = [
                    'category' => $category->title,
                    'employee' => $categoryEmployees
                ];
            }
        }

        return response()->json(['category' => $categoryData]);
    }

    public function print(Request $request)
    {
        $date = $request->get('date');
        $selectedCategories = json_decode($request->get('data', '[]'), true);
        
        if (!$date || empty($selectedCategories)) {
            return response()->json(['error' => 'Date and categories are required'], 400);
        }

        // Get data for selected categories
        $categoryData = $this->getCategoryDataForDate($date, $selectedCategories);
        
        // Generate PDF
        $pdf = Pdf::loadView('reports.category-pdf', [
            'date' => $date,
            'categoryData' => $categoryData,
            'selectedCategories' => $selectedCategories
        ]);
        
        $pdf->setPaper('A4', 'landscape');
        
        // Save PDF to storage
        $filename = 'Category_Report_' . $date . '.pdf';
        $pdf->save(storage_path('app/public/reports/' . $filename));
        
        $pdfUrl = url('storage/reports/' . $filename);
        
        return response()->json([$pdfUrl]);
    }
    
    private function getCategoryDataForDate($date, $selectedCategories)
    {
        $currentUser = auth()->user();
        $currentEmployee = Employee::where('user_id', $currentUser->id)->first();

        if (!$currentEmployee) {
            return [];
        }

        // Get employees based on approval hierarchy logic
        $employees = Employee::with(['user', 'division', 'subDivision', 'role', 'position'])
            ->where('archive', false)
            ->whereHas('logs', function($query) use ($date) {
                $query->where('date', $date);
            });

        // Filter based on can_approve permission and hierarchy
        if ($currentUser->can_approve) {
            // Get current user's position level
            $currentPositionLevel = config('approval.position_levels')[$currentEmployee->position->title] ?? 0;
            
            $employees = $employees->where(function($query) use ($currentEmployee, $currentPositionLevel) {
                // Include current user's own logs
                $query->where('id', $currentEmployee->id)
                    // OR same division employees
                    ->orWhere(function($q) use ($currentEmployee, $currentPositionLevel) {
                        $q->where('division_id', $currentEmployee->division_id)
                            ->where(function($q2) use ($currentEmployee, $currentPositionLevel) {
                                // Subordinates (employees whose superior is current user)
                                $q2->where('superior_id', $currentEmployee->id)
                                // OR employees with lower position level in same division
                                ->orWhereHas('position', function($pq) use ($currentPositionLevel) {
                                    $positionTitles = collect(config('approval.position_levels'))
                                        ->filter(function($level) use ($currentPositionLevel) {
                                            return $level < $currentPositionLevel;
                                        })
                                        ->keys()
                                        ->toArray();
                                    $pq->whereIn('title', $positionTitles);
                                });
                            });
                    });
            });
        } else {
            // Regular employees can only see their own logs
            $employees = $employees->where('id', $currentEmployee->id);
        }

        $employees = $employees->get();

        $result = [];

        foreach ($employees as $employee) {
            $logs = Log::with(['category', 'task', 'builder', 'dweling', 'status'])
                ->where('employee_id', $employee->id)
                ->where('date', $date)
                ->get();

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
                })->toArray()
            ];
        }

        // Group by selected categories only
        $categoryData = [];
        
        foreach ($selectedCategories as $categoryTitle) {
            $categoryEmployees = [];
            
            foreach ($result as $employee) {
                $categoryLogs = array_filter($employee['log'], function($log) use ($categoryTitle) {
                    return $log['category'] === $categoryTitle;
                });
                
                if (!empty($categoryLogs)) {
                    $categoryEmployees[] = [
                        'employee' => $employee['title'] . ',' . $employee['division'] . ',' . $employee['subdivision'],
                        'items' => array_values($categoryLogs)
                    ];
                }
            }
            
            if (!empty($categoryEmployees)) {
                $categoryData[] = [
                    'category' => $categoryTitle,
                    'employee' => $categoryEmployees
                ];
            }
        }

        return $categoryData;
    }
}
