<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getUnreadCount()
    {
        try {
            $user = Auth::user();
            $employee = $user->employee;
            
            if (!$employee) {
                return response()->json(['count' => 0]);
            }
            
            $count = Notification::where('employee_id', $employee->id)
                ->where('read_status', false)
                ->count();
            
            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            \Log::error('Error getting unread notification count: ' . $e->getMessage());
            return response()->json(['count' => 0], 500);
        }
    }
    
    public function getNotifications()
    {
        try {
            $user = Auth::user();
            $employee = $user->employee;
            
            if (!$employee) {
                return response()->json(['notifications' => []]);
            }
            
            $notifications = Notification::where('employee_id', $employee->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function($notif) {
                    return [
                        'id' => $notif->id,
                        'title' => $notif->title,
                        'message' => $notif->message,
                        'date' => $notif->date->format('d/m/Y'),
                        'created_at' => $notif->created_at->diffForHumans(),
                        'read_status' => $notif->read_status
                    ];
                });
            
            return response()->json(['notifications' => $notifications]);
        } catch (\Exception $e) {
            \Log::error('Error getting notifications: ' . $e->getMessage());
            return response()->json(['notifications' => []], 500);
        }
    }
    
    public function markAsRead($id)
    {
        try {
            $user = Auth::user();
            $employee = $user->employee;
            
            if (!$employee) {
                return response()->json(['success' => false, 'message' => 'Employee not found'], 404);
            }
            
            $notification = Notification::where('id', $id)
                ->where('employee_id', $employee->id)
                ->first();
            
            if (!$notification) {
                return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
            }
            
            $notification->update(['read_status' => true]);
            
            return response()->json(['success' => true, 'message' => 'Notification marked as read']);
        } catch (\Exception $e) {
            \Log::error('Error marking notification as read: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function markAllAsRead()
    {
        try {
            $user = Auth::user();
            $employee = $user->employee;
            
            if (!$employee) {
                return response()->json(['success' => false, 'message' => 'Employee not found'], 404);
            }
            
            Notification::where('employee_id', $employee->id)
                ->where('read_status', false)
                ->update(['read_status' => true]);
            
            return response()->json(['success' => true, 'message' => 'All notifications marked as read']);
        } catch (\Exception $e) {
            \Log::error('Error marking all notifications as read: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

