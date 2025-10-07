<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function index()
    {
        return view('tables.task');
    }

    public function getData(Request $request)
    {
        $params = json_decode($request->query('data'), true);
        
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 10;
        $search = $params['search'] ?? '';
        $sort = $params['sort'] ?? 'title';
        $order = $params['order'] ?? 'ASC';
        
        $query = Task::query();
        
        if ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }
        
        $total = $query->count();
        
        $tasks = $query->orderBy($sort, $order)
                      ->offset(($page - 1) * $limit)
                      ->limit($limit)
                      ->get();
        
        $result = [
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'search' => $search,
            'sort' => $sort,
            'order' => $order,
            'offset' => ($page - 1) * $limit,
            'rows' => $tasks->map(function($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                ];
            })
        ];
        
        return response()->json($result);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $task = Task::create([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            return response()->json(['success' => true, 'message' => 'Task created successfully', 'data' => $task]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create task'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $task = Task::findOrFail($id);
            $task->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            return response()->json(['success' => true, 'message' => 'Task updated successfully', 'data' => $task]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update task'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $task = Task::findOrFail($id);
            
            // Check if task is being used
            $logCount = DB::table('logs')->where('task', $task->title)->count();
            if ($logCount > 0) {
                return response()->json(['success' => false, 'message' => 'Cannot delete task that is being used in logs'], 400);
            }
            
            $task->delete();
            return response()->json(['success' => true, 'message' => 'Task deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete task'], 500);
        }
    }
}
