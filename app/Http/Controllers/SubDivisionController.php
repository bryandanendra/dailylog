<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubDivision;
use Illuminate\Support\Facades\DB;

class SubDivisionController extends Controller
{
    public function index()
    {
        $divisions = \App\Models\Division::where('archive', false)
                                        ->orderBy('title')
                                        ->get(['id', 'title']);
        return view('tables.subdivision', compact('divisions'));
    }

    public function getDivisions()
    {
        $divisions = \App\Models\Division::where('archive', false)
                                        ->orderBy('title')
                                        ->get(['id', 'title']);
        return response()->json($divisions);
    }

    public function getData(Request $request)
    {
        $params = json_decode($request->query('data'), true);
        
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 10;
        $search = $params['search'] ?? '';
        $sort = $params['sort'] ?? 'title';
        $order = $params['order'] ?? 'ASC';
        
        $query = SubDivision::query();
        
        if ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }
        
        $total = $query->count();
        
        $subdivisions = $query->orderBy($sort, $order)
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
            'rows' => $subdivisions->map(function($subdivision) {
                return [
                    'id' => $subdivision->id,
                    'title' => $subdivision->title,
                    'description' => $subdivision->description,
                    'division_id' => $subdivision->division_id,
                    'division_name' => $subdivision->division ? $subdivision->division->title : '-',
                ];
            })
        ];
        
        return response()->json($result);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'division_id' => 'required|exists:divisions,id',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $subdivision = SubDivision::create([
                'title' => $request->title,
                'division_id' => $request->division_id,
                'description' => $request->description,
            ]);

            return response()->json(['success' => true, 'message' => 'Sub Division created successfully', 'data' => $subdivision]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create sub division'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'division_id' => 'required|exists:divisions,id',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $subdivision = SubDivision::findOrFail($id);
            $subdivision->update([
                'title' => $request->title,
                'division_id' => $request->division_id,
                'description' => $request->description,
            ]);

            return response()->json(['success' => true, 'message' => 'Sub Division updated successfully', 'data' => $subdivision]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update sub division'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $subdivision = SubDivision::findOrFail($id);
            
            // Check if subdivision is being used
            $employeeCount = DB::table('employees')->where('subdivision_id', $id)->count();
            if ($employeeCount > 0) {
                return response()->json(['success' => false, 'message' => 'Cannot delete sub division that is being used by employees'], 400);
            }
            
            $subdivision->delete();
            return response()->json(['success' => true, 'message' => 'Sub Division deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete sub division'], 500);
        }
    }
}
