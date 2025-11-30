<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Models\User;

class ReportDebugController extends Controller
{
    /**
     * Debug endpoint to check report access for current user
     * Access: /report/debug
     */
    public function debug(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'error' => 'User not authenticated'
            ], 401);
        }
        
        // Get employee
        $employee = Employee::where('email', $user->email)->first();
        
        // Get role info
        $userRole = null;
        $userRoleId = null;
        $employeeRole = null;
        $employeeRoleId = null;
        
        if ($employee) {
            $employee->load('role');
            if ($employee->role) {
                $employeeRole = $employee->role->title;
                $employeeRoleId = $employee->role->id;
            }
            $employeeRoleId = $employee->role_id;
        }
        
        if ($user->role_id) {
            $user->load('role');
            if ($user->role) {
                $userRole = $user->role->title;
                $userRoleId = $user->role->id;
            }
        }
        
        // Get report configs
        $reportConfigs = config('reports', []);
        
        // Check access for each report
        $reportAccess = [];
        foreach ($reportConfigs as $reportRoute => $allowedRoles) {
            $hasAccess = hasReportAccess($reportRoute);
            $reportAccess[$reportRoute] = [
                'has_access' => $hasAccess,
                'allowed_roles' => $allowedRoles,
                'user_role_match' => in_array($employeeRole ?? $userRole, $allowedRoles) || in_array('*', $allowedRoles)
            ];
        }
        
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => $user->is_admin,
            ],
            'employee' => $employee ? [
                'id' => $employee->id,
                'name' => $employee->name,
                'email' => $employee->email,
                'is_admin' => $employee->is_admin,
                'role_id' => $employee->role_id,
            ] : null,
            'roles' => [
                'employee_role' => [
                    'id' => $employeeRoleId,
                    'title' => $employeeRole,
                ],
                'user_role' => [
                    'id' => $userRoleId,
                    'title' => $userRole,
                ],
                'active_role' => $employeeRole ?? $userRole,
            ],
            'report_configs' => $reportConfigs,
            'report_access' => $reportAccess,
        ]);
    }
}

