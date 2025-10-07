<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\Employee;
use App\Models\Category;
use App\Models\Task;
use App\Models\Dweling;
use App\Models\Builder;
use Carbon\Carbon;

class BIReportController extends Controller
{
    public function index()
    {
        return view('reports.bi');
    }

    public function setDate()
    {
        $minDate = Log::min('date');
        $maxDate = Log::max('date');
        
        return response()->json([
            'min_date' => $minDate ? Carbon::parse($minDate)->format('Y-m-d') : null,
            'max_date' => $maxDate ? Carbon::parse($maxDate)->format('Y-m-d') : null
        ]);
    }

    public function getCategories(Request $request)
    {
        $date1 = $request->get('date1');
        $date2 = $request->get('date2');
        
        $categories = Category::whereHas('logs', function($query) use ($date1, $date2) {
            $query->whereBetween('date', [$date1, $date2]);
        })->get();
        
        return response()->json($categories);
    }

    public function getFilterData(Request $request)
    {
        $selectedEmployees = $request->input('employees', []);
        
        // Handle both GET and POST requests
        if ($request->isMethod('get')) {
            $selectedEmployees = $request->query('employees', []);
        }
        
        // Get all employees with users
        $employees = Employee::with('user')->get()->map(function($employee) {
            return [
                'id' => $employee->id,
                'name' => $employee->user ? $employee->user->name : 'No User'
            ];
        });
        
        // If employees are selected, filter other data based on those employees
        if (!empty($selectedEmployees) && $selectedEmployees[0] !== '') {
            $employeeIds = is_array($selectedEmployees) ? $selectedEmployees : [$selectedEmployees];
            
            // Get categories that the selected employees have worked on
            $categories = Log::whereIn('employee_id', $employeeIds)
                ->with('category')
                ->get()
                ->pluck('category')
                ->filter()
                ->unique('id')
                ->map(function($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->title
                    ];
                });
            
            // Get tasks that the selected employees have worked on
            $tasks = Log::whereIn('employee_id', $employeeIds)
                ->with('task')
                ->get()
                ->pluck('task')
                ->filter()
                ->unique('id')
                ->map(function($task) {
                    return [
                        'id' => $task->id,
                        'name' => $task->title
                    ];
                });
            
            // Get dwellings that the selected employees have worked on
            $dwellings = Log::whereIn('employee_id', $employeeIds)
                ->with('dweling')
                ->get()
                ->pluck('dweling')
                ->filter()
                ->unique('id')
                ->map(function($dwelling) {
                    return [
                        'id' => $dwelling->id,
                        'name' => $dwelling->title
                    ];
                });
            
            // Get builders that the selected employees have worked on
            $builders = Log::whereIn('employee_id', $employeeIds)
                ->with('builder')
                ->get()
                ->pluck('builder')
                ->filter()
                ->unique('id')
                ->map(function($builder) {
                    return [
                        'id' => $builder->id,
                        'name' => $builder->title
                    ];
                });
            
            // Get statuses that the selected employees have worked on
            $statuses = Log::whereIn('employee_id', $employeeIds)
                ->with('status')
                ->get()
                ->pluck('status')
                ->filter()
                ->unique('id')
                ->map(function($status) {
                    return [
                        'id' => $status->id,
                        'name' => $status->title
                    ];
                });
            
            // Get subjects that the selected employees have worked on
            $subjects = Log::whereIn('employee_id', $employeeIds)
                ->distinct()
                ->pluck('subject')
                ->filter()
                ->map(function($subject) {
                    return [
                        'id' => $subject,
                        'name' => $subject
                    ];
                });
        } else {
            // If no employees selected, show all data
            $categories = Category::all()->map(function($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->title
                ];
            });
            
            $tasks = Task::all()->map(function($task) {
                return [
                    'id' => $task->id,
                    'name' => $task->title
                ];
            });
            
            $dwellings = Dweling::all()->map(function($dwelling) {
                return [
                    'id' => $dwelling->id,
                    'name' => $dwelling->title
                ];
            });
            
            $builders = Builder::all()->map(function($builder) {
                return [
                    'id' => $builder->id,
                    'name' => $builder->title
                ];
            });
            
            $statuses = Log::with('status')->distinct()->pluck('status_id')->filter()->map(function($statusId) {
                $status = \App\Models\Status::find($statusId);
                return [
                    'id' => $statusId,
                    'name' => $status ? $status->title : 'Unknown Status'
                ];
            });
            
            $subjects = Log::distinct()->pluck('subject')->filter()->map(function($subject) {
                return [
                    'id' => $subject,
                    'name' => $subject
                ];
            });
        }
        
        $result = [
            'employees' => $employees,
            'categories' => $categories,
            'tasks' => $tasks,
            'dwellings' => $dwellings,
            'builders' => $builders,
            'statuses' => $statuses,
            'subjects' => $subjects
        ];
        
        \Log::info('Filter data result:', [
            'employees_count' => $employees->count(),
            'categories_count' => $categories->count(),
            'tasks_count' => $tasks->count(),
            'dwellings_count' => $dwellings->count(),
            'builders_count' => $builders->count(),
            'statuses_count' => $statuses->count(),
            'subjects_count' => $subjects->count()
        ]);
        
        return response()->json($result);
    }

    public function getData(Request $request)
    {
        $date1 = $request->get('date1');
        $date2 = $request->get('date2');
        $worktime = $request->get('worktime');
        $subject = $request->get('subject');
        $description = $request->get('description');
        
        // Get filter arrays
        $employees = $request->get('employees', []);
        $categories = $request->get('categories', []);
        $tasks = $request->get('tasks', []);
        $dwellings = $request->get('dwellings', []);
        $builders = $request->get('builders', []);
        $statuses = $request->get('statuses', []);
        $subjects = $request->get('subjects', []);
        
        // Convert to arrays if they're not already and filter out empty values
        if (!is_array($employees)) $employees = $employees ? [$employees] : [];
        if (!is_array($categories)) $categories = $categories ? [$categories] : [];
        if (!is_array($tasks)) $tasks = $tasks ? [$tasks] : [];
        if (!is_array($dwellings)) $dwellings = $dwellings ? [$dwellings] : [];
        if (!is_array($builders)) $builders = $builders ? [$builders] : [];
        if (!is_array($statuses)) $statuses = $statuses ? [$statuses] : [];
        if (!is_array($subjects)) $subjects = $subjects ? [$subjects] : [];
        
        // Filter out empty values from arrays
        $employees = array_filter($employees, function($value) { return !empty($value); });
        $categories = array_filter($categories, function($value) { return !empty($value); });
        $tasks = array_filter($tasks, function($value) { return !empty($value); });
        $dwellings = array_filter($dwellings, function($value) { return !empty($value); });
        $builders = array_filter($builders, function($value) { return !empty($value); });
        $statuses = array_filter($statuses, function($value) { return !empty($value); });
        $subjects = array_filter($subjects, function($value) { return !empty($value); });
        
        // Debug logging
        \Log::info('BI Report getData request:', [
            'all_params' => $request->all(),
            'employees_raw' => $request->get('employees', []),
            'employees_filtered' => $employees,
            'employees_type' => gettype($employees),
            'employees_empty' => empty($employees),
            'employees_count' => count($employees)
        ]);

        $query = Log::with(['employee.user', 'category', 'task', 'dweling', 'builder'])
            ->whereBetween('date', [$date1, $date2]);

        // Apply filters
        if (!empty($employees)) {
            \Log::info('Applying employee filter:', ['employee_ids' => $employees]);
            $query->whereIn('employee_id', $employees);
        } else {
            \Log::info('No employee filter applied - showing all employees');
        }
        
        if (!empty($categories)) {
            $query->whereIn('category_id', $categories);
        }
        
        if (!empty($tasks)) {
            $query->whereIn('task_id', $tasks);
        }
        
        if (!empty($dwellings)) {
            $query->whereIn('dweling_id', $dwellings);
        }
        
        if (!empty($builders)) {
            $query->whereIn('builder_id', $builders);
        }
        
        if (!empty($statuses)) {
            $query->whereIn('status_id', $statuses);
        }
        
        if (!empty($subjects)) {
            $query->whereIn('subject', $subjects);
        }
        
        if ($subject) {
            $query->where('subject', 'like', '%' . $subject . '%');
        }
        
        if ($description) {
            $query->where('description', 'like', '%' . $description . '%');
        }
        
        if ($worktime) {
            $query->where('duration', '>=', $worktime);
        }

        $logs = $query->get();

        $reportData = [];
        $employeeStats = [];
        $categoryStats = [];
        $taskStats = [];
        $dwellingStats = [];
        $builderStats = [];

        foreach ($logs as $log) {
            $employeeId = $log->employee_id;
            $categoryId = $log->category_id;
            $taskId = $log->task_id;
            $dwellingId = $log->dweling_id;
            $builderId = $log->builder_id;

            if (!isset($employeeStats[$employeeId])) {
                $employeeStats[$employeeId] = [
                    'name' => $log->employee->user->name,
                    'total_hours' => 0,
                    'working_days' => 0,
                    'logs_count' => 0
                ];
            }

            if (!isset($categoryStats[$categoryId])) {
                $categoryStats[$categoryId] = [
                    'name' => $log->category ? $log->category->title : 'N/A',
                    'total_hours' => 0,
                    'logs_count' => 0
                ];
            }

            if (!isset($taskStats[$taskId])) {
                $taskStats[$taskId] = [
                    'name' => $log->task ? $log->task->title : 'N/A',
                    'total_hours' => 0,
                    'logs_count' => 0
                ];
            }

            if (!isset($dwellingStats[$dwellingId])) {
                $dwellingStats[$dwellingId] = [
                    'name' => $log->dweling ? $log->dweling->title : 'N/A',
                    'total_hours' => 0,
                    'logs_count' => 0
                ];
            }

            if (!isset($builderStats[$builderId])) {
                $builderStats[$builderId] = [
                    'name' => $log->builder ? $log->builder->title : 'N/A',
                    'total_hours' => 0,
                    'logs_count' => 0
                ];
            }

            $employeeStats[$employeeId]['total_hours'] += $log->duration;
            $employeeStats[$employeeId]['logs_count']++;
            $categoryStats[$categoryId]['total_hours'] += $log->duration;
            $categoryStats[$categoryId]['logs_count']++;
            $taskStats[$taskId]['total_hours'] += $log->duration;
            $taskStats[$taskId]['logs_count']++;
            $dwellingStats[$dwellingId]['total_hours'] += $log->duration;
            $dwellingStats[$dwellingId]['logs_count']++;
            $builderStats[$builderId]['total_hours'] += $log->duration;
            $builderStats[$builderId]['logs_count']++;

            $reportData[] = [
                'date' => $log->date->format('M d, Y'),
                'name' => $log->employee->user->name,
                'task' => $log->task ? $log->task->title : 'N/A',
                'subject' => $log->subject,
                'description' => $log->description,
                'status' => $log->status ? $log->status->title : 'N/A',
                'dwelling_type' => $log->dweling ? $log->dweling->title : 'N/A',
                'note' => $log->note,
                'hours' => $log->duration,
                'category' => $log->category ? $log->category->title : 'N/A',
                'builder' => $log->builder ? $log->builder->title : 'N/A'
            ];
        }

        $dates = $this->getDatesInRange($date1, $date2);
        $workingDays = $this->calculateWorkingDays($dates);

        foreach ($employeeStats as $employeeId => $stats) {
            $employeeLogs = $logs->where('employee_id', $employeeId);
            $uniqueDates = $employeeLogs->pluck('date')->map(function($date) {
                return $date->format('Y-m-d');
            })->unique();
            $employeeStats[$employeeId]['working_days'] = $uniqueDates->count();
        }

        return response()->json([
            'report_data' => $reportData,
            'employee_stats' => $employeeStats,
            'category_stats' => $categoryStats,
            'task_stats' => $taskStats,
            'dwelling_stats' => $dwellingStats,
            'builder_stats' => $builderStats,
            'working_days' => $workingDays,
            'dates' => $dates
        ]);
    }

    private function getDatesInRange($startDate, $endDate)
    {
        $dates = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($current->lte($end)) {
            $dates[] = $current->format('Y-m-d');
            $current->addDay();
        }

        return $dates;
    }

    private function calculateWorkingDays($dates)
    {
        $workingDays = 0;
        foreach ($dates as $date) {
            $carbonDate = Carbon::parse($date);
            if ($carbonDate->isWeekday()) {
                $workingDays++;
            }
        }
        return $workingDays;
    }
}
