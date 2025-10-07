<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Log;

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
        Gate::define('approve-log', function (User $user, Log $log) {
            if ($user->id === optional($log->employee)->user_id) return false;

            $levels = config('approval.position_levels');
            $approverLevel = $levels[$user->position->title ?? ''] ?? 0;
            $makerLevel = $levels[$log->employee->position->title ?? ''] ?? 0;

            $isSuperior = optional($log->employee)->superior_id && $log->employee->superior?->user_id === $user->id;
            $higherPos = $approverLevel > $makerLevel;
            $sameDiv = $user->division_id && $log->employee->division_id && $user->division_id === $log->employee->division_id;

            return ($isSuperior || $higherPos) && $sameDiv && (bool) $user->can_approve;
        });
    }
}
