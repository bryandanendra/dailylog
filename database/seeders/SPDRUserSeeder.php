<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SPDRUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create SPDR Admin user
        User::create([
            'name' => 'SPDR Admin',
            'username' => 'spdr_admin',
            'email' => 'spdr_admin@dailylog.com',
            'password' => Hash::make('spdr123'),
            'join_date' => now()->toDateString(),
            'is_admin' => true,
            'can_approve' => true,
            'cutoff_exception' => true,
            'is_supervisor' => true,
            'division_id' => 2, // SPDR Team
            'sub_division_id' => 3, // SPDR
            'role_id' => 2, // SPDR Estimating
            'position_id' => 3, // Manager
            'description' => 'SPDR Administrator Account',
            'archive' => false,
        ]);

        // Create SPDR User
        User::create([
            'name' => 'SPDR User',
            'username' => 'spdr_user',
            'email' => 'spdr_user@dailylog.com',
            'password' => Hash::make('spdr123'),
            'join_date' => now()->toDateString(),
            'is_admin' => false,
            'can_approve' => false,
            'cutoff_exception' => false,
            'is_supervisor' => false,
            'division_id' => 2, // SPDR Team
            'sub_division_id' => 3, // SPDR
            'role_id' => 2, // SPDR Estimating
            'position_id' => 5, // Staff
            'description' => 'SPDR Staff Account',
            'archive' => false,
        ]);
    }
}
