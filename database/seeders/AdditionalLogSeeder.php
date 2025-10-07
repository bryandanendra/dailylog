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

class AdditionalLogSeeder extends Seeder
{
    public function run(): void
    {
        // Get employee ID 9 (Sonia Megah)
        $employee = Employee::find(9);
        if (!$employee) {
            echo "Employee ID 9 not found!\n";
            return;
        }

        // Get existing dates from database
        $existingDates = Log::select('date')->distinct()->orderBy('date')->get();
        if ($existingDates->isEmpty()) {
            echo "No existing dates found!\n";
            return;
        }

        // Use the first available date
        $targetDate = $existingDates->first()->date;

        // Get master data
        $category = Category::where('title', 'SPDR')->first() ?? Category::first();
        $task = Task::where('title', 'Team Monitoring')->first() ?? Task::first();
        $builder = Builder::where('title', 'Creation Homes VIC Pty Ltd')->first() ?? Builder::first();
        $dweling = Dweling::where('title', 'Multi dwelling 2 units')->first() ?? Dweling::first();
        $status = Status::where('title', 'Quote Sent')->first() ?? Status::first();

        // Sample log data based on CSV
        $logData = [
            [
                'subject' => 'Check schedule acuity',
                'description' => 'For all estimator',
                'qty' => 1,
                'category' => $category,
                'task' => $task,
                'builder' => $builder,
                'dweling' => $dweling,
                'status' => $status,
                'duration' => 12,
                'note' => 'Daily schedule review',
                'temp' => false,
                'approved' => true,
            ],
            [
                'subject' => 'Check email admin4 and estimate',
                'description' => 'Response client email',
                'qty' => 1,
                'category' => $category,
                'task' => Task::where('title', 'Misc.')->first() ?? $task,
                'builder' => $builder,
                'dweling' => $dweling,
                'status' => $status,
                'duration' => 42,
                'note' => 'Client communication',
                'temp' => false,
                'approved' => true,
            ],
            [
                'subject' => '16405 - Lot 60 No 1 Baritone Road, Strathtulloh',
                'description' => 'Review - sent quote',
                'qty' => 2,
                'category' => $category,
                'task' => $task,
                'builder' => Builder::where('title', 'Creation Homes VIC Pty Ltd')->first() ?? $builder,
                'dweling' => Dweling::where('title', 'Multi dwelling 2 units')->first() ?? $dweling,
                'status' => Status::where('title', 'Quote Sent')->first() ?? $status,
                'duration' => 18,
                'note' => 'Quote review and submission',
                'temp' => false,
                'approved' => true,
            ],
            [
                'subject' => 'Coordinated w/ Admin',
                'description' => 'Client\'s email - RFQ - Daily log',
                'qty' => 1,
                'category' => $category,
                'task' => $task,
                'builder' => $builder,
                'dweling' => $dweling,
                'status' => $status,
                'duration' => 34,
                'note' => 'Team coordination',
                'temp' => false,
                'approved' => true,
            ],
            [
                'subject' => 'Coordinated w/ Team',
                'description' => 'Progress Staff OJT (admin)',
                'qty' => 1,
                'category' => $category,
                'task' => $task,
                'builder' => $builder,
                'dweling' => $dweling,
                'status' => $status,
                'duration' => 18,
                'note' => 'Staff training progress',
                'temp' => false,
                'approved' => true,
            ]
        ];

        $created = 0;
        foreach ($logData as $data) {
            try {
                Log::create([
                    'date' => $targetDate,
                    'employee_id' => $employee->id,
                    'subject' => $data['subject'],
                    'description' => $data['description'],
                    'qty' => $data['qty'],
                    'category_id' => $data['category']->id,
                    'task_id' => $data['task']->id,
                    'builder_id' => $data['builder']->id,
                    'dweling_id' => $data['dweling']->id,
                    'status_id' => $data['status']->id,
                    'duration' => $data['duration'],
                    'note' => $data['note'],
                    'temp' => $data['temp'],
                    'approved' => $data['approved'],
                    'approved_date' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $created++;
            } catch (\Exception $e) {
                echo "Error creating log: " . $e->getMessage() . "\n";
            }
        }

        echo "Successfully created {$created} additional logs for {$employee->name} on {$targetDate}\n";
    }
}