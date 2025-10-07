<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dweling;

class DwelingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = fopen(base_path('csv-file/Backup dweling 2025-09-04.csv'), 'r');
        
        // Skip header row
        fgetcsv($csvFile);
        
        while (($data = fgetcsv($csvFile)) !== false) {
            Dweling::create([
                'id' => $data[0],
                'title' => $data[1],
                'description' => $data[2] ?: null,
                'created_at' => $data[3],
                'archive' => false,
            ]);
        }
        
        fclose($csvFile);
    }
}
