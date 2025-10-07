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
        return view('reports.category');
    }

    public function getData(Request $request)
    {
        $date = $request->get('date');
        
        if (!$date) {
            return response()->json([]);
        }

        // Get all SPDR Team employees with their logs for the specific date
        $employees = Employee::with(['user', 'division', 'subDivision', 'role', 'position'])
            ->where('division_id', 1) // SPDR Team division
            ->whereHas('logs', function($query) use ($date) {
                $query->where('date', $date);
            })
            ->get();

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
        // Get all SPDR Team employees with their logs for the specific date
        $employees = Employee::with(['user', 'division', 'subDivision', 'role', 'position'])
            ->where('division_id', 1) // SPDR Team division
            ->whereHas('logs', function($query) use ($date) {
                $query->where('date', $date);
            })
            ->get();

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
