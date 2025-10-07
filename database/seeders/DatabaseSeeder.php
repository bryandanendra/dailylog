<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DivisionSeeder::class,
            SubDivisionSeeder::class,
            RoleSeeder::class,
            PositionSeeder::class,
            CategorySeeder::class,
            TaskSeeder::class,
            BuilderSeeder::class,
            DwelingSeeder::class,
            StatusSeeder::class,
            WorkStatusSeeder::class,
            TimeCutoffSeeder::class,
            EmployeeSeeder::class,
            LogSeeder::class,
        ]);
    }
}
