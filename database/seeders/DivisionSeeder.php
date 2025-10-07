<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Division;
use Illuminate\Support\Facades\DB;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = fopen(base_path('csv-file/Backup division 2025-09-04.csv'), 'r');
        
        // Skip header row
        fgetcsv($csvFile);
        
        while (($data = fgetcsv($csvFile)) !== false) {
            Division::create([
                'id' => $data[0],
                'title' => $data[1],
                'description' => $data[2],
                'created_at' => $data[3],
                'archive' => $data[4] === '1' ? true : false,
            ]);
        }
        
        fclose($csvFile);
    }
}
