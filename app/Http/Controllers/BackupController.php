<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Models\Division;
use App\Models\SubDivision;
use App\Models\Role;
use App\Models\Position;
use App\Models\Category;
use App\Models\Task;
use App\Models\Builder;
use App\Models\Dweling;
use App\Models\Status;
use App\Models\WorkStatus;
use App\Models\Employee;
use App\Models\Log;
use App\Models\Offwork;
use App\Models\Holiday;
use App\Models\LeaveType;
use App\Models\User;
use App\Models\Notification;
use App\Models\TimeCutoff;

class BackupController extends Controller
{
    public function index()
    {
        return view('backup.index');
    }

    private function generateCSV($data, $filename)
    {
        try {
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ];

            $callback = function() use ($data) {
                $file = fopen('php://output', 'w');
                
                // Add BOM for UTF-8
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                if (!empty($data)) {
                    // Write header
                    fputcsv($file, array_keys($data[0]));
                    
                    // Write data
                    foreach ($data as $row) {
                        fputcsv($file, $row);
                    }
                } else {
                    // Write empty message if no data
                    fputcsv($file, ['No data available']);
                }
                
                fclose($file);
            };

            return Response::stream($callback, 200, $headers);
        } catch (\Exception $e) {
            \Log::error('CSV Export Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to export data: ' . $e->getMessage());
        }
    }

    public function exportDivisions()
    {
        try {
            $data = Division::all()->toArray();
            return $this->generateCSV($data, 'divisions_' . date('Y-m-d_His') . '.csv');
        } catch (\Exception $e) {
            \Log::error('Export Divisions Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to export divisions: ' . $e->getMessage());
        }
    }

    public function exportSubDivisions()
    {
        $data = SubDivision::with('division')->get()->map(function($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'description' => $item->description,
                'division_id' => $item->division_id,
                'division_name' => $item->division ? $item->division->title : '',
                'archive' => $item->archive,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];
        })->toArray();
        return $this->generateCSV($data, 'subdivisions_' . date('Y-m-d_His') . '.csv');
    }

    public function exportRoles()
    {
        $data = Role::all()->toArray();
        return $this->generateCSV($data, 'roles_' . date('Y-m-d_His') . '.csv');
    }

    public function exportPositions()
    {
        $data = Position::all()->toArray();
        return $this->generateCSV($data, 'positions_' . date('Y-m-d_His') . '.csv');
    }

    public function exportCategories()
    {
        $data = Category::all()->toArray();
        return $this->generateCSV($data, 'categories_' . date('Y-m-d_His') . '.csv');
    }

    public function exportTasks()
    {
        $data = Task::all()->toArray();
        return $this->generateCSV($data, 'tasks_' . date('Y-m-d_His') . '.csv');
    }

    public function exportBuilders()
    {
        $data = Builder::all()->toArray();
        return $this->generateCSV($data, 'builders_' . date('Y-m-d_His') . '.csv');
    }

    public function exportDwelings()
    {
        $data = Dweling::all()->toArray();
        return $this->generateCSV($data, 'dwelings_' . date('Y-m-d_His') . '.csv');
    }

    public function exportStatus()
    {
        $data = Status::all()->toArray();
        return $this->generateCSV($data, 'status_' . date('Y-m-d_His') . '.csv');
    }

    public function exportWorkStatus()
    {
        $data = WorkStatus::all()->toArray();
        return $this->generateCSV($data, 'work_status_' . date('Y-m-d_His') . '.csv');
    }

    public function exportEmployees()
    {
        $data = Employee::with(['division', 'subDivision', 'role', 'position'])->get()->map(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'username' => $item->username,
                'email' => $item->email,
                'join_date' => $item->join_date,
                'is_admin' => $item->is_admin,
                'can_approve' => $item->can_approve,
                'cutoff_exception' => $item->cutoff_exception,
                'is_supervisor' => $item->is_supervisor,
                'division_id' => $item->division_id,
                'division_name' => $item->division ? $item->division->title : '',
                'sub_division_id' => $item->sub_division_id,
                'sub_division_name' => $item->subDivision ? $item->subDivision->title : '',
                'role_id' => $item->role_id,
                'role_name' => $item->role ? $item->role->title : '',
                'position_id' => $item->position_id,
                'position_name' => $item->position ? $item->position->title : '',
                'user_id' => $item->user_id,
                'superior_id' => $item->superior_id,
                'is_approved' => $item->is_approved,
                'archive' => $item->archive,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];
        })->toArray();
        return $this->generateCSV($data, 'employees_' . date('Y-m-d_His') . '.csv');
    }

    public function exportLogs()
    {
        $data = Log::with(['employee', 'category', 'task', 'builder', 'dweling', 'status'])->get()->map(function($item) {
            return [
                'id' => $item->id,
                'date' => $item->date,
                'employee_id' => $item->employee_id,
                'employee_name' => $item->employee ? $item->employee->name : '',
                'subject' => $item->subject,
                'description' => $item->description,
                'qty' => $item->qty,
                'category_id' => $item->category_id,
                'category_name' => $item->category ? $item->category->title : '',
                'task_id' => $item->task_id,
                'task_name' => $item->task ? $item->task->title : '',
                'builder_id' => $item->builder_id,
                'builder_name' => $item->builder ? $item->builder->title : '',
                'dweling_id' => $item->dweling_id,
                'dweling_name' => $item->dweling ? $item->dweling->title : '',
                'status_id' => $item->status_id,
                'status_name' => $item->status ? $item->status->title : '',
                'duration' => $item->duration,
                'note' => $item->note,
                'work_time' => $item->work_time,
                'temp' => $item->temp,
                'approved' => $item->approved,
                'approved_date' => $item->approved_date,
                'approved_note' => $item->approved_note,
                'approved_emoji' => $item->approved_emoji,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];
        })->toArray();
        return $this->generateCSV($data, 'logs_' . date('Y-m-d_His') . '.csv');
    }

    public function exportOffwork()
    {
        $data = Offwork::with('employee')->get()->map(function($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'date' => $item->date,
                'leave_type' => $item->leave_type,
                'employee_id' => $item->employee_id,
                'employee_name' => $item->employee ? $item->employee->name : '',
                'description' => $item->description,
                'status' => $item->status,
                'archive' => $item->archive,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];
        })->toArray();
        return $this->generateCSV($data, 'offwork_' . date('Y-m-d_His') . '.csv');
    }

    public function exportHolidays()
    {
        $data = Holiday::all()->toArray();
        return $this->generateCSV($data, 'holidays_' . date('Y-m-d_His') . '.csv');
    }

    public function exportLeaveTypes()
    {
        $data = LeaveType::all()->toArray();
        return $this->generateCSV($data, 'leave_types_' . date('Y-m-d_His') . '.csv');
    }

    public function exportUsers()
    {
        $data = User::all()->map(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'email' => $item->email,
                'can_approve' => $item->can_approve,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];
        })->toArray();
        return $this->generateCSV($data, 'users_' . date('Y-m-d_His') . '.csv');
    }

    public function exportNotifications()
    {
        $data = Notification::all()->toArray();
        return $this->generateCSV($data, 'notifications_' . date('Y-m-d_His') . '.csv');
    }

    public function exportTimeCutoff()
    {
        $data = TimeCutoff::all()->toArray();
        return $this->generateCSV($data, 'time_cutoff_' . date('Y-m-d_His') . '.csv');
    }

    public function exportAll()
    {
        // Create a zip file with all tables
        $zipFileName = 'backup_all_' . date('Y-m-d_His') . '.zip';
        $zipPath = storage_path('app/public/' . $zipFileName);
        
        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            // Add each table as CSV to the zip
            $tables = [
                'divisions' => Division::all()->toArray(),
                'subdivisions' => SubDivision::all()->toArray(),
                'roles' => Role::all()->toArray(),
                'positions' => Position::all()->toArray(),
                'categories' => Category::all()->toArray(),
                'tasks' => Task::all()->toArray(),
                'builders' => Builder::all()->toArray(),
                'dwelings' => Dweling::all()->toArray(),
                'status' => Status::all()->toArray(),
                'work_status' => WorkStatus::all()->toArray(),
                'employees' => Employee::all()->toArray(),
                'logs' => Log::all()->toArray(),
                'offwork' => Offwork::all()->toArray(),
                'holidays' => Holiday::all()->toArray(),
                'leave_types' => LeaveType::all()->toArray(),
                'users' => User::select('id', 'name', 'email', 'can_approve', 'created_at', 'updated_at')->get()->toArray(),
                'notifications' => Notification::all()->toArray(),
                'time_cutoff' => TimeCutoff::all()->toArray(),
            ];

            foreach ($tables as $tableName => $data) {
                if (!empty($data)) {
                    $csv = '';
                    // Add BOM for UTF-8
                    $csv .= chr(0xEF).chr(0xBB).chr(0xBF);
                    
                    // Header
                    $csv .= implode(',', array_keys($data[0])) . "\n";
                    
                    // Data
                    foreach ($data as $row) {
                        $csv .= implode(',', array_map(function($value) {
                            return '"' . str_replace('"', '""', $value) . '"';
                        }, $row)) . "\n";
                    }
                    
                    $zip->addFromString($tableName . '.csv', $csv);
                }
            }
            
            $zip->close();
            
            return response()->download($zipPath)->deleteFileAfterSend(true);
        }
        
        return back()->with('error', 'Failed to create backup file');
    }
}
