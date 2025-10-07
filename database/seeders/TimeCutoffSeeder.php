<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TimeCutoff;

class TimeCutoffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TimeCutoff::create([
            'time' => '23:00:00',
            'day_offset' => 0,
            'active' => true,
        ]);
    }
}
