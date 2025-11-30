<?php

if (!function_exists('hasReportAccess')) {
    /**
     * Check if current user has access to a specific report based on their role
     * 
     * @param string $reportRoute Report route name (e.g., 'monthly', 'spdr', 'bi', 'category')
     * @return bool
     */
    function hasReportAccess($reportRoute)
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }
        
        // Admin always has access to all reports
        $employee = \App\Models\Employee::where('email', $user->email)->first();
        $isAdmin = $employee ? $employee->is_admin : $user->is_admin;
        
        if ($isAdmin) {
            return true;
        }
        
        // Get report access configuration
        $reportConfig = config('reports.' . $reportRoute, []);
        
        // If no config found, deny access by default
        if (empty($reportConfig)) {
            return false;
        }
        
        // If config has '*', allow all roles
        if (in_array('*', $reportConfig)) {
            return true;
        }
        
        // Get user's role - prioritize Employee role over User role
        $userRole = null;
        if ($employee) {
            // Load role relationship if not loaded
            if (!$employee->relationLoaded('role')) {
                $employee->load('role');
            }
            if ($employee->role) {
                $userRole = $employee->role->title;
            }
        }
        
        // Fallback to User role if Employee role not found
        if (!$userRole && $user->role_id) {
            // Load role relationship if not loaded
            if (!$user->relationLoaded('role')) {
                $user->load('role');
            }
            if ($user->role) {
                $userRole = $user->role->title;
            }
        }
        
        // If user has no role, deny access
        if (!$userRole) {
            return false;
        }
        
        // Check if user's role is in the allowed roles list
        // Case-insensitive comparison for flexibility
        $userRoleLower = strtolower(trim($userRole));
        foreach ($reportConfig as $allowedRole) {
            if (strtolower(trim($allowedRole)) === $userRoleLower) {
                return true;
            }
        }
        
        return false;
    }
}

if (!function_exists('getUserRole')) {
    /**
     * Get current user's role title
     * 
     * @return string|null
     */
    function getUserRole()
    {
        $user = auth()->user();
        
        if (!$user) {
            return null;
        }
        
        $employee = \App\Models\Employee::where('email', $user->email)->first();
        
        if ($employee && $employee->role) {
            return $employee->role->title;
        }
        
        if ($user->role) {
            return $user->role->title;
        }
        
        return null;
    }
}

