<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubDivision;

class SubDivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = fopen(base_path('csv-file/Backup subdivision 2025-09-04.csv'), 'r');
        
        // Skip header row
        fgetcsv($csvFile);
        
        // Mapping subdivision to division based on employee data
        $subdivisionToDivision = [
            1 => 1, // GMC -> GMC Management
            2 => 2, // SPDR Admin -> SPDR Team
            3 => 2, // SPDR -> SPDR Team
            4 => 1, // AMC -> GMC Management
            5 => 2, // SPDR Trainee -> SPDR Team
            7 => 1, // GMC PM -> GMC Management
            8 => 14, // TMA -> TMA
            9 => 15, // FLAWLESS DIGITAL -> Flawless Digital
        ];
        
        while (($data = fgetcsv($csvFile)) !== false) {
            $subdivisionId = $data[0];
            $divisionId = $subdivisionToDivision[$subdivisionId] ?? 1; // Default to GMC Management
            
            SubDivision::create([
                'id' => $subdivisionId,
                'title' => $data[1],
                'description' => $data[2] ?: null,
                'division_id' => $divisionId,
                'created_at' => $data[3],
                'archive' => false,
            ]);
        }
        
        fclose($csvFile);
    }
}
