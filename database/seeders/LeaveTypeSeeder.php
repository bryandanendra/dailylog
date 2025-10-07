<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeaveType;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaveTypes = [
            ['name' => 'Annual Leave', 'description' => 'Description'],
            ['name' => 'AWOL', 'description' => 'Description'],
            ['name' => 'Half-Day', 'description' => 'half-day off given by the company'],
            ['name' => 'Half-Day Off', 'description' => 'half-day leave taken by employee from annual leave balance'],
            ['name' => 'Inactive', 'description' => 'to show on monthly report google sheet'],
            ['name' => 'Joint Leave', 'description' => 'Description'],
            ['name' => 'Maternity Leave', 'description' => 'not reducing annual leave'],
            ['name' => 'No Assignments', 'description' => 'Description'],
            ['name' => 'Public Holiday', 'description' => 'placeholder, from regular holiday'],
            ['name' => 'Public Holiday Replacement', 'description' => 'Description'],
            ['name' => 'Sick Leave', 'description' => 'Description'],
            ['name' => 'SPDR Commercial Assignment', 'description' => 'Description'],
            ['name' => 'Special Leave', 'description' => 'Description'],
            ['name' => 'Unpaid Leave', 'description' => 'Description'],
            ['name' => 'Weekend', 'description' => 'Description'],
        ];

        foreach ($leaveTypes as $type) {
            LeaveType::create($type);
        }
    }
}
