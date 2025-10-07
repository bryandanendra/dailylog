<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Log;
use App\Models\Employee;
use App\Models\Category;
use App\Models\Task;
use App\Models\Builder;
use App\Models\Dweling;
use App\Models\Status;

class LogSeeder extends Seeder
{
    public function run(): void
    {
        $csvFile = fopen(base_path('csv-file/Backup log 2025-09-04.csv'), 'r');
        fgetcsv($csvFile); // Skip header
        
        $processed = 0;
        $skipped = 0;
        
        while (($data = fgetcsv($csvFile)) !== false) {
            try {
                // Skip if essential data is missing
                if (empty($data[2]) || empty($data[1])) { // employee name or date
                    $skipped++;
                    continue;
                }
                
                // Find employee by name (only Sonia Megah)
                $employee = Employee::where('name', 'Sonia Megah')->first();
                if (!$employee) {
                    $skipped++;
                    continue;
                }
                
                // Skip if not Sonia Megah's data
                if ($data[2] !== 'Sonia Megah') {
                    $skipped++;
                    continue;
                }
                
                // Find category by title
                $category = Category::where('title', $data[6])->first();
                if (!$category) {
                    $skipped++;
                    continue;
                }
                
                // Find task by title
                $task = Task::where('title', $data[7])->first();
                if (!$task) {
                    $skipped++;
                    continue;
                }
                
                // Find builder by title (allow empty, use default)
                $builder = null;
                if (!empty($data[8])) {
                    $builder = Builder::where('title', $data[8])->first();
                }
                if (!$builder) {
                    $builder = Builder::first(); // Use first builder as default
                }
                
                // Find dweling by title (allow empty, use default)
                $dweling = null;
                if (!empty($data[9])) {
                    $dweling = Dweling::where('title', $data[9])->first();
                }
                if (!$dweling) {
                    $dweling = Dweling::first(); // Use first dweling as default
                }
                
                // Find status by title (allow empty, use default)
                $status = null;
                if (!empty($data[10])) {
                    $status = Status::where('title', $data[10])->first();
                }
                if (!$status) {
                    $status = Status::first(); // Use first status as default
                }
                
                // Parse date
                $date = date('Y-m-d', strtotime($data[1]));
                
                // Filter: Only import logs from 2025-08-01 onwards
                $filterDate = '2025-08-01';
                if ($date < $filterDate) {
                    $skipped++;
                    continue;
                }
                
                // Parse duration (convert to decimal)
                $duration = is_numeric($data[11]) ? (float)$data[11] : 0;
                
                // Parse quantity (allow empty, default to 1)
                $qty = (!empty($data[5]) && is_numeric($data[5])) ? (int)$data[5] : 1;
                
                // Parse work time
                $workTime = !empty($data[13]) ? $data[13] : null;
                
                // Parse approval status
                $approved = $data[15] === '1';
                $approvedDate = !empty($data[16]) ? date('Y-m-d H:i:s', strtotime($data[16])) : null;
                
                Log::create([
                    'id' => $data[0],
                    'date' => $date,
                    'employee_id' => $employee->id,
                    'subject' => $data[3] ?: 'No Subject',
                    'description' => $data[4] ?: 'No Description',
                    'qty' => $qty,
                    'category_id' => $category->id,
                    'task_id' => $task->id,
                    'builder_id' => $builder->id,
                    'dweling_id' => $dweling->id,
                    'status_id' => $status->id,
                    'duration' => $duration,
                    'note' => $data[12] ?: null,
                    'work_time' => $workTime,
                    'temp' => $data[14] === '1',
                    'approved' => $approved,
                    'approved_date' => $approvedDate,
                    'approved_note' => $data[17] ?: null,
                    'approved_emoji' => $data[18] ?: null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $processed++;
                
                // No limit for Sonia Megah's data
                
            } catch (\Exception $e) {
                $skipped++;
                continue;
            }
        }
        
        fclose($csvFile);
        
        echo "Logs imported: $processed, Skipped: $skipped\n";
    }
}