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
        // Categories table
        Schema::table('categories', function (Blueprint $table) {
            $table->index('title', 'idx_categories_title');
            $table->index('archive', 'idx_categories_archive');
        });

        // Tasks table
        Schema::table('tasks', function (Blueprint $table) {
            $table->index('title', 'idx_tasks_title');
            $table->index('archive', 'idx_tasks_archive');
        });

        // Builders table
        Schema::table('builders', function (Blueprint $table) {
            $table->index('title', 'idx_builders_title');
            $table->index('archive', 'idx_builders_archive');
        });

        // Dwelings table
        Schema::table('dwelings', function (Blueprint $table) {
            $table->index('title', 'idx_dwelings_title');
            $table->index('archive', 'idx_dwelings_archive');
        });

        // Status table
        Schema::table('status', function (Blueprint $table) {
            $table->index('title', 'idx_status_title');
            $table->index('archive', 'idx_status_archive');
        });

        // Work Status table
        Schema::table('work_status', function (Blueprint $table) {
            $table->index('title', 'idx_work_status_title');
            $table->index('archive', 'idx_work_status_archive');
        });

        // Divisions table
        Schema::table('divisions', function (Blueprint $table) {
            $table->index('title', 'idx_divisions_title');
            $table->index('archive', 'idx_divisions_archive');
        });

        // Sub Divisions table
        Schema::table('sub_divisions', function (Blueprint $table) {
            $table->index('title', 'idx_sub_divisions_title');
            $table->index('archive', 'idx_sub_divisions_archive');
            $table->index('division_id', 'idx_sub_divisions_division');
        });

        // Roles table
        Schema::table('roles', function (Blueprint $table) {
            $table->index('title', 'idx_roles_title');
            $table->index('archive', 'idx_roles_archive');
        });

        // Positions table
        Schema::table('positions', function (Blueprint $table) {
            $table->index('title', 'idx_positions_title');
            $table->index('archive', 'idx_positions_archive');
        });

        // Holidays table
        Schema::table('holidays', function (Blueprint $table) {
            $table->index('date', 'idx_holidays_date');
        });

        // Offwork table
        Schema::table('offwork', function (Blueprint $table) {
            $table->index('date', 'idx_offwork_date');
            $table->index('employee_id', 'idx_offwork_employee');
            $table->index('status', 'idx_offwork_status');
        });

        // Notifications table
        Schema::table('notifications', function (Blueprint $table) {
            $table->index('employee_id', 'idx_notifications_employee');
            $table->index('read_status', 'idx_notifications_read_status');
            $table->index(['employee_id', 'read_status'], 'idx_notifications_emp_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Categories
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('idx_categories_title');
            $table->dropIndex('idx_categories_archive');
        });

        // Tasks
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex('idx_tasks_title');
            $table->dropIndex('idx_tasks_archive');
        });

        // Builders
        Schema::table('builders', function (Blueprint $table) {
            $table->dropIndex('idx_builders_title');
            $table->dropIndex('idx_builders_archive');
        });

        // Dwelings
        Schema::table('dwelings', function (Blueprint $table) {
            $table->dropIndex('idx_dwelings_title');
            $table->dropIndex('idx_dwelings_archive');
        });

        // Status
        Schema::table('status', function (Blueprint $table) {
            $table->dropIndex('idx_status_title');
            $table->dropIndex('idx_status_archive');
        });

        // Work Status
        Schema::table('work_status', function (Blueprint $table) {
            $table->dropIndex('idx_work_status_title');
            $table->dropIndex('idx_work_status_archive');
        });

        // Divisions
        Schema::table('divisions', function (Blueprint $table) {
            $table->dropIndex('idx_divisions_title');
            $table->dropIndex('idx_divisions_archive');
        });

        // Sub Divisions
        Schema::table('sub_divisions', function (Blueprint $table) {
            $table->dropIndex('idx_sub_divisions_title');
            $table->dropIndex('idx_sub_divisions_archive');
            $table->dropIndex('idx_sub_divisions_division');
        });

        // Roles
        Schema::table('roles', function (Blueprint $table) {
            $table->dropIndex('idx_roles_title');
            $table->dropIndex('idx_roles_archive');
        });

        // Positions
        Schema::table('positions', function (Blueprint $table) {
            $table->dropIndex('idx_positions_title');
            $table->dropIndex('idx_positions_archive');
        });

        // Holidays
        Schema::table('holidays', function (Blueprint $table) {
            $table->dropIndex('idx_holidays_date');
        });

        // Offwork
        Schema::table('offwork', function (Blueprint $table) {
            $table->dropIndex('idx_offwork_date');
            $table->dropIndex('idx_offwork_employee');
            $table->dropIndex('idx_offwork_status');
        });

        // Notifications
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('idx_notifications_employee');
            $table->dropIndex('idx_notifications_read_status');
            $table->dropIndex('idx_notifications_emp_read');
        });
    }
};
