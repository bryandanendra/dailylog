<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use Illuminate\Support\Facades\DB;

class StatusController extends Controller
{
    public function index()
    {
        return view('tables.status');
    }

    public function getData(Request $request)
    {
        $params = json_decode($request->query('data'), true);
        
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 10;
        $search = $params['search'] ?? '';
        $sort = $params['sort'] ?? 'title';
        $order = $params['order'] ?? 'ASC';
        
        $query = Status::query();
        
        if ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }
        
        $total = $query->count();
        
        $statuses = $query->orderBy($sort, $order)
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
            'rows' => $statuses->map(function($status) {
                return [
                    'id' => $status->id,
                    'title' => $status->title,
                    'description' => $status->description,
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
            $status = Status::create([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            return response()->json(['success' => true, 'message' => 'Status created successfully', 'data' => $status]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create status'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $status = Status::findOrFail($id);
            $status->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            return response()->json(['success' => true, 'message' => 'Status updated successfully', 'data' => $status]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update status'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $status = Status::findOrFail($id);
            
            // Check if status is being used
            $logCount = DB::table('logs')->where('status', $status->title)->count();
            if ($logCount > 0) {
                return response()->json(['success' => false, 'message' => 'Cannot delete status that is being used in logs'], 400);
            }
            
            $status->delete();
            return response()->json(['success' => true, 'message' => 'Status deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete status'], 500);
        }
    }
}
