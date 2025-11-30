<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;
use App\Models\Employee;
use App\Models\Category;
use App\Models\Holiday;
use Carbon\Carbon;

class MonthlyReportController extends Controller
{
    public function index()
    {
        // Check report access based on role
        if (!hasReportAccess('monthly')) {
            abort(403, 'Access denied. You do not have permission to access this report.');
        }
        
        return view('reports.monthly');
    }

    public function setDate()
    {
        $minDate = Log::min('date') ?? Carbon::now()->startOfMonth();
        $maxDate = Log::max('date') ?? Carbon::now()->endOfMonth();
        
        return response()->json([
            'minDate' => $minDate,
            'maxDate' => $maxDate
        ]);
    }

    public function getCategories(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        $date1 = $request->date1;
        $date2 = $request->date2;

        $categories = Category::whereHas('logs', function($query) use ($date1, $date2) {
            $query->whereBetween('date', [$date1, $date2]);
        })->get();

        return response()->json($categories);
    }

    public function getData(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        $date1 = $request->date1;
        $date2 = $request->date2;
        $category = $request->category;

        // Get all employees first, then filter by category if specified
        $employeeQuery = Employee::with('user');
        
        if ($category) {
            // Filter employees by category (role)
            $employeeQuery->whereHas('logs', function($query) use ($category, $date1, $date2) {
                $query->where('category_id', $category)
                      ->whereBetween('date', [$date1, $date2]);
            });
        } else {
            // Only get employees who have logs in the date range
            $employeeQuery->whereHas('logs', function($query) use ($date1, $date2) {
                $query->whereBetween('date', [$date1, $date2]);
            });
        }
        
        $employees = $employeeQuery->get();

        if ($employees->isEmpty()) {
            return response()->json([]);
        }

        $employeeIds = $employees->pluck('id');
        
        // Get logs for all employees in the date range
        $query = Log::with(['employee.user', 'category'])
            ->whereIn('employee_id', $employeeIds)
            ->whereBetween('date', [$date1, $date2]);

        if ($category) {
            $query->where('category_id', $category);
        }

        $logs = $query->get();
        
        $dates = $this->getDatesInRange($date1, $date2);
        
        $reportData = [];
        $employeeStats = [];

        foreach ($dates as $date) {
            $carbonDate = Carbon::parse($date);
            $dayName = $carbonDate->format('l');
            $dayNumber = $carbonDate->format('d');
            
            $dayData = [
                'date' => $date,
                'day_name' => $dayName,
                'day_number' => $dayNumber,
                'employees' => []
            ];

            foreach ($employees as $employee) {
                $dayLogs = $logs->where('employee_id', $employee->id)
                    ->filter(function($log) use ($date) {
                        return $log->date->format('Y-m-d') === $date;
                    });

                $totalHours = $dayLogs->sum('duration');
                // Cap total hours to maximum 24 hours per day (or 16 hours for reasonable work day)
                $totalHours = min($totalHours, 16);
                $dayData['employees'][$employee->id] = $totalHours > 0 ? number_format($totalHours, 2) : '';

                if (!isset($employeeStats[$employee->id])) {
                    $employeeStats[$employee->id] = [
                        'name' => $employee->user->name,
                        'total_hours' => 0,
                        'working_days' => 0
                    ];
                }

                if ($totalHours > 0) {
                    $employeeStats[$employee->id]['total_hours'] += $totalHours;
                    $employeeStats[$employee->id]['working_days']++;
                }
            }

            $reportData[] = $dayData;
        }

        // Calculate holidays and weekends
        $holidays = Holiday::whereBetween('date', [$date1, $date2])->get();
        $weekendDays = 0;
        $publicHolidays = $holidays->count();
        
        foreach ($dates as $date) {
            $carbonDate = Carbon::parse($date);
            if ($carbonDate->isWeekend()) {
                $weekendDays++;
            }
        }
        
        $effectiveDays = count($dates) - $weekendDays - $publicHolidays;

        return response()->json([
            'report_data' => $reportData,
            'employee_stats' => $employeeStats,
            'effective_days' => $effectiveDays,
            'public_holidays' => $publicHolidays,
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
}
