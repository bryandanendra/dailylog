<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckApprovalPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $employee = \App\Models\Employee::where('email', $user->email)->first();
        $canApprove = $employee ? $employee->can_approve : $user->can_approve;
        
        if (!$canApprove) {
            abort(403, 'Access denied. You do not have approval permissions.');
        }

        return $next($request);
    }
}
