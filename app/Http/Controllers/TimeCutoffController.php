<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeCutoff;
use Illuminate\Support\Facades\Log;

class TimeCutoffController extends Controller
{
    public function index()
    {
        return view('cutoff.index');
    }

    public function getCurrent(Request $request)
    {
        try {
            $cutoff = TimeCutoff::where('active', true)->first();
            
            if (!$cutoff) {
                // Return default if not set
                return response()->json([
                    'day_offset' => 0,
                    'hour' => '23',
                    'minute' => '00',
                    'time' => '23:00:00',
                    'timezone' => 'GMT +7'
                ]);
            }

            // Parse time to get hour and minute
            $timeParts = explode(':', $cutoff->time);
            
            return response()->json([
                'day_offset' => $cutoff->day_offset,
                'hour' => $timeParts[0],
                'minute' => $timeParts[1],
                'time' => $cutoff->time,
                'timezone' => 'GMT +7'
            ]);

        } catch (\Exception $e) {
            Log::error('Error in TimeCutoffController@getCurrent: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch cutoff settings'], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'day_offset' => 'required|integer|min:0|max:1',
                'hour' => 'required|integer|min:0|max:23',
                'minute' => 'required|integer|min:0|max:59',
            ]);

            $day_offset = $request->input('day_offset');
            $hour = str_pad($request->input('hour'), 2, '0', STR_PAD_LEFT);
            $minute = str_pad($request->input('minute'), 2, '0', STR_PAD_LEFT);
            $time = "$hour:$minute:00";

            // Deactivate all existing cutoff settings
            TimeCutoff::where('active', true)->update(['active' => false]);

            // Create or update new cutoff setting
            $cutoff = TimeCutoff::create([
                'time' => $time,
                'day_offset' => $day_offset,
                'active' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Time cut off updated successfully',
                'cutoff' => $cutoff
            ]);

        } catch (\Exception $e) {
            Log::error('Error in TimeCutoffController@update: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}

