<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPDR Report - {{ $date }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 15px;
        }
        
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }
        
        .header h2 {
            color: #666;
            margin: 5px 0 0 0;
            font-size: 16px;
        }
        
        .date-info {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .leave-section {
            margin-bottom: 30px;
        }
        
        .leave-section h3 {
            color: #dc3545;
            background-color: #f8d7da;
            padding: 8px;
            margin: 0 0 10px 0;
            border-radius: 3px;
            font-size: 14px;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 10px;
        }
        
        .table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        
        .table-leave th {
            background-color: #dc3545;
            color: white;
        }
        
        .table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .employee-info {
            background-color: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        
        .employee-info table {
            width: 100%;
            border: none;
        }
        
        .employee-info td {
            border: none;
            padding: 2px 0;
        }
        
        .total-hours {
            background-color: #343a40;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 20px;
        }
        
        .holiday-notice {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SPDR Report</h1>
        <h2>Specialized Plumbing & Drainage Report</h2>
    </div>
    
    <div class="date-info">
        <strong>Report Date:</strong> {{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}
    </div>
    
    @if($holiday)
    <div class="holiday-notice">
        ⚠️ PUBLIC HOLIDAY
    </div>
    @endif
    
    @if(count($leaveList) > 0)
    <div class="leave-section">
        <h3>Leave List</h3>
        <table class="table table-leave">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 20%;">Name</th>
                    <th style="width: 15%;">Division</th>
                    <th style="width: 12%;">Sub Division</th>
                    <th style="width: 18%;">Role</th>
                    <th style="width: 10%;">Level</th>
                    <th style="width: 20%;">Reason</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leaveList as $index => $leave)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $leave['name'] }}</strong></td>
                    <td>{{ $leave['division'] }}</td>
                    <td>{{ $leave['sub_division'] }}</td>
                    <td>{{ $leave['role'] }}</td>
                    <td>{{ $leave['level'] }}</td>
                    <td>{{ $leave['reason'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    
    @if(count($spdrData) > 0)
        @foreach($spdrData as $employee)
        <div class="employee-section">
            <div class="employee-info">
                <table>
                    <tr>
                        <td style="width: 100px;"><strong>Name:</strong></td>
                        <td>{{ $employee['name'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Division:</strong></td>
                        <td>{{ $employee['division'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Date:</strong></td>
                        <td>{{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}</td>
                    </tr>
                </table>
            </div>
            
            @if(count($employee['logs']) > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 15%;">Subject / Address</th>
                        <th style="width: 15%;">Description</th>
                        <th style="width: 8%;">Unit Qty</th>
                        <th style="width: 10%;">Category</th>
                        <th style="width: 12%;">Task</th>
                        <th style="width: 12%;">Builder</th>
                        <th style="width: 10%;">Dweling</th>
                        <th style="width: 8%;">Status</th>
                        <th style="width: 8%;">Duration Minutes</th>
                        <th style="width: 15%;">Additional Notes</th>
                        <th style="width: 8%;">Work Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employee['logs'] as $index => $log)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $log['subject'] }}</td>
                        <td>{{ $log['description'] }}</td>
                        <td>{{ $log['qty'] ?: '' }}</td>
                        <td>{{ $log['category'] }}</td>
                        <td>{{ $log['task'] }}</td>
                        <td>{{ $log['builder'] }}</td>
                        <td>{{ $log['dweling'] }}</td>
                        <td>{{ $log['status'] }}</td>
                        <td>{{ $log['duration'] }}</td>
                        <td>{{ $log['note'] ?: '' }}</td>
                        <td>{{ $log['work_status'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-hours">
                        <td colspan="9"></td>
                        <td><strong>Total Hours:</strong></td>
                        <td><strong>{{ number_format($employee['total_hours'], 2) }}</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            @else
            <div class="no-data">
                No SPDR logs found for this employee on {{ \Carbon\Carbon::parse($date)->format('d F Y') }}
            </div>
            @endif
        </div>
        @endforeach
    @else
    <div class="no-data">
        No SPDR data found for {{ \Carbon\Carbon::parse($date)->format('d F Y') }}
    </div>
    @endif
    
    <div class="footer">
        <p>Generated on {{ now()->format('d F Y H:i:s') }} |  Daily Log System</p>
    </div>
</body>
</html>
