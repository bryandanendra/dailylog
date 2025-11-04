<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('logs', function (Blueprint $table) {
            // Composite index for most common query (employee + date)
            $table->index(['employee_id', 'date'], 'idx_logs_employee_date');
            
            // Date index for reports and date range queries
            $table->index('date', 'idx_logs_date');
            
            // Approval queries (approved status + employee)
            $table->index(['approved', 'employee_id'], 'idx_logs_approved_employee');
            
            // Created_at for ordering
            $table->index('created_at', 'idx_logs_created_at');
            
            // Note: Foreign key indexes (category_id, task_id, etc.) 
            // are usually auto-created by Laravel, but we add them explicitly
            // if they don't exist for better query performance
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logs', function (Blueprint $table) {
            $table->dropIndex('idx_logs_employee_date');
            $table->dropIndex('idx_logs_date');
            $table->dropIndex('idx_logs_approved_employee');
            $table->dropIndex('idx_logs_created_at');
        });
    }
};
