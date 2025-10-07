<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        return view('tables.role');
    }

    public function getData(Request $request)
    {
        $params = json_decode($request->query('data'), true);
        
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 10;
        $search = $params['search'] ?? '';
        $sort = $params['sort'] ?? 'title';
        $order = $params['order'] ?? 'ASC';
        
        $query = Role::query();
        
        if ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }
        
        $total = $query->count();
        
        $roles = $query->orderBy($sort, $order)
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
            'rows' => $roles->map(function($role) {
                return [
                    'id' => $role->id,
                    'title' => $role->title,
                    'description' => $role->description,
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
            $role = Role::create([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            return response()->json(['success' => true, 'message' => 'Role created successfully', 'data' => $role]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create role'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $role = Role::findOrFail($id);
            $role->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            return response()->json(['success' => true, 'message' => 'Role updated successfully', 'data' => $role]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update role'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            
            // Check if role is being used
            $employeeCount = DB::table('employees')->where('role_id', $id)->count();
            if ($employeeCount > 0) {
                return response()->json(['success' => false, 'message' => 'Cannot delete role that is being used by employees'], 400);
            }
            
            $role->delete();
            return response()->json(['success' => true, 'message' => 'Role deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete role'], 500);
        }
    }
}
