<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use Illuminate\Support\Facades\DB;

class DivisionController extends Controller
{
    public function index()
    {
        return view('tables.division');
    }

    public function getData(Request $request)
    {
        try {
            $params = json_decode($request->query('data'), true);
            
            $page = $params['page'] ?? 1;
            $limit = $params['limit'] ?? 10;
            $search = $params['search'] ?? '';
            $sort = $params['sort'] ?? 'title';
            $order = $params['order'] ?? 'ASC';
            
            $query = Division::query();
            
            if ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
            }
            
            $total = $query->count();
            
            $divisions = $query->orderBy($sort, $order)
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
                'rows' => $divisions->map(function($division) {
                    return [
                        'id' => $division->id,
                        'title' => $division->title,
                        'description' => $division->description,
                    ];
                })
            ];
            
            return response()->json($result);
        } catch (\Exception $e) {
            \Log::error('DivisionController getData error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $division = Division::create([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            return response()->json(['success' => true, 'message' => 'Division created successfully', 'data' => $division]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create division'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $division = Division::findOrFail($id);
            $division->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            return response()->json(['success' => true, 'message' => 'Division updated successfully', 'data' => $division]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update division'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $division = Division::findOrFail($id);
            
            // Check if division is being used
            $employeeCount = DB::table('employees')->where('division_id', $id)->count();
            if ($employeeCount > 0) {
                return response()->json(['success' => false, 'message' => 'Cannot delete division that is being used by employees'], 400);
            }
            
            $division->delete();
            return response()->json(['success' => true, 'message' => 'Division deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete division'], 500);
        }
    }
}
