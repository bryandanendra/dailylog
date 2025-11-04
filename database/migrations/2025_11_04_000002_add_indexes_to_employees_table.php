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
        Schema::table('employees', function (Blueprint $table) {
            // Division filtering (used in approval & reports)
            $table->index('division_id', 'idx_employees_division');
            
            // Superior hierarchy (used in approval logic)
            $table->index('superior_id', 'idx_employees_superior');
            
            // Archive filter (exclude archived employees)
            $table->index('archive', 'idx_employees_archive');
            
            // Composite index for approval queries (division + archive)
            $table->index(['division_id', 'archive'], 'idx_employees_div_archive');
            
            // User relationship
            $table->index('user_id', 'idx_employees_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex('idx_employees_division');
            $table->dropIndex('idx_employees_superior');
            $table->dropIndex('idx_employees_archive');
            $table->dropIndex('idx_employees_div_archive');
            $table->dropIndex('idx_employees_user_id');
        });
    }
};
