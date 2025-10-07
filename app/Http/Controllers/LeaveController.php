<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveType;

class LeaveController extends Controller
{
    public function index()
    {
        return view('offwork.leave');
    }

    public function getData(Request $request)
    {
        try {
            $params = json_decode($request->query('data'), true);
            
            $page = $params['page'] ?? 1;
            $limit = $params['limit'] ?? 15;
            $search = $params['search'] ?? '';
            $sort = $params['sort'] ?? 'id';
            $order = $params['order'] ?? 'asc';

            $query = LeaveType::query();

            // Search
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
                });
            }

            // Sorting
            $query->orderBy($sort, $order);

            // Pagination
            $total = $query->count();
            $offset = ($page - 1) * $limit;
            $leaveTypes = $query->skip($offset)->take($limit)->get();

            // Format data
            $rows = $leaveTypes->map(function($type) {
                return [
                    'id' => $type->id,
                    'name' => $type->name,
                    'description' => $type->description ?? ''
                ];
            });

            return response()->json([
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'search' => $search,
                'sort' => $sort,
                'order' => $order,
                'offset' => $offset,
                'rows' => $rows
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:leave_types,name',
                'description' => 'nullable|string'
            ]);

            $leaveType = LeaveType::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Leave type created successfully',
                'data' => $leaveType
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $leaveType = LeaveType::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:leave_types,name,' . $id,
                'description' => 'nullable|string'
            ]);

            $leaveType->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Leave type updated successfully',
                'data' => $leaveType
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $leaveType = LeaveType::findOrFail($id);
            $leaveType->delete();

            return response()->json([
                'success' => true,
                'message' => 'Leave type deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
