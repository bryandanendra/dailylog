<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\MonthlyReportController;
use App\Http\Controllers\BIReportController;
use App\Http\Controllers\SPDRReportController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Test route with view
Route::get('/test-view', function () {
    return view('tables.division');
});

// Test route with controller
Route::get('/test-controller', [App\Http\Controllers\DivisionController::class, 'index']);




// Tables feature will be implemented here

// Test division route without auth
Route::get('/test-division', [App\Http\Controllers\DivisionController::class, 'getData']);
Route::get('/test-division-page', [App\Http\Controllers\DivisionController::class, 'index']);

// Table Management Routes (temporary without auth for testing)
Route::get('/table/division', [App\Http\Controllers\DivisionController::class, 'index'])->name('table.division');
Route::get('/table/subdivision', [App\Http\Controllers\SubDivisionController::class, 'index'])->name('table.subdivision');
Route::get('/table/role', [App\Http\Controllers\RoleController::class, 'index'])->name('table.role');
Route::get('/table/position', [App\Http\Controllers\PositionController::class, 'index'])->name('table.position');
Route::get('/table/category', [App\Http\Controllers\CategoryController::class, 'index'])->name('table.category');
Route::get('/table/task', [App\Http\Controllers\TaskController::class, 'index'])->name('table.task');
Route::get('/table/builder', [App\Http\Controllers\BuilderController::class, 'index'])->name('table.builder');
Route::get('/table/dweling', [App\Http\Controllers\DwelingController::class, 'index'])->name('table.dweling');
Route::get('/table/status', [App\Http\Controllers\StatusController::class, 'index'])->name('table.status');
Route::get('/table/wtime', [App\Http\Controllers\WorkStatusController::class, 'index'])->name('table.wtime');

// Removed duplicate routes - using /tables/ routes instead


// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');
    
    // Daily Log Routes
    Route::get('/log', [App\Http\Controllers\LogController::class, 'index'])->name('log.index');
    Route::post('/log', [App\Http\Controllers\LogController::class, 'store'])->name('log.store');
    Route::put('/log/{id}', [App\Http\Controllers\LogController::class, 'update'])->name('log.update');
    Route::delete('/log/{id}', [App\Http\Controllers\LogController::class, 'destroy'])->name('log.destroy');
    Route::get('/log/date/{date}', [App\Http\Controllers\LogController::class, 'getLogsByDate'])->name('log.by-date');
    Route::get('/log/autocomplete', [App\Http\Controllers\LogController::class, 'getAutocompleteData'])->name('log.autocomplete');
    Route::get('/log/checkApproval', [App\Http\Controllers\LogController::class, 'checkApproval'])->name('log.check-approval');
    
    // Change Password Route
    Route::post('/change-password', [ChangePasswordController::class, 'changePassword'])->name('change-password');
    
    // Reports Routes
    Route::prefix('report')->group(function () {
        // Debug route (remove in production or add auth check)
        Route::get('/debug', [App\Http\Controllers\ReportDebugController::class, 'debug'])->name('report.debug');
        
        Route::get('/monthly', [MonthlyReportController::class, 'index'])->name('report.monthly');
        Route::get('/monthly/setdate', [MonthlyReportController::class, 'setDate'])->name('report.monthly.setdate');
        Route::get('/monthly/getcategories', [MonthlyReportController::class, 'getCategories'])->name('report.monthly.getcategories');
        Route::get('/monthly/getData', [MonthlyReportController::class, 'getData'])->name('report.monthly.getdata');
        
        Route::get('/bi', [BIReportController::class, 'index'])->name('report.bi');
        Route::get('/bi/setdate', [BIReportController::class, 'setDate'])->name('report.bi.setdate');
        Route::get('/bi/getcategories', [BIReportController::class, 'getCategories'])->name('report.bi.getcategories');
        Route::match(['GET', 'POST'], '/bi/getfilterdata', [BIReportController::class, 'getFilterData'])->name('report.bi.getfilterdata');
        Route::get('/bi/getdata', [BIReportController::class, 'getData'])->name('report.bi.getdata');
        
        // Test route for debugging
        Route::get('/bi/test', function() {
            return response()->json(['message' => 'BI Report API is working']);
        });
        
        Route::get('/regular', [App\Http\Controllers\SPDRReportController::class, 'index'])->name('report.spdr');
        Route::get('/regular/getData', [App\Http\Controllers\SPDRReportController::class, 'getData'])->name('report.spdr.getdata');
        Route::get('/regular/getHoliday', [App\Http\Controllers\SPDRReportController::class, 'getHoliday'])->name('report.spdr.getholiday');
        Route::get('/regular/print', [App\Http\Controllers\SPDRReportController::class, 'print'])->name('report.spdr.print');
        
        Route::get('/category', [App\Http\Controllers\CategoryReportController::class, 'index'])->name('report.category');
        Route::get('/category/getData', [App\Http\Controllers\CategoryReportController::class, 'getData'])->name('report.category.getdata');
        Route::get('/category/print', [App\Http\Controllers\CategoryReportController::class, 'print'])->name('report.category.print');
        
        Route::get('/tma', [App\Http\Controllers\TMAReportController::class, 'index'])->name('report.tma');
        Route::get('/tma/getData', [App\Http\Controllers\TMAReportController::class, 'getData'])->name('report.tma.getdata');
        Route::get('/tma/getHoliday', [App\Http\Controllers\TMAReportController::class, 'getHoliday'])->name('report.tma.getholiday');
        Route::get('/tma/print', [App\Http\Controllers\TMAReportController::class, 'print'])->name('report.tma.print');
        
        // Division Report (Auto-detect user's division)
        Route::get('/division', [App\Http\Controllers\DivisionReportController::class, 'index'])->name('report.division');
        Route::get('/division/getData', [App\Http\Controllers\DivisionReportController::class, 'getData'])->name('report.division.getdata');
        Route::get('/division/getHoliday', [App\Http\Controllers\DivisionReportController::class, 'getHoliday'])->name('report.division.getholiday');
        Route::get('/division/print', [App\Http\Controllers\DivisionReportController::class, 'print'])->name('report.division.print');
        
    });
    
    // Approval Routes
    Route::get('/approved', function () {
        $user = Auth::user();
        $employee = \App\Models\Employee::where('email', $user->email)->first();
        $canApprove = $employee ? $employee->can_approve : $user->can_approve;
        
        if (!$canApprove) {
            abort(403, 'Access denied. You do not have approval permissions.');
        }
        return view('approval.index');
    })->name('approval.index');
    Route::prefix('approved')->middleware('check.approval')->group(function () {
        Route::get('/getHoliday', [App\Http\Controllers\ApprovalController::class, 'getHoliday']);
        Route::get('/getleave', [App\Http\Controllers\ApprovalController::class, 'getleave']);
        Route::get('/getData', [App\Http\Controllers\ApprovalController::class, 'getData']);
        Route::get('/check', [App\Http\Controllers\ApprovalController::class, 'check']);
        Route::get('/update', [App\Http\Controllers\ApprovalController::class, 'update']);
        Route::get('/submit', [App\Http\Controllers\ApprovalController::class, 'submit']);
        Route::get('/getUnapprovedLogs', [App\Http\Controllers\ApprovalController::class, 'getUnapprovedLogs']);
        Route::get('/leavesave', [App\Http\Controllers\ApprovalController::class, 'leavesave']);
        Route::get('/select', [App\Http\Controllers\ApprovalController::class, 'select']);
        Route::get('/debug', [App\Http\Controllers\ApprovalController::class, 'debug']);
    });
    
    // Table Management Routes
    // Division (using bypass routes)
    Route::get('/division', [App\Http\Controllers\DivisionController::class, 'index'])->name('division.index');
    Route::get('/division/getData', [App\Http\Controllers\DivisionController::class, 'getData'])->name('division.getdata');
    Route::post('/division', [App\Http\Controllers\DivisionController::class, 'store'])->name('division.store');
    Route::put('/division/{id}', [App\Http\Controllers\DivisionController::class, 'update'])->name('division.update');
    Route::delete('/division/{id}', [App\Http\Controllers\DivisionController::class, 'destroy'])->name('division.destroy');
    Route::get('/division/remove', [App\Http\Controllers\DivisionController::class, 'destroy'])->name('division.remove');

    // Sub Division (using bypass routes)
    Route::get('/subdivision', [App\Http\Controllers\SubDivisionController::class, 'index'])->name('subdivision.index');
    Route::get('/subdivision/getData', [App\Http\Controllers\SubDivisionController::class, 'getData'])->name('subdivision.getdata');
    Route::post('/subdivision', [App\Http\Controllers\SubDivisionController::class, 'store'])->name('subdivision.store');
    Route::put('/subdivision/{id}', [App\Http\Controllers\SubDivisionController::class, 'update'])->name('subdivision.update');
    Route::delete('/subdivision/{id}', [App\Http\Controllers\SubDivisionController::class, 'destroy'])->name('subdivision.destroy');
    Route::get('/subdivision/remove', [App\Http\Controllers\SubDivisionController::class, 'destroy'])->name('subdivision.remove');

    // Role
    Route::get('/role', [App\Http\Controllers\RoleController::class, 'index'])->name('role.index');
    Route::get('/role/getData', [App\Http\Controllers\RoleController::class, 'getData'])->name('role.getdata');
    Route::post('/role', [App\Http\Controllers\RoleController::class, 'store'])->name('role.store');
    Route::put('/role/{id}', [App\Http\Controllers\RoleController::class, 'update'])->name('role.update');
    Route::delete('/role/{id}', [App\Http\Controllers\RoleController::class, 'destroy'])->name('role.destroy');
    Route::get('/role/remove', [App\Http\Controllers\RoleController::class, 'destroy'])->name('role.remove');

    // Position
    Route::get('/position', [App\Http\Controllers\PositionController::class, 'index'])->name('position.index');
    Route::get('/position/getData', [App\Http\Controllers\PositionController::class, 'getData'])->name('position.getdata');
    Route::post('/position', [App\Http\Controllers\PositionController::class, 'store'])->name('position.store');
    Route::put('/position/{id}', [App\Http\Controllers\PositionController::class, 'update'])->name('position.update');
    Route::delete('/position/{id}', [App\Http\Controllers\PositionController::class, 'destroy'])->name('position.destroy');
    Route::get('/position/remove', [App\Http\Controllers\PositionController::class, 'destroy'])->name('position.remove');

    // Category
    Route::get('/category', [App\Http\Controllers\CategoryController::class, 'index'])->name('category.index');
    Route::get('/category/getData', [App\Http\Controllers\CategoryController::class, 'getData'])->name('category.getdata');
    Route::post('/category', [App\Http\Controllers\CategoryController::class, 'store'])->name('category.store');
    Route::put('/category/{id}', [App\Http\Controllers\CategoryController::class, 'update'])->name('category.update');
    Route::delete('/category/{id}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('category.destroy');
    Route::get('/category/remove', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('category.remove');

    // Task
    Route::get('/task', [App\Http\Controllers\TaskController::class, 'index'])->name('task.index');
    Route::get('/task/getData', [App\Http\Controllers\TaskController::class, 'getData'])->name('task.getdata');
    Route::post('/task', [App\Http\Controllers\TaskController::class, 'store'])->name('task.store');
    Route::put('/task/{id}', [App\Http\Controllers\TaskController::class, 'update'])->name('task.update');
    Route::delete('/task/{id}', [App\Http\Controllers\TaskController::class, 'destroy'])->name('task.destroy');
    Route::get('/task/remove', [App\Http\Controllers\TaskController::class, 'destroy'])->name('task.remove');

    // Builder
    Route::get('/builder', [App\Http\Controllers\BuilderController::class, 'index'])->name('builder.index');
    Route::get('/builder/getData', [App\Http\Controllers\BuilderController::class, 'getData'])->name('builder.getdata');
    Route::post('/builder', [App\Http\Controllers\BuilderController::class, 'store'])->name('builder.store');
    Route::put('/builder/{id}', [App\Http\Controllers\BuilderController::class, 'update'])->name('builder.update');
    Route::delete('/builder/{id}', [App\Http\Controllers\BuilderController::class, 'destroy'])->name('builder.destroy');
    Route::get('/builder/remove', [App\Http\Controllers\BuilderController::class, 'destroy'])->name('builder.remove');

    // Dweling
    Route::get('/dweling', [App\Http\Controllers\DwelingController::class, 'index'])->name('dweling.index');
    Route::get('/dweling/getData', [App\Http\Controllers\DwelingController::class, 'getData'])->name('dweling.getdata');
    Route::post('/dweling', [App\Http\Controllers\DwelingController::class, 'store'])->name('dweling.store');
    Route::put('/dweling/{id}', [App\Http\Controllers\DwelingController::class, 'update'])->name('dweling.update');
    Route::delete('/dweling/{id}', [App\Http\Controllers\DwelingController::class, 'destroy'])->name('dweling.destroy');
    Route::get('/dweling/remove', [App\Http\Controllers\DwelingController::class, 'destroy'])->name('dweling.remove');

    // Status
    Route::get('/status', [App\Http\Controllers\StatusController::class, 'index'])->name('status.index');
    Route::get('/status/getData', [App\Http\Controllers\StatusController::class, 'getData'])->name('status.getdata');
    Route::post('/status', [App\Http\Controllers\StatusController::class, 'store'])->name('status.store');
    Route::put('/status/{id}', [App\Http\Controllers\StatusController::class, 'update'])->name('status.update');
    Route::delete('/status/{id}', [App\Http\Controllers\StatusController::class, 'destroy'])->name('status.destroy');
    Route::get('/status/remove', [App\Http\Controllers\StatusController::class, 'destroy'])->name('status.remove');

    // Work Status
    Route::get('/wtime', [App\Http\Controllers\WorkStatusController::class, 'index'])->name('wtime.index');
    Route::get('/wtime/getData', [App\Http\Controllers\WorkStatusController::class, 'getData'])->name('wtime.getdata');
    Route::post('/wtime', [App\Http\Controllers\WorkStatusController::class, 'store'])->name('wtime.store');
    Route::put('/wtime/{id}', [App\Http\Controllers\WorkStatusController::class, 'update'])->name('wtime.update');
    Route::delete('/wtime/{id}', [App\Http\Controllers\WorkStatusController::class, 'destroy'])->name('wtime.destroy');
    Route::get('/wtime/remove', [App\Http\Controllers\WorkStatusController::class, 'destroy'])->name('wtime.remove');
    
    // Tables Routes (uses same controllers as main routes)
    Route::prefix('tables')->group(function () {
        Route::get('/division', [App\Http\Controllers\DivisionController::class, 'index']);
        Route::get('/subdivision', [App\Http\Controllers\SubDivisionController::class, 'index']);
        Route::get('/role', [App\Http\Controllers\RoleController::class, 'index']);
        Route::get('/position', [App\Http\Controllers\PositionController::class, 'index']);
        Route::get('/category', [App\Http\Controllers\CategoryController::class, 'index']);
        Route::get('/task', [App\Http\Controllers\TaskController::class, 'index']);
        Route::get('/builder', [App\Http\Controllers\BuilderController::class, 'index']);
        Route::get('/dweling', [App\Http\Controllers\DwelingController::class, 'index']);
        Route::get('/status', [App\Http\Controllers\StatusController::class, 'index']);
        Route::get('/wtime', [App\Http\Controllers\WorkStatusController::class, 'index']);
    });
    
    // Off Work Routes
    Route::prefix('offwork')->group(function () {
        Route::get('/', [App\Http\Controllers\OffworkController::class, 'index'])->name('offwork.index');
        Route::get('/getData', [App\Http\Controllers\OffworkController::class, 'getData'])->name('offwork.getdata');
        Route::get('/getEmployees', [App\Http\Controllers\OffworkController::class, 'getEmployees'])->name('offwork.getemployees');
        Route::get('/getLeaveTypes', [App\Http\Controllers\OffworkController::class, 'getLeaveTypes'])->name('offwork.getleavetypes');
        Route::post('/', [App\Http\Controllers\OffworkController::class, 'store'])->name('offwork.store');
        Route::put('/{id}', [App\Http\Controllers\OffworkController::class, 'update'])->name('offwork.update');
        Route::delete('/{id}', [App\Http\Controllers\OffworkController::class, 'destroy'])->name('offwork.destroy');
        
        // Leave Types Master Data Routes
        Route::get('/leave', [App\Http\Controllers\LeaveController::class, 'index'])->name('offwork.leave');
        Route::get('/leave/getData', [App\Http\Controllers\LeaveController::class, 'getData'])->name('leave.getdata');
        Route::post('/leave', [App\Http\Controllers\LeaveController::class, 'store'])->name('leave.store');
        Route::put('/leave/{id}', [App\Http\Controllers\LeaveController::class, 'update'])->name('leave.update');
        Route::delete('/leave/{id}', [App\Http\Controllers\LeaveController::class, 'destroy'])->name('leave.destroy');
        
        Route::get('/holiday', [App\Http\Controllers\HolidayController::class, 'index'])->name('offwork.holiday');
        Route::get('/holiday/getData', [App\Http\Controllers\HolidayController::class, 'getData'])->name('offwork.holiday.getdata');
        Route::post('/holiday', [App\Http\Controllers\HolidayController::class, 'store'])->name('offwork.holiday.store');
        Route::put('/holiday/{id}', [App\Http\Controllers\HolidayController::class, 'update'])->name('offwork.holiday.update');
        Route::delete('/holiday/{id}', [App\Http\Controllers\HolidayController::class, 'destroy'])->name('offwork.holiday.destroy');
    });
    
    // Employee Routes
    Route::get('/employee', [App\Http\Controllers\EmployeeController::class, 'index'])->name('employee.index');
    Route::get('/employee/getData', [App\Http\Controllers\EmployeeController::class, 'getData'])->name('employee.getdata');
    Route::get('/employee/getCombo', [App\Http\Controllers\EmployeeController::class, 'getCombo'])->name('employee.getcombo');
    Route::get('/employee/remove', [App\Http\Controllers\EmployeeController::class, 'destroy'])->name('employee.remove'); // Moved up
    Route::get('/employee/resetpass', [App\Http\Controllers\EmployeeController::class, 'resetPassword'])->name('employee.resetpass'); // Moved up
    Route::get('/employee/{id}', [App\Http\Controllers\EmployeeController::class, 'show'])->name('employee.show');
    Route::post('/employee', [App\Http\Controllers\EmployeeController::class, 'store'])->name('employee.store');
    Route::put('/employee/{id}', [App\Http\Controllers\EmployeeController::class, 'update'])->name('employee.update');
    
    // Employee Registration Approval Routes
    Route::post('/employee/approve', [App\Http\Controllers\EmployeeController::class, 'approveRegistration'])->name('employee.approve');
    Route::post('/employee/reject', [App\Http\Controllers\EmployeeController::class, 'rejectRegistration'])->name('employee.reject');
    Route::post('/employee/bulk-approve', [App\Http\Controllers\EmployeeController::class, 'bulkApprove'])->name('employee.bulk-approve');
    Route::post('/employee/bulk-reject', [App\Http\Controllers\EmployeeController::class, 'bulkReject'])->name('employee.bulk-reject');
    
    // Backup Routes
    Route::get('/backup', [App\Http\Controllers\BackupController::class, 'index'])->name('backup.index');
    Route::get('/backup/divisions', [App\Http\Controllers\BackupController::class, 'exportDivisions'])->name('backup.divisions');
    Route::get('/backup/subdivisions', [App\Http\Controllers\BackupController::class, 'exportSubDivisions'])->name('backup.subdivisions');
    Route::get('/backup/roles', [App\Http\Controllers\BackupController::class, 'exportRoles'])->name('backup.roles');
    Route::get('/backup/positions', [App\Http\Controllers\BackupController::class, 'exportPositions'])->name('backup.positions');
    Route::get('/backup/categories', [App\Http\Controllers\BackupController::class, 'exportCategories'])->name('backup.categories');
    Route::get('/backup/tasks', [App\Http\Controllers\BackupController::class, 'exportTasks'])->name('backup.tasks');
    Route::get('/backup/builders', [App\Http\Controllers\BackupController::class, 'exportBuilders'])->name('backup.builders');
    Route::get('/backup/dwelings', [App\Http\Controllers\BackupController::class, 'exportDwelings'])->name('backup.dwelings');
    Route::get('/backup/status', [App\Http\Controllers\BackupController::class, 'exportStatus'])->name('backup.status');
    Route::get('/backup/workstatus', [App\Http\Controllers\BackupController::class, 'exportWorkStatus'])->name('backup.workstatus');
    Route::get('/backup/employees', [App\Http\Controllers\BackupController::class, 'exportEmployees'])->name('backup.employees');
    Route::get('/backup/logs', [App\Http\Controllers\BackupController::class, 'exportLogs'])->name('backup.logs');
    Route::get('/backup/offwork', [App\Http\Controllers\BackupController::class, 'exportOffwork'])->name('backup.offwork');
    Route::get('/backup/holidays', [App\Http\Controllers\BackupController::class, 'exportHolidays'])->name('backup.holidays');
    Route::get('/backup/leavetypes', [App\Http\Controllers\BackupController::class, 'exportLeaveTypes'])->name('backup.leavetypes');
    Route::get('/backup/users', [App\Http\Controllers\BackupController::class, 'exportUsers'])->name('backup.users');
    Route::get('/backup/notifications', [App\Http\Controllers\BackupController::class, 'exportNotifications'])->name('backup.notifications');
    Route::get('/backup/timecutoff', [App\Http\Controllers\BackupController::class, 'exportTimeCutoff'])->name('backup.timecutoff');
    Route::get('/backup/all', [App\Http\Controllers\BackupController::class, 'exportAll'])->name('backup.all');
    
    // Time Cut Off Routes
    Route::get('/cutoff', [App\Http\Controllers\TimeCutoffController::class, 'index'])->name('cutoff.index');
    Route::get('/cutoff/current', [App\Http\Controllers\TimeCutoffController::class, 'getCurrent'])->name('cutoff.current');
    Route::post('/cutoff/update', [App\Http\Controllers\TimeCutoffController::class, 'update'])->name('cutoff.update');
    
    // Notification Routes
    Route::get('/notifications/count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.count');
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'getNotifications'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.readall');
    
    // User Guide Routes (disabled)
    // Route::get('/manual', function () {
    //     return view('manual.index');
    // })->name('manual.index');
    
    // Master Data Tables Routes
    Route::prefix('table')->group(function () {
        Route::get('/division', function () {
            return view('tables.division');
        })->name('table.division');
        
        Route::get('/subdivision', function () {
            return view('tables.subdivision');
        })->name('table.subdivision');
        
        Route::get('/role', function () {
            return view('tables.role');
        })->name('table.role');
        
        Route::get('/position', function () {
            return view('tables.position');
        })->name('table.position');
        
        Route::get('/category', function () {
            return view('tables.category');
        })->name('table.category');
        
        Route::get('/task', function () {
            return view('tables.task');
        })->name('table.task');
        
        Route::get('/builder', function () {
            return view('tables.builder');
        })->name('table.builder');
        
        Route::get('/dweling', function () {
            return view('tables.dweling');
        })->name('table.dweling');
        
        Route::get('/status', function () {
            return view('tables.status');
        })->name('table.status');
        
        Route::get('/wtime', function () {
            return view('tables.workstatus');
        })->name('table.workstatus');
    });
});
