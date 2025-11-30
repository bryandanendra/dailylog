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
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@dailylog.com',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
            'can_approve' => true,
        ]);

        // Uncomment jika ingin membuat user biasa juga
        // User::create([
        //     'name' => 'User Demo',
        //     'email' => 'user@dailylog.com',
        //     'password' => Hash::make('user123'),
        //     'is_admin' => false,
        //     'can_approve' => false,
        // ]);
    }
}
