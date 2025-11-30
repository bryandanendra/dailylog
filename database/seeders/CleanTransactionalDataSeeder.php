<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Employee;

class CleanTransactionalDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder will clean all transactional data but keep master data:
     * - Keep: divisions, sub_divisions, roles, positions, categories, statuses
     * - Keep: Only admin employee and admin user
     * - Delete: All other employees, users, logs, leaves, approvals, etc.
     */
    public function run(): void
    {
        try {
            // Get admin user ID
            $adminUser = User::where('email', 'like', '%admin%')
                            ->orWhere('is_admin', true)
                            ->orWhere('id', 1)
                            ->first();
            
            if (!$adminUser) {
                $this->command->error('Admin user not found!');
                return;
            }
            
            $adminUserId = $adminUser->id;
            
            // Get admin employee (if exists)
            $adminEmployee = Employee::where('user_id', $adminUserId)->first();
            $adminEmployeeId = $adminEmployee ? $adminEmployee->id : null;
            
            $this->command->info("Admin User ID: {$adminUserId}");
            if ($adminEmployeeId) {
                $this->command->info("Admin Employee ID: {$adminEmployeeId}");
            } else {
                $this->command->warn("No employee record found for admin user");
            }
            
            // ========== DELETE TRANSACTIONAL DATA ==========
            
            // 1. Delete all logs (daily logs and work logs)
            $this->command->info('Deleting logs...');
            $deleted = DB::table('logs')->delete();
            $this->command->info("  → Deleted {$deleted} logs");
            
            // 2. Delete all leave/offwork data
            $this->command->info('Deleting leaves/offwork...');
            $deleted = DB::table('offwork')->delete();
            $this->command->info("  → Deleted {$deleted} offwork records");
            
            // 3. Delete all approvals (if table exists)
            if (DB::getSchemaBuilder()->hasTable('approvals')) {
                $this->command->info('Deleting approvals...');
                $deleted = DB::table('approvals')->delete();
                $this->command->info("  → Deleted {$deleted} approvals");
            }
            
            // 4. Delete all notifications except admin's
            $this->command->info('Deleting notifications...');
            if ($adminEmployeeId) {
                $deleted = DB::table('notifications')
                    ->where('employee_id', '!=', $adminEmployeeId)
                    ->delete();
            } else {
                $deleted = DB::table('notifications')->delete();
            }
            $this->command->info("  → Deleted {$deleted} notifications");
            
            // 5. Delete all tasks
            $this->command->info('Deleting tasks...');
            $deleted = DB::table('tasks')->delete();
            $this->command->info("  → Deleted {$deleted} tasks");
            
            // 6. Delete all builders
            $this->command->info('Deleting builders...');
            $deleted = DB::table('builders')->delete();
            $this->command->info("  → Deleted {$deleted} builders");
            
            // 7. Delete all dwelings
            $this->command->info('Deleting dwelings...');
            $deleted = DB::table('dwelings')->delete();
            $this->command->info("  → Deleted {$deleted} dwelings");
            
            // 8. Delete all holidays
            $this->command->info('Deleting holidays...');
            $deleted = DB::table('holidays')->delete();
            $this->command->info("  → Deleted {$deleted} holidays");
            
            // 9. Delete all time_cutoff
            $this->command->info('Deleting time cutoffs...');
            $deleted = DB::table('time_cutoff')->delete();
            $this->command->info("  → Deleted {$deleted} time cutoffs");
            
            // 10. Delete all work_status
            $this->command->info('Deleting work statuses...');
            $deleted = DB::table('work_status')->delete();
            $this->command->info("  → Deleted {$deleted} work statuses");
            
            // 11. Delete all leave_types (if you want to keep, comment this out)
            // $this->command->info('Deleting leave types...');
            // $deleted = DB::table('leave_types')->delete();
            // $this->command->info("  → Deleted {$deleted} leave types");
            
            // 12. Delete all employees except admin
            $this->command->info('Deleting employees (except admin)...');
            if ($adminEmployeeId) {
                $deleted = DB::table('employees')
                    ->where('id', '!=', $adminEmployeeId)
                    ->delete();
            } else {
                $deleted = DB::table('employees')->delete();
            }
            $this->command->info("  → Deleted {$deleted} employees");
            
            // 13. Delete all users except admin
            $this->command->info('Deleting users (except admin)...');
            $deleted = DB::table('users')
                ->where('id', '!=', $adminUserId)
                ->delete();
            $this->command->info("  → Deleted {$deleted} users");
            
            // ========== RESET AUTO INCREMENTS ==========
            $this->command->info('Resetting auto increments...');
            
            DB::statement("ALTER TABLE logs AUTO_INCREMENT = 1");
            DB::statement("ALTER TABLE offwork AUTO_INCREMENT = 1");
            DB::statement("ALTER TABLE notifications AUTO_INCREMENT = 1");
            DB::statement("ALTER TABLE tasks AUTO_INCREMENT = 1");
            DB::statement("ALTER TABLE builders AUTO_INCREMENT = 1");
            DB::statement("ALTER TABLE dwelings AUTO_INCREMENT = 1");
            DB::statement("ALTER TABLE holidays AUTO_INCREMENT = 1");
            DB::statement("ALTER TABLE time_cutoff AUTO_INCREMENT = 1");
            DB::statement("ALTER TABLE work_status AUTO_INCREMENT = 1");
            
            // Reset users and employees based on admin ID
            DB::statement("ALTER TABLE users AUTO_INCREMENT = " . ($adminUserId + 1));
            
            if ($adminEmployeeId) {
                DB::statement("ALTER TABLE employees AUTO_INCREMENT = " . ($adminEmployeeId + 1));
            } else {
                DB::statement("ALTER TABLE employees AUTO_INCREMENT = 1");
            }
            
            if (DB::getSchemaBuilder()->hasTable('approvals')) {
                DB::statement("ALTER TABLE approvals AUTO_INCREMENT = 1");
            }
            
            $this->command->info('');
            $this->command->info('✓ Successfully cleaned transactional data!');
            $this->command->info('✓ Master data preserved:');
            $this->command->info('  - Divisions: ' . DB::table('divisions')->count());
            $this->command->info('  - Sub Divisions: ' . DB::table('sub_divisions')->count());
            $this->command->info('  - Roles: ' . DB::table('roles')->count());
            $this->command->info('  - Positions: ' . DB::table('positions')->count());
            $this->command->info('  - Categories: ' . DB::table('categories')->count());
            $this->command->info('  - Leave Types: ' . DB::table('leave_types')->count());
            $this->command->info('✓ Admin user and employee preserved');
            
        } catch (\Exception $e) {
            $this->command->error('Error: ' . $e->getMessage());
            $this->command->error($e->getTraceAsString());
            throw $e;
        }
    }
}
