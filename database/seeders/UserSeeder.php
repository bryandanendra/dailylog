<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user (Alberta)
        $user = User::create([
            'name' => 'Alberta',
            'username' => 'alberta@gmail.com',
            'email' => 'alberta@gmail.com',
            'password' => Hash::make('asc123'),
            'join_date' => now(),
            'is_admin' => true,
            'can_approve' => true,
            'cutoff_exception' => true,
            'division_id' => 14, // Administration
            'sub_division_id' => 11, // General Affair
            'role_id' => 4, // Manager
            'position_id' => 1, // Head
        ]);

        // Create corresponding employee record
        \App\Models\Employee::create([
            'name' => $user->name,
            'username' => $user->email,
            'email' => $user->email,
            'password' => $user->password,
            'join_date' => $user->join_date,
            'division_id' => $user->division_id,
            'sub_division_id' => $user->sub_division_id,
            'role_id' => $user->role_id,
            'position_id' => $user->position_id,
            'is_admin' => true,
            'can_approve' => true,
            'cutoff_exception' => true,
            'is_approved' => true,
            'user_id' => $user->id
        ]);
    }
}
