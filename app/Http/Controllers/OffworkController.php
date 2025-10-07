<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\Employee;

class OffworkController extends Controller
{
    public function index()
    {
        return view('offwork.index');
    }

    public function getData(Request $request)
    {
        try {
            $params = json_decode($request->query('data'), true);
            
            $page = $params['page'] ?? 1;
            $limit = $params['limit'] ?? 15;
            $search = $params['search'] ?? '';
            $sort = $params['sort'] ?? 'date';
            $order = $params['order'] ?? 'desc';

            $query = Leave::with('employee.user')->where('archive', false);

            // Search
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                      ->orWhere('leave_type', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%")
                      ->orWhereHas('employee.user', function($q) use ($search) {
                          $q->where('name', 'LIKE', "%{$search}%");
                      });
                });
            }

            // Sorting
            $query->orderBy($sort, $order);

            // Pagination
            $total = $query->count();
            $offset = ($page - 1) * $limit;
            $leaves = $query->skip($offset)->take($limit)->get();

            // Format data
            $rows = $leaves->map(function($leave) {
                return [
                    'id' => $leave->id,
                    'title' => $leave->title,
                    'date' => $leave->date->format('d/m/Y'),
                    'leave_type' => $leave->leave_type,
                    'employee_name' => $leave->employee ? $leave->employee->user->name : 'N/A',
                    'employee_id' => $leave->employee_id,
                    'description' => $leave->description ?? ''
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
                'title' => 'required|string|max:255',
                'date' => 'required|date',
                'leave_type' => 'required|string',
                'employee_id' => 'nullable|exists:employees,id',
                'description' => 'nullable|string'
            ]);

            // Add default values for status and archive
            $validated['status'] = 'pending';
            $validated['archive'] = false;

            $leave = Leave::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Leave record created successfully',
                'data' => $leave
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating offwork record: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $leave = Leave::findOrFail($id);
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'date' => 'required|date',
                'leave_type' => 'required|string',
                'employee_id' => 'nullable|exists:employees,id',
                'description' => 'nullable|string'
            ]);

            $leave->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Leave record updated successfully',
                'data' => $leave
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
            $leave = Leave::findOrFail($id);
            $leave->delete();

            return response()->json([
                'success' => true,
                'message' => 'Leave record deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function getEmployees(Request $request)
    {
        try {
            $employees = Employee::with('user')
                ->where('archive', false)
                ->whereHas('user') // Only get employees with valid user
                ->get()
                ->map(function($emp) {
                    return [
                        'id' => $emp->id,
                        'name' => $emp->user->name
                    ];
                });

            return response()->json($employees);
        } catch (\Exception $e) {
            \Log::error('Error loading employees for offwork: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getLeaveTypes()
    {
        $types = [
            'Annual Leave',
            'Half-Day Off',
            'Joint Leave',
            'AWOL',
            'Public Holiday Replacement',
            'Sick Leave',
            'Special Leave',
            'Unpaid Leave',
            'Weekend',
            'Public Holiday',
            'SPDR Commercial Assignment',
            'No Assignments',
            'Half-Day',
            'Inactive',
            'Maternity Leave',
        ];

        return response()->json($types);
    }
}
