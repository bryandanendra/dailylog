<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;
use Illuminate\Support\Facades\DB;

class PositionController extends Controller
{
    public function index()
    {
        return view('tables.position');
    }

    public function getData(Request $request)
    {
        $params = json_decode($request->query('data'), true);
        
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 10;
        $search = $params['search'] ?? '';
        $sort = $params['sort'] ?? 'title';
        $order = $params['order'] ?? 'ASC';
        
        $query = Position::query();
        
        if ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }
        
        $total = $query->count();
        
        $positions = $query->orderBy($sort, $order)
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
            'rows' => $positions->map(function($position) {
                return [
                    'id' => $position->id,
                    'title' => $position->title,
                    'description' => $position->description,
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
            $position = Position::create([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            return response()->json(['success' => true, 'message' => 'Position created successfully', 'data' => $position]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create position'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $position = Position::findOrFail($id);
            $position->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            return response()->json(['success' => true, 'message' => 'Position updated successfully', 'data' => $position]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update position'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $position = Position::findOrFail($id);
            
            // Check if position is being used
            $employeeCount = DB::table('employees')->where('position_id', $id)->count();
            if ($employeeCount > 0) {
                return response()->json(['success' => false, 'message' => 'Cannot delete position that is being used by employees'], 400);
            }
            
            $position->delete();
            return response()->json(['success' => true, 'message' => 'Position deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete position'], 500);
        }
    }
}
