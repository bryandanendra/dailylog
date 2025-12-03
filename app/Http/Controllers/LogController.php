<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\Employee;
use App\Models\Category;
use App\Models\Task;
use App\Models\Builder;
use App\Models\Dweling;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employee = Employee::where('email', $user->email)->first();
        $today = now()->format('Y-m-d');
        
        // Get today's logs for current employee
        $logs = Log::where('employee_id', $employee->id)
                   ->where('date', $today)
                   ->with(['category', 'task', 'builder', 'dweling', 'status'])
                   ->orderBy('created_at', 'desc')
                   ->get();
        
        // Get master data for dropdowns
        $categories = Category::where('archive', false)->orderBy('title')->get();
        $tasks = Task::where('archive', false)->orderBy('title')->get();
        $builders = Builder::where('archive', false)->orderBy('title')->get();
        $dwelings = Dweling::where('archive', false)->orderBy('title')->get();
        $statuses = Status::where('archive', false)->orderBy('title')->get();
        
        return view('log.index', compact('logs', 'categories', 'tasks', 'builders', 'dwelings', 'statuses', 'today'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'qty' => 'required|integer|min:0',
            'category' => 'required|string', // Changed to required
            'task' => 'required|string', // Changed to required
            'builder' => 'required|string', // Changed to required
            'dweling' => 'required|string', // Changed to required
            'status' => 'required|string', // Changed to required
            'duration' => 'required|numeric|min:0',
            'note' => 'nullable|string',
            'temp' => 'boolean',
        ]);
        
        $user = Auth::user();
        $employee = Employee::where('email', $user->email)->first();
        
        // Find master data by title
        $category = Category::where('title', $request->category)->first();
        $task = Task::where('title', $request->task)->first();
        $builder = Builder::where('title', $request->builder)->first();
        $dweling = Dweling::where('title', $request->dweling)->first();
        $status = Status::where('title', $request->status)->first();
        
        // Return error if any required field not found
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found. Please select a valid category.'
            ], 400);
        }
        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found. Please select a valid task.'
            ], 400);
        }
        if (!$builder) {
            return response()->json([
                'success' => false,
                'message' => 'Builder not found. Please select a valid builder.'
            ], 400);
        }
        if (!$dweling) {
            return response()->json([
                'success' => false,
                'message' => 'Dwelling not found. Please select a valid dwelling.'
            ], 400);
        }
        if (!$status) {
            return response()->json([
                'success' => false,
                'message' => 'Status not found. Please select a valid status.'
            ], 400);
        }
        
        $log = Log::create([
            'date' => $request->date,
            'employee_id' => $employee->id,
            'subject' => $request->subject,
            'description' => $request->description,
            'qty' => $request->qty,
            'category_id' => $category->id,
            'task_id' => $task->id,
            'builder_id' => $builder->id,
            'dweling_id' => $dweling->id,
            'status_id' => $status->id,
            'duration' => $request->duration,
            'note' => $request->note,
            'temp' => $request->temp ?? false,
            'approved' => false,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Log berhasil disimpan',
            'log' => $log->load(['category', 'task', 'builder', 'dweling', 'status'])
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $employee = Employee::where('email', $user->email)->first();
        
        $log = Log::where('id', $id)
                  ->where('employee_id', $employee->id)
                  ->firstOrFail();
        
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'qty' => 'required|integer|min:0',
            'category' => 'required|string', // Changed to required
            'task' => 'required|string', // Changed to required
            'builder' => 'required|string', // Changed to required
            'dweling' => 'required|string', // Changed to required
            'status' => 'required|string', // Changed to required
            'duration' => 'required|numeric|min:0',
            'note' => 'nullable|string',
            'temp' => 'boolean',
        ]);
        
        // Find master data by title
        $category = Category::where('title', $request->category)->first();
        $task = Task::where('title', $request->task)->first();
        $builder = Builder::where('title', $request->builder)->first();
        $dweling = Dweling::where('title', $request->dweling)->first();
        $status = Status::where('title', $request->status)->first();
        
        // Return error if any required field not found
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found. Please select a valid category.'
            ], 400);
        }
        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found. Please select a valid task.'
            ], 400);
        }
        if (!$builder) {
            return response()->json([
                'success' => false,
                'message' => 'Builder not found. Please select a valid builder.'
            ], 400);
        }
        if (!$dweling) {
            return response()->json([
                'success' => false,
                'message' => 'Dwelling not found. Please select a valid dwelling.'
            ], 400);
        }
        if (!$status) {
            return response()->json([
                'success' => false,
                'message' => 'Status not found. Please select a valid status.'
            ], 400);
        }
        
        $log->update([
            'subject' => $request->subject,
            'description' => $request->description,
            'qty' => $request->qty,
            'category_id' => $category->id,
            'task_id' => $task->id,
            'builder_id' => $builder->id,
            'dweling_id' => $dweling->id,
            'status_id' => $status->id,
            'duration' => $request->duration,
            'note' => $request->note,
            'temp' => $request->temp ?? false,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Log berhasil diupdate',
            'log' => $log->load(['category', 'task', 'builder', 'dweling', 'status'])
        ]);
    }
    
    public function destroy($id)
    {
        $user = Auth::user();
        $employee = Employee::where('email', $user->email)->first();
        
        $log = Log::where('id', $id)
                  ->where('employee_id', $employee->id)
                  ->firstOrFail();
        
        $log->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Log berhasil dihapus'
        ]);
    }
    
    public function getLogsByDate($date)
    {
        $user = Auth::user();
        $employee = Employee::where('email', $user->email)->first();
        
        $logs = Log::where('employee_id', $employee->id)
                   ->where('date', $date)
                   ->with(['category', 'task', 'builder', 'dweling', 'status'])
                   ->orderBy('created_at', 'desc')
                   ->get();
        
        return response()->json($logs);
    }
    
    public function getAutocompleteData()
    {
        $user = Auth::user();
        $employee = Employee::where('email', $user->email)->first();
        
        // Get unique values from logs for this employee
        $subjects = Log::where('employee_id', $employee->id)
                      ->whereNotNull('subject')
                      ->where('subject', '!=', '')
                      ->distinct()
                      ->pluck('subject')
                      ->sort()
                      ->values();
        
        // Get master data
        $categories = Category::where('archive', false)->orderBy('title')->pluck('title');
        $tasks = Task::where('archive', false)->orderBy('title')->pluck('title');
        $builders = Builder::where('archive', false)->orderBy('title')->pluck('title');
        $dwelings = Dweling::where('archive', false)->orderBy('title')->pluck('title');
        $statuses = Status::where('archive', false)->orderBy('title')->pluck('title');
        
        return response()->json([
            'subjects' => $subjects,
            'categories' => $categories,
            'tasks' => $tasks,
            'builders' => $builders,
            'dwelings' => $dwelings,
            'statuses' => $statuses
        ]);
    }
    
    public function checkApproval(Request $request)
    {
        $user = Auth::user();
        $employee = Employee::where('email', $user->email)->first();
        
        $ids = $request->query('ids');
        if (!$ids) {
            return response()->json([]);
        }
        
        $logIds = explode(',', $ids);
        $logs = Log::where('employee_id', $employee->id)
                   ->whereIn('id', $logIds)
                   ->select('id', 'approved')
                   ->get();
        
        return response()->json($logs);
    }
}