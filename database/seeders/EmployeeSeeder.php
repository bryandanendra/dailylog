<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = fopen(base_path('csv-file/Backup employee 2025-09-04.csv'), 'r');
        
        // Skip header row
        fgetcsv($csvFile);
        
        while (($data = fgetcsv($csvFile)) !== false) {
            // Skip archived employees
            if ($data[12] === '1') continue;
            
            // Skip resigned employees
            if (!empty($data[23])) continue;
            
            User::create([
                'id' => $data[0],
                'name' => $data[2],
                'username' => $data[3],
                'email' => $data[3], // Using username as email
                'password' => Hash::make('password123'), // Default password
                'join_date' => $data[1],
                'is_admin' => $data[14] === '1',
                'can_approve' => $data[20] === '1',
                'cutoff_exception' => false, // Default
                'is_supervisor' => $data[8] === '3', // Manager position
                'division_id' => $data[5],
                'sub_division_id' => $data[6],
                'role_id' => $data[7],
                'position_id' => $data[8],
                'description' => $data[11] ?: null,
                'archive' => $data[12] === '1',
                'created_at' => $data[13],
            ]);
        }
        
        fclose($csvFile);
    }
}
