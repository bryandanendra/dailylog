<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Log;
use App\Models\Employee;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load report helper functions
        if (file_exists(app_path('Helpers/ReportHelper.php'))) {
            require_once app_path('Helpers/ReportHelper.php');
        }
        
        Gate::define('approve-log', function (User $user, Log $log) {
            if ($user->id === optional($log->employee)->user_id) return false;

            // Get can_approve and is_admin from Employee if exists, otherwise from User
            $employee = Employee::where('email', $user->email)->first();
            $canApprove = $employee ? $employee->can_approve : $user->can_approve;
            $isAdmin = $employee ? $employee->is_admin : $user->is_admin;

            if (!$canApprove && !$isAdmin) {
                return false;
            }

            // Check division match
            $sameDiv = $user->division_id && $log->employee->division_id && $user->division_id === $log->employee->division_id;
            if (!$sameDiv) {
                return false;
            }

            // Only approve if user is in the superior hierarchy
            // Even admin must follow the hierarchy structure
            if (!$log->employee->superior_id) {
                return false; // No supervisor means no one can approve
            }

            // Check if current user is in the superior chain
            return self::isInSuperiorHierarchy($log->employee, $user->id);
        });
    }

    /**
     * Check if current user is in the superior hierarchy of an employee
     * This traverses the superior chain to find if current user is a supervisor
     */
    private static function isInSuperiorHierarchy(Employee $employee, $currentUserId, $maxDepth = 10)
    {
        $current = $employee;
        $depth = 0;
        
        while ($current && $current->superior_id && $depth < $maxDepth) {
            if (!$current->relationLoaded('superior')) {
                $current->load('superior');
            }
            
            if ($current->superior && $current->superior->user_id === $currentUserId) {
                return true;
            }
            
            $current = $current->superior;
            $depth++;
        }
        
        return false;
    }
}
