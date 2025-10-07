@extends('layouts.app')

@section('title', 'Monthly Report')

@section('content')
<style>
    thead th {
        width: 90px;
        font-size: 12px;
    }
    tbody td {
        font-size: 11px;
        vertical-align: middle;
    }
    .days-stat {
        font-size: 12px;
    }
    .combo {
        width: 200px;
    }
    .bg-red {
        background-color: #ffebee !important;
    }
</style>

<main id="main" class="main d-inline-block">
    <div class="pagetitle">
        <h1 class="text-capitalize">Monthly Report</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                <li class="breadcrumb-item active text-capitalize">Monthly Report</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-body pt-3">
                <div class="btn-parent mb-4 d-flex">
                    <div class="me-5">
                        <div class="fw-bold">Choose Month Periode</div>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="date" class="combo form-control form-control-sm ps-2 me-3" id="chaMounth" min="0000-00-00" max="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-6">
                                <input type="date" class="combo form-control form-control-sm ps-2 me-3" id="chaMounth2" min="0000-00-00" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div class="me-5">
                        <div class="fw-bold">Choose Category</div>
                        <select id="combocategory" class="combo form-select form-select-sm">
                            <option value="">All Category</option>
                        </select>
                    </div>
                    <div>
                        <div class="fw-bold">&nbsp;</div>
                        <button class="btn btn-success btn-sm" onclick="CreatePDFfromHTML();">Print Report</button>
                    </div>
                </div>
                <h3 class="text-center" style="display: none;">No Data</h3>
                <div id="report_container">
                    <div class="report-section-1" style="visibility: visible;">
                        <table id="tbl_report" class="table table-bordered table-sm table-striped mt-1" style="table-layout: fixed; width: 0">
                            <thead class="table-dark border-secondary text-center align-top">
                                <tr id="report_header">
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody id="report_body">
                            </tbody>
                        </table>
                    </div>
                    <div class="report-section-2 d-flex mt-4" style="font-size: 12px; visibility: visible;">
                        <div class="me-3" style="width: 400px">
                            <div class="border rounded border-1">
                                <div class="d-flex p-1">
                                    <div class="w-75">Effective Days</div><div class="ef-days w-25 text-end">0</div>
                                </div>
                                <div class="d-flex px-1 pb-1">
                                    <div class="w-75">Number of Public Holiday</div><div class="pu-days w-25 text-end">0</div>
                                </div>
                            </div>
                            <div class="group-employee mt-3 border rounded border-1">
                                <table id="tbl_productivity" class="table table-striped caption-top">
                                    <caption class="px-1 fw-bold">
                                        Average Effective Productivity Per Day<br>(Total Hours Include Overtime Hours / Number of Regular Days)
                                    </caption>
                                    <tbody id="productivity_body">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="chart_div" class="rounded border-1" style="width: 400px">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    if(!localStorage.getItem('dateMonthLocal')) {
        var date_1 = getCustomDate(new Date(), 'date_mysql').replace(/-(\d{2})$/, '-01');
        localStorage.setItem('dateMonthLocal', date_1);
        localStorage.setItem('dateMonthLocal2', getCustomDate(new Date(), 'date_mysql'));
    }
    
    const mainRoute = 'report/monthly',
        container = document.querySelector('.report-section-1'),
        section = document.querySelector('.report-section-2'),
        h3 = document.querySelector('h3'),
        inputdate = document.getElementById("chaMounth"),
        inputdate2 = document.getElementById("chaMounth2"),
        combocategory = document.getElementById("combocategory");
    
    var weekendDate = 0, efectiveDate = 0, publicHoliday = 0, hdate = [];

    inputdate.value = localStorage.getItem('dateMonthLocal');
    inputdate2.value = localStorage.getItem('dateMonthLocal2');

    const setDate = () => {
        fetch(`/${mainRoute}/setdate`).then((response) => {
            return response.json();
        }).then((data) => {
            inputdate.min = data.minDate;
            inputdate.max = data.maxDate;
            
            inputdate2.min = data.minDate;
            inputdate2.max = data.maxDate;
        }).then(() => {
            getData(category = '');
        });
    };

    const getcategories = () => {
        const year = parseInt(localStorage.getItem('dateMonthLocal').split('-')[0]),
            month = parseInt(localStorage.getItem('dateMonthLocal').split('-')[1]) - 1,
            date1 = localStorage.getItem("dateMonthLocal");

        let date2 = new Date(localStorage.getItem("dateMonthLocal2"));
        date2.setDate(date2.getDate() + 1);
        date2 = getCustomDate(date2, 'date_mysql')

        console.log('Fetching categories with params:', {year, month, date1, date2});
        
        fetch(`/${mainRoute}/getcategories?year=${year}&month=${month}&date1=${date1}&date2=${date2}`).then((response) => {
            console.log('Categories response status:', response.status);
            return response.json();
        }).then((data) => {
            console.log('Categories data received:', data);
            combocategory.innerHTML = '<option value="">All Category</option>';
            data.forEach((item) => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.title;
                combocategory.appendChild(option);
            });
            console.log('Categories dropdown populated with', data.length, 'items');
        }).catch((error) => {
            console.error('Error fetching categories:', error);
        });
    }

    getData = (cat) => {
        container.style.visibility = 'visible';
        section.style.visibility = 'visible';
        h3.style.display = 'none';
        
        let category = (cat) ? `&category=${cat}` : ''
        const year = parseInt(localStorage.getItem('dateMonthLocal').split('-')[0]),
            month = parseInt(localStorage.getItem('dateMonthLocal').split('-')[1]) - 1,
            date1 = localStorage.getItem("dateMonthLocal");

        let date2 = new Date(localStorage.getItem("dateMonthLocal2"));
        date2.setDate(date2.getDate() + 1);
        date2 = getCustomDate(date2, 'date_mysql')

        console.log('Fetching data with category:', cat);
        fetch(`/${mainRoute}/getData?year=${year}&month=${month}${category}&date1=${date1}&date2=${date2}`).then((response) => {
            return response.json();
        }).then((data) => {
            console.log('Data received for category:', cat, data);
            if(!data || !data.report_data || data.report_data.length === 0) {
                container.style.visibility = 'hidden';
                section.style.visibility = 'hidden';
                h3.style.display = 'block';
            } else {
                container.style.visibility = 'visible';
                section.style.visibility = 'visible';
                h3.style.display = 'none';
            }
            return data;
        }).then((data) => {
            createReport(data);
        });
    };

    const createReport = (data) => {
        if (!data || !data.report_data || data.report_data.length === 0) return;

        const { report_data, employee_stats, effective_days, public_holidays } = data;
        
        // Store data globally for PDF generation
        window.currentReportData = data;
        
        // Update statistics
        document.querySelector('.ef-days').innerHTML = effective_days;
        document.querySelector('.pu-days').innerHTML = public_holidays;

        // Create table header
        const headerRow = document.getElementById('report_header');
        headerRow.innerHTML = '<th>Date</th>';
        
        // Get unique employees
        const employees = Object.keys(employee_stats).map(id => ({
            id: id,
            name: employee_stats[id].name
        }));

        employees.forEach(employee => {
            headerRow.innerHTML += `<th class="text-center align-top">${employee.name}</th>`;
        });

        // Create table body
        const tbody = document.getElementById('report_body');
        tbody.innerHTML = '';

        report_data.forEach(day => {
            const row = document.createElement('tr');
            const dayName = new Date(day.date).toLocaleDateString('en-US', { 
                weekday: 'long', 
                day: 'numeric' 
            });
            
            // Check if it's a public holiday
            const isHoliday = day.day_name === 'Saturday' || day.day_name === 'Sunday' || 
                             (day.employees && Object.values(day.employees).every(hours => hours === ''));
            
            if (isHoliday) {
                row.innerHTML = `<td class="text-nowrap fw-bold text-end text-danger">* ${dayName}</td>`;
                employees.forEach(employee => {
                    row.innerHTML += `<td class="text-center text-dark bg-red">Public Holiday</td>`;
                });
            } else {
                row.innerHTML = `<td class="text-nowrap fw-bold text-end text-dark">${dayName}</td>`;
                employees.forEach(employee => {
                    const hours = day.employees[employee.id] || '';
                    row.innerHTML += `<td class="text-center text-dark">${hours}</td>`;
                });
            }
            
            tbody.appendChild(row);
        });

        // Create productivity table
        const productivityBody = document.getElementById('productivity_body');
        productivityBody.innerHTML = '';

        employees.forEach(employee => {
            const stats = employee_stats[employee.id];
            const avgProductivity = stats.working_days > 0 ? 
                (stats.total_hours / stats.working_days).toFixed(2) : '0.00';
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="w-75">${employee.name}</td>
                <td class="w-25 text-end">${avgProductivity}</td>
            `;
            productivityBody.appendChild(row);
        });

        // Create chart
        createChart(employees, employee_stats);
    };

    const createChart = (employees, employeeStats) => {
        console.log('Creating chart with', employees.length, 'employees');
        google.charts.load('current', {packages: ['corechart']});
        google.charts.setOnLoadCallback(() => {
            const data = new google.visualization.DataTable();
            data.addColumn('string', 'Employee');
            data.addColumn('number', 'Average Productivity');
            data.addColumn({type: 'string', role: 'annotation'});

            employees.forEach(employee => {
                const stats = employeeStats[employee.id];
                const avgProductivity = stats.working_days > 0 ? 
                    parseFloat((stats.total_hours / stats.working_days).toFixed(2)) : 0;
                
                data.addRow([employee.name, avgProductivity, avgProductivity.toString()]);
            });

            // Calculate dynamic max value based on data
            const maxProductivity = Math.max(...employees.map(emp => {
                const stats = employeeStats[emp.id];
                return stats.working_days > 0 ? (stats.total_hours / stats.working_days) : 0;
            }));
            const dynamicMaxValue = Math.ceil(maxProductivity * 1.1); // Add 10% padding

            const options = {
                title: 'Average Effective Productivity Chart',
                hAxis: {
                    title: 'Productivity Hours',
                    minValue: 0,
                    maxValue: dynamicMaxValue,
                    gridlines: {
                        color: '#ebebeb'
                    },
                    minorGridlines: {
                        color: '#ebebeb'
                    }
                },
                vAxis: {
                    title: 'Employee',
                    textStyle: {
                        fontSize: 12
                    }
                },
                height: 200,
                width: 400,
                chartArea: {
                    width: '60%', 
                    height: '90%',
                    left: 160,
                    top: 20,
                    bottom: 20
                },
                legend: {position: 'none'},
                bar: {
                    groupWidth: '20%'
                },
                colors: ['#0000ff'],
                backgroundColor: '#ffffff',
                annotations: {
                    textStyle: {
                        fontSize: 12,
                        color: '#0000ff'
                    },
                    alwaysOutside: true,
                    stem: {
                        color: 'transparent'
                    }
                },
                isStacked: false,
                focusTarget: 'category'
            };

            console.log('Chart options:', options);
            const chart = new google.visualization.BarChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        });
    };

    combocategory.addEventListener('change', (e) => {
        let val = e.target.value;
        console.log('Category selected:', val);
        getData(val);
    });

    inputdate.addEventListener('change', (e) => {
        let val = e.target.value;
        localStorage.setItem('dateMonthLocal', val);
        getData(category = '');
        getcategories();
    });

    inputdate2.addEventListener('change', (e) => {
        let val = e.target.value;
        localStorage.setItem('dateMonthLocal2', val);
        getData(category = '');
        getcategories();
    });

    const generatetable1 = (el) => {
        var table = document.getElementById("tbl_report");
        var header = [];
        var rows = [];
        var data = {};
 
        for (var i = 0; i < table.rows[0].cells.length; i++) {
            header.push(table.rows[0].cells[i].innerHTML);
        }
 
        for (var i = 1; i < table.rows.length; i++) {
            var row = {};
            for (var j = 0; j < table.rows[i].cells.length; j++) {
                row[j] = table.rows[i].cells[j].innerText;
            }
            rows.push(row);
        }
        data['th'] = header;
        data['td'] = rows;

        return data;
    }
 
    const generatetable2 = (el) => {
        var table = document.getElementById("tbl_productivity");
        var tr = [];
 
        for (var i = 0; i < table.rows.length; i++) {
            var row = {};
            for (var j = 0; j < table.rows[i].cells.length; j++) {
                row[j] = table.rows[i].cells[j].innerText;
            }
            tr.push(row);
        }
        return tr;
    }

    window.jsPDF = window.jspdf.jsPDF;

    const CreatePDFfromHTML = () => {
        date1 = localStorage.getItem("dateMonthLocal"),
        date2 = localStorage.getItem("dateMonthLocal2"),
        dat1 = getCustomDate(date1, 'date_idn');
        dat2 = getCustomDate(date2, 'date_idn');
        const rep_period = `${dat1} - ${dat2}`,
            rep_category = combocategory.options[combocategory.selectedIndex].text;
        
        // Get current report data
        const reportData = window.currentReportData || {};
        const { report_data, employee_stats, effective_days, public_holidays } = reportData;
        
        if (!report_data || report_data.length === 0) {
            alert('No data to print. Please generate report first.');
            return;
        }
        
        // Create PDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape', 'mm', 'a4');
        
        // Set font
        doc.setFont('helvetica');
        
        // Header
        doc.setFontSize(16);
        doc.setFont('helvetica', 'bold');
        doc.text('MONTHLY REPORT', 20, 20);
        
        doc.setFontSize(12);
        doc.setFont('helvetica', 'normal');
        doc.text(rep_period, 20, 30);
        doc.text('CATEGORY: ' + rep_category, 20, 35);
        
        // Create table data
        const employees = Object.keys(employee_stats).map(id => ({
            id: id,
            name: employee_stats[id].name
        }));
        
        // Table headers
        const headers = ['Date', ...employees.map(emp => emp.name)];
        const tableData = [];
        
        // Add data rows
        report_data.forEach(day => {
            const dayName = new Date(day.date).toLocaleDateString('en-US', { 
                weekday: 'long', 
                day: 'numeric' 
            });
            const row = [dayName];
            employees.forEach(employee => {
                const hours = day.employees[employee.id] || '';
                row.push(hours);
            });
            tableData.push(row);
        });
        
        // Draw table
        doc.autoTable({
            head: [headers],
            body: tableData,
            startY: 45,
            styles: {
                fontSize: 8,
                cellPadding: 2
            },
            headStyles: {
                fillColor: [66, 66, 66],
                textColor: [255, 255, 255],
                fontStyle: 'bold'
            },
            alternateRowStyles: {
                fillColor: [245, 245, 245]
            }
        });
        
        // Effective Days box
        const finalY = doc.lastAutoTable.finalY + 10;
        doc.setFillColor(240, 240, 240);
        doc.rect(20, finalY, 40, 15, 'F');
        doc.setFontSize(10);
        doc.setFont('helvetica', 'bold');
        doc.text('Effective Days', 25, finalY + 8);
        doc.text(effective_days.toString(), 25, finalY + 12);
        
        // Legend
        const legendY = finalY + 25;
        doc.setFontSize(8);
        doc.setFont('helvetica', 'normal');
        const legendItems = [
            'Annual Leave', 'Sick Leave', 'Public Holiday / Public Holiday Replacement',
            'Work off public Holiday (shift)', 'Overtime', 'Special Case',
            'Half-Day Off', 'Away without leave', '*Public Holiday'
        ];
        
        legendItems.forEach((item, index) => {
            const y = legendY + (index * 4);
            doc.text('• ' + item, 20, y);
        });
        
        // Productivity table
        const productivityY = legendY + (legendItems.length * 4) + 10;
        doc.setFontSize(10);
        doc.setFont('helvetica', 'bold');
        doc.text('Average Effective Productivity Per Day', 20, productivityY);
        
        const productivityData = employees.map(employee => {
            const stats = employee_stats[employee.id];
            const avgProductivity = stats.working_days > 0 ? 
                (stats.total_hours / stats.working_days).toFixed(2) : '0.00';
            return [employee.name, avgProductivity];
        });
        
        doc.autoTable({
            head: [['Employee', 'Average Productivity']],
            body: productivityData,
            startY: productivityY + 5,
            styles: {
                fontSize: 8,
                cellPadding: 2
            },
            headStyles: {
                fillColor: [66, 66, 66],
                textColor: [255, 255, 255],
                fontStyle: 'bold'
            }
        });
        
        // Add chart
        html2canvas(document.getElementById('chart_div')).then((canvas) => {
            const imgData = canvas.toDataURL('image/png');
            const chartY = doc.lastAutoTable.finalY + 10;
            doc.addImage(imgData, 'PNG', 20, chartY, 100, 50);
            
            // Save PDF
            const fileName = `Monthly_Report_${rep_period.replace(/\s+/g, '_')}_${rep_category.replace(/\s+/g, '_')}.pdf`;
            doc.save(fileName);
        });
    }

    // Helper function for date formatting
    function getCustomDate(date, format) {
        const d = new Date(date);
        if (format === 'date_mysql') {
            return d.toISOString().split('T')[0];
        } else if (format === 'date_idn') {
            return d.toLocaleDateString('id-ID');
        }
        return d.toISOString().split('T')[0];
    }

    window.addEventListener('load', (event) => {
        setDate();
        getcategories();
    });
</script>
@endsection
