<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dweling;
use Illuminate\Support\Facades\DB;

class DwelingController extends Controller
{
    public function index()
    {
        return view('tables.dweling');
    }

    public function getData(Request $request)
    {
        $params = json_decode($request->query('data'), true);
        
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 10;
        $search = $params['search'] ?? '';
        $sort = $params['sort'] ?? 'title';
        $order = $params['order'] ?? 'ASC';
        
        $query = Dweling::query();
        
        if ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }
        
        $total = $query->count();
        
        $dwelings = $query->orderBy($sort, $order)
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
            'rows' => $dwelings->map(function($dweling) {
                return [
                    'id' => $dweling->id,
                    'title' => $dweling->title,
                    'description' => $dweling->description,
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
            $dweling = Dweling::create([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            return response()->json(['success' => true, 'message' => 'Dweling created successfully', 'data' => $dweling]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create dwelling'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $dweling = Dweling::findOrFail($id);
            $dweling->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            return response()->json(['success' => true, 'message' => 'Dweling updated successfully', 'data' => $dweling]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update dwelling'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $dweling = Dweling::findOrFail($id);
            
            // Check if dwelling is being used
            $logCount = DB::table('logs')->where('dweling', $dweling->title)->count();
            if ($logCount > 0) {
                return response()->json(['success' => false, 'message' => 'Cannot delete dwelling that is being used in logs'], 400);
            }
            
            $dweling->delete();
            return response()->json(['success' => true, 'message' => 'Dweling deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete dwelling'], 500);
        }
    }
}
