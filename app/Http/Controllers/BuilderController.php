<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Builder;
use Illuminate\Support\Facades\DB;

class BuilderController extends Controller
{
    public function index()
    {
        return view('tables.builder');
    }

    public function getData(Request $request)
    {
        $params = json_decode($request->query('data'), true);
        
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 10;
        $search = $params['search'] ?? '';
        $sort = $params['sort'] ?? 'title';
        $order = $params['order'] ?? 'ASC';
        
        $query = Builder::query();
        
        if ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }
        
        $total = $query->count();
        
        $builders = $query->orderBy($sort, $order)
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
            'rows' => $builders->map(function($builder) {
                return [
                    'id' => $builder->id,
                    'title' => $builder->title,
                    'description' => $builder->description,
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
            $builder = Builder::create([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            return response()->json(['success' => true, 'message' => 'Builder created successfully', 'data' => $builder]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create builder'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $builder = Builder::findOrFail($id);
            $builder->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            return response()->json(['success' => true, 'message' => 'Builder updated successfully', 'data' => $builder]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update builder'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $builder = Builder::findOrFail($id);
            
            // Check if builder is being used
            $logCount = DB::table('logs')->where('builder', $builder->title)->count();
            if ($logCount > 0) {
                return response()->json(['success' => false, 'message' => 'Cannot delete builder that is being used in logs'], 400);
            }
            
            $builder->delete();
            return response()->json(['success' => true, 'message' => 'Builder deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete builder'], 500);
        }
    }
}
