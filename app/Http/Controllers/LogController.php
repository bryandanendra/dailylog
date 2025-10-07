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
            'qty' => 'required|integer|min:1',
            'category' => 'nullable|string',
            'task' => 'nullable|string',
            'builder' => 'nullable|string',
            'dweling' => 'nullable|string',
            'status' => 'nullable|string',
            'duration' => 'required|numeric|min:0',
            'note' => 'nullable|string',
            'temp' => 'boolean',
        ]);
        
        $user = Auth::user();
        $employee = Employee::where('email', $user->email)->first();
        
        // Find or create master data by title
        $category = null;
        $task = null;
        $builder = null;
        $dweling = null;
        $status = null;
        
        if (!empty($request->category)) {
            $category = Category::where('title', $request->category)->first();
        }
        if (!empty($request->task)) {
            $task = Task::where('title', $request->task)->first();
        }
        if (!empty($request->builder)) {
            $builder = Builder::where('title', $request->builder)->first();
        }
        if (!empty($request->dweling)) {
            $dweling = Dweling::where('title', $request->dweling)->first();
        }
        if (!empty($request->status)) {
            $status = Status::where('title', $request->status)->first();
        }
        
        // If not found, use first available
        if (!$category) $category = Category::first();
        if (!$task) $task = Task::first();
        if (!$builder) $builder = Builder::first();
        if (!$dweling) $dweling = Dweling::first();
        if (!$status) $status = Status::first();
        
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
            'qty' => 'required|integer|min:1',
            'category' => 'nullable|string',
            'task' => 'nullable|string',
            'builder' => 'nullable|string',
            'dweling' => 'nullable|string',
            'status' => 'nullable|string',
            'duration' => 'required|numeric|min:0',
            'note' => 'nullable|string',
            'temp' => 'boolean',
        ]);
        
        // Find or create master data by title
        $category = null;
        $task = null;
        $builder = null;
        $dweling = null;
        $status = null;
        
        if (!empty($request->category)) {
            $category = Category::where('title', $request->category)->first();
        }
        if (!empty($request->task)) {
            $task = Task::where('title', $request->task)->first();
        }
        if (!empty($request->builder)) {
            $builder = Builder::where('title', $request->builder)->first();
        }
        if (!empty($request->dweling)) {
            $dweling = Dweling::where('title', $request->dweling)->first();
        }
        if (!empty($request->status)) {
            $status = Status::where('title', $request->status)->first();
        }
        
        // If not found, use first available
        if (!$category) $category = Category::first();
        if (!$task) $task = Task::first();
        if (!$builder) $builder = Builder::first();
        if (!$dweling) $dweling = Dweling::first();
        if (!$status) $status = Status::first();
        
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
}