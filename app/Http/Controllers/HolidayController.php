<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Holiday;
use Illuminate\Support\Facades\Log;

class HolidayController extends Controller
{
    public function index()
    {
        return view('offwork.holiday');
    }

    public function getData(Request $request)
    {
        try {
            $params = json_decode($request->query('data'), true);
            
            $page = $params['page'] ?? 1;
            $limit = $params['limit'] ?? 15;
            $search = $params['search'] ?? '';
            $sort = $params['sort'] ?? 'date';
            $order = $params['order'] ?? 'asc';

            $query = Holiday::where('archive', false);

            // Search
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
                });
            }

            // Sorting
            $query->orderBy($sort, $order);

            // Pagination
            $total = $query->count();
            $offset = ($page - 1) * $limit;
            $holidays = $query->skip($offset)->take($limit)->get();

            // Format data
            $rows = $holidays->map(function($holiday) {
                return [
                    'id' => $holiday->id,
                    'title' => $holiday->title,
                    'date' => $holiday->date->format('d/m/Y'),
                    'description' => $holiday->description ?? ''
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
            Log::error('Error in HolidayController@getData: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'date' => 'required|date',
                'description' => 'nullable|string'
            ]);

            // Add default values
            $validated['archive'] = false;

            $holiday = Holiday::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Holiday created successfully',
                'data' => $holiday
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating holiday: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $holiday = Holiday::findOrFail($id);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'date' => 'required|date',
                'description' => 'nullable|string'
            ]);

            $holiday->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Holiday updated successfully',
                'data' => $holiday
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating holiday: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $holiday = Holiday::findOrFail($id);
            $holiday->delete();

            return response()->json([
                'success' => true,
                'message' => 'Holiday deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting holiday: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
