<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkStatus;
use Illuminate\Support\Facades\DB;

class WorkStatusController extends Controller
{
    public function index()
    {
        return view('tables.wtime');
    }

    public function getData(Request $request)
    {
        $params = json_decode($request->query('data'), true);
        
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 10;
        $search = $params['search'] ?? '';
        $sort = $params['sort'] ?? 'title';
        $order = $params['order'] ?? 'ASC';
        
        $query = WorkStatus::query();
        
        if ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }
        
        $total = $query->count();
        
        $workStatuses = $query->orderBy($sort, $order)
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
            'rows' => $workStatuses->map(function($workStatus) {
                return [
                    'id' => $workStatus->id,
                    'title' => $workStatus->title,
                    'description' => $workStatus->description,
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
            $workStatus = WorkStatus::create([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            return response()->json(['success' => true, 'message' => 'Work Status created successfully', 'data' => $workStatus]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create work status'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $workStatus = WorkStatus::findOrFail($id);
            $workStatus->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            return response()->json(['success' => true, 'message' => 'Work Status updated successfully', 'data' => $workStatus]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update work status'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $workStatus = WorkStatus::findOrFail($id);
            
            // Check if work status is being used
            $logCount = DB::table('logs')->where('wtime', $workStatus->title)->count();
            if ($logCount > 0) {
                return response()->json(['success' => false, 'message' => 'Cannot delete work status that is being used in logs'], 400);
            }
            
            $workStatus->delete();
            return response()->json(['success' => true, 'message' => 'Work Status deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete work status'], 500);
        }
    }
}
