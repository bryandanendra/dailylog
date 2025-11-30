<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use App\Models\Division;
use App\Models\SubDivision;
use App\Models\Role;
use App\Models\Position;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function index()
    {
        // Check if user is admin
        $user = Auth::user();
        if (!$user || !$user->is_admin) {
            abort(403, 'Unauthorized access. Only administrators can access this page.');
        }
        
        return view('employee.index');
    }

    public function getData(Request $request)
    {
        // Check if user is admin
        $user = Auth::user();
        if (!$user || !$user->is_admin) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }
        
        $params = json_decode($request->get('data'), true);
        
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 25;
        $search = $params['search'] ?? '';
        $sort = $params['sort'] ?? 'id';
        $order = $params['order'] ?? 'ASC';
        $tab = $params['tab'] ?? 1;

        $query = Employee::with(['division', 'subDivision', 'role', 'position', 'superior'])
            ->select('employees.*');

        // Filter berdasarkan tab
        switch ($tab) {
            case 1: // Current Employees
                $query->where('archive', false)->where('is_approved', true);
                break;
            case 2: // Registration Request
                $query->where('archive', false)->where('is_approved', false);
                break;
            case 3: // Archive Employees
                $query->where('archive', true);
                break;
        }

        // Search functionality
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('username', 'LIKE', "%{$search}%");
            });
        }

        // Sorting
        $query->orderBy($sort, $order);

        // Pagination
        $total = $query->count();
        $employees = $query->skip(($page - 1) * $limit)
                          ->take($limit)
                          ->get();

        // Format data untuk frontend
        $formattedData = [];
        $offset = ($page - 1) * $limit;

        foreach ($employees as $employee) {
            $formattedData[] = [
                'id' => $employee->id,
                'title' => $employee->name,
                'username' => $employee->username,
                'join_date' => $employee->join_date ? $employee->join_date->format('Y-m-d') : null,
                'administrator' => $employee->is_admin,
                'approved' => $employee->can_approve,
                'archive' => $employee->archive,
                'archivereport' => $employee->cutoff_exception,
                'monthlyreport' => true, // Default values
                'bireport' => true,
                'regularreport' => true,
                'categoryreport' => true,
                'parent_id' => $employee->superior_id,
                'order_pos' => 0,
                'division_id' => $employee->division_id,
                'subdivision_id' => $employee->sub_division_id,
                'role_id' => $employee->role_id,
                'position_id' => $employee->position_id,
                'description' => $employee->description,
                'slack_channel' => '',
                'offset' => ++$offset
            ];
        }

        $result = [
            'rows' => $formattedData,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'search' => $search,
            'sort' => $sort,
            'order' => $order
        ];

        return response()->json(json_encode($result));
    }

    public function getCombo()
    {
        $divisions = Division::where('archive', false)->get(['id', 'title']);
        $subdivisions = SubDivision::where('archive', false)->get(['id', 'title']);
        $roles = Role::where('archive', false)->get(['id', 'title']);
        $positions = Position::where('archive', false)->get(['id', 'title']);
        $employees = Employee::where('archive', false)->get(['id', 'name as title']);

        $combo = [
            'division' => $divisions,
            'subdivision' => $subdivisions,
            'role' => $roles,
            'position' => $positions,
            'parent' => $employees
        ];

        return response()->json(json_encode($combo));
    }

    public function store(Request $request)
    {
        // Check if user is admin
        $user = Auth::user();
        if (!$user || !$user->is_admin) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|email|unique:employees,username',
            'password' => 'required|string|min:6',
            'join_date' => 'required|date',
            'division_id' => 'required|exists:divisions,id',
            'sub_division_id' => 'required|exists:sub_divisions,id',
            'role_id' => 'required|exists:roles,id',
            'position_id' => 'required|exists:positions,id',
        ]);
        
        // Validasi tambahan untuk email
        $request->validate([
            'username' => 'unique:employees,email',
        ]);

        $employee = Employee::create([
            'name' => $validatedData['name'],
            'username' => $validatedData['username'],
            'email' => $validatedData['username'], // Menggunakan username (email) sebagai email
            'password' => Hash::make($validatedData['password']),
            'join_date' => $validatedData['join_date'],
            'division_id' => $validatedData['division_id'],
            'sub_division_id' => $validatedData['sub_division_id'],
            'role_id' => $validatedData['role_id'],
            'position_id' => $validatedData['position_id'],
            'superior_id' => $request->superior_id ?: null,
            'description' => $request->description ?? null,
            'is_admin' => $request->is_admin ?? false,
            'can_approve' => $request->can_approve ?? false,
            'cutoff_exception' => $request->cutoff_exception ?? false,
            'archive' => false,
            'is_approved' => false // Employee baru belum diapprove
        ]);

        // Sinkronisasi ke User jika user dengan email yang sama sudah ada
        $user = User::where('email', $employee->email)->first();
        if ($user) {
            $user->update([
                'is_admin' => $employee->is_admin,
                'can_approve' => $employee->can_approve,
                'cutoff_exception' => $employee->cutoff_exception,
                'division_id' => $employee->division_id,
                'sub_division_id' => $employee->sub_division_id,
                'role_id' => $employee->role_id,
                'position_id' => $employee->position_id,
            ]);
            $employee->update(['user_id' => $user->id]);
        }

        return response()->json(['success' => true, 'employee' => $employee]);
    }

    public function update(Request $request, $id)
    {
        // Check if user is admin
        $user = Auth::user();
        if (!$user || !$user->is_admin) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }
        
        $employee = Employee::findOrFail($id);
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|email|unique:employees,username,' . $id,
            'join_date' => 'required|date',
            'division_id' => 'required|exists:divisions,id',
            'sub_division_id' => 'required|exists:sub_divisions,id',
            'role_id' => 'required|exists:roles,id',
            'position_id' => 'required|exists:positions,id',
        ]);
        
        // Validasi tambahan untuk email
        $request->validate([
            'username' => 'unique:employees,email,' . $id,
        ]);

        $updateData = [
            'name' => $validatedData['name'],
            'username' => $validatedData['username'],
            'email' => $validatedData['username'], // Menggunakan username (email) sebagai email
            'join_date' => $validatedData['join_date'],
            'division_id' => $validatedData['division_id'],
            'sub_division_id' => $validatedData['sub_division_id'],
            'role_id' => $validatedData['role_id'],
            'position_id' => $validatedData['position_id'],
            'superior_id' => $request->has('superior_id') ? ($request->superior_id ?: null) : $employee->superior_id,
            'description' => $request->description ?? $employee->description,
            'is_admin' => $request->is_admin ?? $employee->is_admin,
            'can_approve' => $request->can_approve ?? $employee->can_approve,
            'cutoff_exception' => $request->cutoff_exception ?? $employee->cutoff_exception,
            'archive' => $request->archive ?? $employee->archive
        ];

        // Update password only if provided
        if ($request->password) {
            $updateData['password'] = Hash::make($request->password);
        }

        $employee->update($updateData);

        // Sinkronisasi akses ke User jika employee memiliki user_id
        if ($employee->user_id) {
            $user = User::find($employee->user_id);
            if ($user) {
                $user->update([
                    'name' => $employee->name,
                    'email' => $employee->email,
                    'is_admin' => $employee->is_admin,
                    'can_approve' => $employee->can_approve,
                    'cutoff_exception' => $employee->cutoff_exception,
                    'division_id' => $employee->division_id,
                    'sub_division_id' => $employee->sub_division_id,
                    'role_id' => $employee->role_id,
                    'position_id' => $employee->position_id,
                ]);
                
                if ($request->password) {
                    $user->update(['password' => Hash::make($request->password)]);
                }
            }
        } else {
            // Jika tidak ada user_id, cari user berdasarkan email
            $user = User::where('email', $employee->email)->first();
            if ($user) {
                $user->update([
                    'name' => $employee->name,
                    'email' => $employee->email,
                    'is_admin' => $employee->is_admin,
                    'can_approve' => $employee->can_approve,
                    'cutoff_exception' => $employee->cutoff_exception,
                    'division_id' => $employee->division_id,
                    'sub_division_id' => $employee->sub_division_id,
                    'role_id' => $employee->role_id,
                    'position_id' => $employee->position_id,
                ]);
                
                if ($request->password) {
                    $user->update(['password' => Hash::make($request->password)]);
                }
                
                // Update user_id di employee jika belum ada
                if (!$employee->user_id) {
                    $employee->update(['user_id' => $user->id]);
                }
            } else {
                // Create new user if not exists (only if approved)
                if ($employee->is_approved) {
                    $user = User::create([
                        'name' => $employee->name,
                        'username' => $employee->username, // Added username
                        'email' => $employee->email,
                        'password' => $employee->password, // Use existing hashed password
                        'join_date' => $employee->join_date, // Added join_date
                        'is_admin' => $employee->is_admin,
                        'can_approve' => $employee->can_approve,
                        'cutoff_exception' => $employee->cutoff_exception,
                        'division_id' => $employee->division_id,
                        'sub_division_id' => $employee->sub_division_id,
                        'role_id' => $employee->role_id,
                        'position_id' => $employee->position_id,
                    ]);
                    $employee->update(['user_id' => $user->id]);
                }
            }
        }

        return response()->json(['success' => true, 'employee' => $employee]);
    }

    public function destroy(Request $request)
    {
        try {
            $id = $request->get('id');
            if (!$id) {
                return response()->json(['success' => false, 'message' => 'ID is required']);
            }

            $employee = Employee::find($id);
            if (!$employee) {
                return response()->json(['success' => false, 'message' => 'Employee not found']);
            }
            
            if ($employee->archive) {
                // If already archived, delete permanently
                try {
                    $employee->delete();
                    return response()->json(['success' => true, 'message' => 'Employee permanently deleted']);
                } catch (\Exception $e) {
                    return response()->json(['success' => false, 'message' => 'Cannot delete employee because they have related data: ' . $e->getMessage()]);
                }
            } else {
                // Archive the employee
                $employee->update(['archive' => true]);
                
                // Also archive the linked user if exists
                if ($employee->user_id) {
                    $user = \App\Models\User::find($employee->user_id);
                    if ($user) {
                        $user->update(['archive' => true]);
                    }
                }
                
                return response()->json(['success' => true, 'message' => 'Employee archived successfully']);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Employee Delete Error: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error($e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Server Error: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $employee = Employee::with(['division', 'subDivision', 'role', 'position', 'superior'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $employee->id,
                'name' => $employee->name,
                'username' => $employee->username,
                'join_date' => $employee->join_date ? $employee->join_date->format('Y-m-d') : null,
                'division_id' => $employee->division_id,
                'sub_division_id' => $employee->sub_division_id,
                'role_id' => $employee->role_id,
                'position_id' => $employee->position_id,
                'superior_id' => $employee->superior_id,
                'description' => $employee->description,
                'is_admin' => $employee->is_admin,
                'can_approve' => $employee->can_approve,
                'cutoff_exception' => $employee->cutoff_exception,
                'archive' => $employee->archive
            ]
        ]);
    }

    public function resetPassword(Request $request)
    {
        $id = $request->get('id');
        $newPassword = $request->get('newpass');
        
        $employee = Employee::findOrFail($id);
        $employee->update([
            'password' => Hash::make($newPassword)
        ]);

        return response()->json(['success' => true]);
    }
    
    public function approveRegistration(Request $request)
    {
        // Check if user is admin
        $user = Auth::user();
        if (!$user || !$user->is_admin) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }
        
        $id = $request->get('id');
        $employee = Employee::findOrFail($id);
        
        $employee->update([
            'is_approved' => true
        ]);

        // Sinkronisasi akses ke User setelah approval
        if ($employee->user_id) {
            $userModel = User::find($employee->user_id);
            if ($userModel) {
                $userModel->update([
                    'is_admin' => $employee->is_admin,
                    'can_approve' => $employee->can_approve,
                    'cutoff_exception' => $employee->cutoff_exception,
                    'division_id' => $employee->division_id,
                    'sub_division_id' => $employee->sub_division_id,
                    'role_id' => $employee->role_id,
                    'position_id' => $employee->position_id,
                ]);
            }
        } else {
            // Jika tidak ada user_id, cari user berdasarkan email
            $userModel = User::where('email', $employee->email)->first();
            if ($userModel) {
                $userModel->update([
                    'is_admin' => $employee->is_admin,
                    'can_approve' => $employee->can_approve,
                    'cutoff_exception' => $employee->cutoff_exception,
                    'division_id' => $employee->division_id,
                    'sub_division_id' => $employee->sub_division_id,
                    'role_id' => $employee->role_id,
                    'position_id' => $employee->position_id,
                ]);
                
                // Update user_id di employee jika belum ada
                if (!$employee->user_id) {
                    $employee->update(['user_id' => $userModel->id]);
                }
            } else {
                // Create new user
                $userModel = User::create([
                    'name' => $employee->name,
                    'username' => $employee->username, // Added username
                    'email' => $employee->email,
                    'password' => $employee->password,
                    'join_date' => $employee->join_date, // Added join_date
                    'is_admin' => $employee->is_admin,
                    'can_approve' => $employee->can_approve,
                    'cutoff_exception' => $employee->cutoff_exception,
                    'division_id' => $employee->division_id,
                    'sub_division_id' => $employee->sub_division_id,
                    'role_id' => $employee->role_id,
                    'position_id' => $employee->position_id,
                ]);
                $employee->update(['user_id' => $userModel->id]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Employee registration approved successfully']);
    }

    public function rejectRegistration(Request $request)
    {
        $id = $request->get('id');
        $employee = Employee::findOrFail($id);
        
        $employee->update([
            'archive' => true  // Archive the employee (soft delete)
        ]);

        return response()->json(['success' => true, 'message' => 'Employee registration rejected']);
    }

    public function bulkApprove(Request $request)
    {
        $ids = $request->get('ids', []);
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No employees selected']);
        }

        Employee::whereIn('id', $ids)->update([
            'is_approved' => true
        ]);

        // Create users for approved employees
        $employees = Employee::whereIn('id', $ids)->get();
        foreach ($employees as $employee) {
            if (!$employee->user_id) {
                $user = User::where('email', $employee->email)->first();
                if (!$user) {
                    $user = User::create([
                        'name' => $employee->name,
                        'username' => $employee->username, // Added username
                        'email' => $employee->email,
                        'password' => $employee->password,
                        'join_date' => $employee->join_date, // Added join_date
                        'is_admin' => $employee->is_admin,
                        'can_approve' => $employee->can_approve,
                        'cutoff_exception' => $employee->cutoff_exception,
                        'division_id' => $employee->division_id,
                        'sub_division_id' => $employee->sub_division_id,
                        'role_id' => $employee->role_id,
                        'position_id' => $employee->position_id,
                    ]);
                }
                $employee->update(['user_id' => $user->id]);
            }
        }

        return response()->json(['success' => true, 'message' => count($ids) . ' employee registrations approved']);
    }

    public function bulkReject(Request $request)
    {
        $ids = $request->get('ids', []);
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No employees selected']);
        }

        Employee::whereIn('id', $ids)->update([
            'archive' => true
        ]);

        return response()->json(['success' => true, 'message' => count($ids) . ' employee registrations rejected']);
    }
}
