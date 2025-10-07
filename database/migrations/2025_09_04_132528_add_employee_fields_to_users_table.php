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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('name');
            $table->date('join_date')->after('email_verified_at');
            $table->boolean('is_admin')->default(false)->after('join_date');
            $table->boolean('can_approve')->default(false)->after('is_admin');
            $table->boolean('cutoff_exception')->default(false)->after('can_approve');
            $table->boolean('is_supervisor')->default(false)->after('cutoff_exception');
            $table->foreignId('division_id')->nullable()->constrained()->onDelete('set null')->after('is_supervisor');
            $table->foreignId('sub_division_id')->nullable()->constrained()->onDelete('set null')->after('division_id');
            $table->foreignId('role_id')->nullable()->constrained()->onDelete('set null')->after('sub_division_id');
            $table->foreignId('position_id')->nullable()->constrained()->onDelete('set null')->after('role_id');
            $table->text('description')->nullable()->after('position_id');
            $table->boolean('archive')->default(false)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['division_id']);
            $table->dropForeign(['sub_division_id']);
            $table->dropForeign(['role_id']);
            $table->dropForeign(['position_id']);
            $table->dropColumn([
                'username', 'join_date', 'is_admin', 'can_approve', 
                'cutoff_exception', 'is_supervisor', 'division_id', 
                'sub_division_id', 'role_id', 'position_id', 
                'description', 'archive'
            ]);
        });
    }
};
