<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Category Report - {{ $date }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            padding: 10px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        
        .header h2 {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }
        
        .category-section {
            margin-bottom: 20px;
        }
        
        .category-title {
            background-color: #007bff;
            color: white;
            padding: 8px;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 10px;
        }
        
        .employee-section {
            margin-bottom: 15px;
        }
        
        .employee-info {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 8px;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 11px;
        }
        
        .employee-info div {
            margin: 2px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 8px;
            table-layout: fixed;
        }
        
        th, td {
            border: 1px solid #333;
            padding: 3px 4px;
            text-align: left;
            vertical-align: top;
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
        }
        
        /* Column-specific styles with fixed widths */
        th:nth-child(1), td:nth-child(1) { /* No */
            width: 3%;
            text-align: center;
        }
        
        th:nth-child(2), td:nth-child(2) { /* Subject/Address */
            width: 12%;
        }
        
        th:nth-child(3), td:nth-child(3) { /* Description */
            width: 15%;
        }
        
        th:nth-child(4), td:nth-child(4) { /* Unit Qty */
            width: 4%;
            text-align: center;
        }
        
        th:nth-child(5), td:nth-child(5) { /* Category */
            width: 8%;
        }
        
        th:nth-child(6), td:nth-child(6) { /* Task */
            width: 12%;
        }
        
        th:nth-child(7), td:nth-child(7) { /* Builder */
            width: 12%;
        }
        
        th:nth-child(8), td:nth-child(8) { /* Dwelling */
            width: 14%;
        }
        
        th:nth-child(9), td:nth-child(9) { /* Status */
            width: 7%;
            text-align: center;
        }
        
        th:nth-child(10), td:nth-child(10) { /* Duration Minutes */
            width: 5%;
            text-align: center;
        }
        
        th:nth-child(11), td:nth-child(11) { /* Additional Notes */
            width: 5%;
        }
        
        th:nth-child(12), td:nth-child(12) { /* Work Status */
            width: 5%;
            text-align: center;
        }
        
        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        
        tfoot th {
            background-color: #333;
            color: white;
        }
        
        .total-hours {
            font-weight: bold;
            text-align: center;
        }
        
        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 20px;
        }
        
        @page {
            margin: 0.5cm;
            size: A4 landscape;
        }
        
        @media print {
            .category-section {
                page-break-inside: avoid;
            }
            
            .employee-section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Category Report</h1>
        <h2>Date: {{ \Carbon\Carbon::parse($date)->format('l, j F Y') }}</h2>
        <h2>Categories: {{ implode(', ', $selectedCategories) }}</h2>
    </div>

    @if(empty($categoryData))
        <div class="no-data">
            <h3>No data available for the selected date and categories.</h3>
        </div>
    @else
        @foreach($categoryData as $category)
            <div class="category-section">
                <div class="category-title">
                    Category: {{ $category['category'] }}
                </div>
                
                @foreach($category['employee'] as $employee)
                    <div class="employee-section">
                        @php
                            $empData = explode(',', $employee['employee']);
                            $empName = $empData[0] ?? '';
                            $empDivision = $empData[1] ?? '';
                            $empSubdivision = $empData[2] ?? '';
                        @endphp
                        <div class="employee-info">
                            <div>Name: {{ $empName }}{{ $empSubdivision ? ',' . $empSubdivision : '' }}</div>
                            <div>Division: {{ $empDivision }}</div>
                            <div>Date: {{ \Carbon\Carbon::parse($date)->format('l, j F Y') }}</div>
                        </div>
                        
                        @if(!empty($employee['items']))
                            <table>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Subject / Address</th>
                                        <th>Description</th>
                                        <th>Unit Qty</th>
                                        <th>Category</th>
                                        <th>Task</th>
                                        <th>Builder</th>
                                        <th>Dwelling</th>
                                        <th>Status</th>
                                        <th>Duration Minutes</th>
                                        <th>Additional Notes</th>
                                        <th>Work Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalMinutes = 0;
                                        $rowNumber = 1;
                                    @endphp
                                    
                                    @foreach($employee['items'] as $item)
                                        <tr>
                                            <td>{{ $rowNumber }}</td>
                                            <td>{{ $item['title'] }}</td>
                                            <td>{{ $item['description'] }}</td>
                                            <td>{{ $item['qty'] }}</td>
                                            <td>{{ $item['category'] }}</td>
                                            <td>{{ $item['task'] }}</td>
                                            <td>{{ $item['builder'] }}</td>
                                            <td>{{ $item['dweling'] }}</td>
                                            <td>{{ $item['status'] }}</td>
                                            <td>{{ $item['duration'] }}</td>
                                            <td>{{ $item['note'] }}</td>
                                            <td>{{ $item['wtime'] }}</td>
                                        </tr>
                                        @php
                                            $totalMinutes += $item['duration'];
                                            $rowNumber++;
                                        @endphp
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="9">Total Hours:</th>
                                        <th class="total-hours">{{ number_format($totalMinutes / 60, 2) }}</th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        @else
                            <div class="no-data">
                                <p>No logs found for this employee in this category.</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    @endif
</body>
</html>
