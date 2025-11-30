<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>TMA Report - {{ $date }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .employee-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .employee-header {
            background-color: #e0e0e0;
            font-weight: bold;
            padding: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>TMA Report</h1>
        <p>Date: {{ \Carbon\Carbon::parse($date)->format('l, F d, Y') }}</p>
        @if($holiday)
        <p style="color: red; font-weight: bold;">PUBLIC HOLIDAY</p>
        @endif
    </div>

    @if(count($leaveList) > 0)
    <h2>Leave List</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Division</th>
                <th>Sub Division</th>
                <th>Role</th>
                <th>Level</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leaveList as $index => $leave)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $leave['name'] }}</td>
                <td>{{ $leave['division'] }}</td>
                <td>{{ $leave['sub_division'] }}</td>
                <td>{{ $leave['role'] }}</td>
                <td>{{ $leave['level'] }}</td>
                <td>{{ $leave['reason'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @foreach($tmaData as $employee)
    <div class="employee-section">
        <div class="employee-header">
            <strong>Name:</strong> {{ $employee['name'] }} | 
            <strong>Division:</strong> {{ $employee['division'] }} | 
            <strong>Sub Division:</strong> {{ $employee['sub_division'] }} | 
            <strong>Role:</strong> {{ $employee['role'] }} | 
            <strong>Position:</strong> {{ $employee['position'] }}
        </div>
        
        @if(count($employee['logs']) > 0)
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Subject / Address</th>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Category</th>
                    <th>Task</th>
                    <th>Builder</th>
                    <th>Dwelling</th>
                    <th>Status</th>
                    <th>Duration (Min)</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employee['logs'] as $index => $log)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $log['subject'] }}</td>
                    <td>{{ $log['description'] }}</td>
                    <td>{{ $log['qty'] }}</td>
                    <td>{{ $log['category'] }}</td>
                    <td>{{ $log['task'] }}</td>
                    <td>{{ $log['builder'] }}</td>
                    <td>{{ $log['dweling'] }}</td>
                    <td>{{ $log['status'] }}</td>
                    <td>{{ $log['duration'] }}</td>
                    <td>{{ $log['note'] }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="9" style="text-align: right; font-weight: bold;">Total Hours:</td>
                    <td style="font-weight: bold;">{{ number_format($employee['total_hours'], 2) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        @else
        <p>No logs found for this date.</p>
        @endif
    </div>
    @endforeach
</body>
</html>

