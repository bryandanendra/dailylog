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
            // Drop the existing foreign key constraint
            $table->dropForeign(['employee_id']);
            
            // Add new foreign key constraint to employees table
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logs', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['employee_id']);
            
            // Add back the original foreign key constraint to users table
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};