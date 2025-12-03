@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">BI Report</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="date1">Start Date:</label>
                            <input type="date" id="date1" max="{{ date('Y-m-d') }}" class="form-control" name="date1">
                        </div>
                        <div class="col-md-3">
                            <label for="date2">End Date:</label>
                            <input type="date" id="date2" max="{{ date('Y-m-d') }}" class="form-control" name="date2">
                        </div>
                        <div class="col-md-3">
                            <label for="worktime">Work Time:</label>
                            <input type="text" id="worktime" class="form-control" placeholder="Work Time">
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <div>
                                <button type="button" class="btn btn-primary" onclick="getData()">Generate Report</button>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="employee-filter">Employee:</label>
                            <div class="dropdown-filter">
                                <input type="text" id="employee-filter" class="form-control" placeholder="Employee" readonly>
                                <div class="dropdown-content" id="employee-dropdown">
                                    <input type="text" placeholder="Search employees..." id="employee-search">
                                    <label class="select-all">
                                        <input type="checkbox" id="employee-select-all"> Select All
                                    </label>
                                    <div class="dropdown-list" id="employee-list"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="status-filter">Status:</label>
                            <div class="dropdown-filter">
                                <input type="text" id="status-filter" class="form-control" placeholder="Status" readonly>
                                <div class="dropdown-content" id="status-dropdown">
                                    <input type="text" placeholder="Search status..." id="status-search">
                                    <label class="select-all">
                                        <input type="checkbox" id="status-select-all"> Select All
                                    </label>
                                    <div class="dropdown-list" id="status-list"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="category-filter">Category:</label>
                            <div class="dropdown-filter">
                                <input type="text" id="category-filter" class="form-control" placeholder="Category" readonly>
                                <div class="dropdown-content" id="category-dropdown">
                                    <input type="text" placeholder="Search categories..." id="category-search">
                                    <label class="select-all">
                                        <input type="checkbox" id="category-select-all"> Select All
                                    </label>
                                    <div class="dropdown-list" id="category-list"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="builder-filter">Builder:</label>
                            <div class="dropdown-filter">
                                <input type="text" id="builder-filter" class="form-control" placeholder="Builder" readonly>
                                <div class="dropdown-content" id="builder-dropdown">
                                    <input type="text" placeholder="Search builders..." id="builder-search">
                                    <label class="select-all">
                                        <input type="checkbox" id="builder-select-all"> Select All
                                    </label>
                                    <div class="dropdown-list" id="builder-list"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="task-filter">Task:</label>
                            <div class="dropdown-filter">
                                <input type="text" id="task-filter" class="form-control" placeholder="Task" readonly>
                                <div class="dropdown-content" id="task-dropdown">
                                    <input type="text" placeholder="Search tasks..." id="task-search">
                                    <label class="select-all">
                                        <input type="checkbox" id="task-select-all"> Select All
                                    </label>
                                    <div class="dropdown-list" id="task-list"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="dwelling-filter">Dwelling:</label>
                            <div class="dropdown-filter">
                                <input type="text" id="dwelling-filter" class="form-control" placeholder="Dwelling" readonly>
                                <div class="dropdown-content" id="dwelling-dropdown">
                                    <input type="text" placeholder="Search dwellings..." id="dwelling-search">
                                    <label class="select-all">
                                        <input type="checkbox" id="dwelling-select-all"> Select All
                                    </label>
                                    <div class="dropdown-list" id="dwelling-list"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="subject-filter">Subject / Address:</label>
                            <div class="dropdown-filter">
                                <input type="text" id="subject-filter" class="form-control" placeholder="Subject" readonly>
                                <div class="dropdown-content" id="subject-dropdown">
                                    <input type="text" placeholder="Search subjects..." id="subject-search">
                                    <label class="select-all">
                                        <input type="checkbox" id="subject-select-all"> Select All
                                    </label>
                                    <div class="dropdown-list" id="subject-list"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="description-filter">Description:</label>
                            <input type="text" id="description-filter" class="form-control" placeholder="Description">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div id="chart-category" style="width: 100%; height: 400px;"></div>
                        </div>
                        <div class="col-md-4">
                            <div id="chart-task" style="width: 100%; height: 400px;"></div>
                        </div>
                        <div class="col-md-4">
                            <div id="chart-dweling" style="width: 100%; height: 400px;"></div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Detailed Log Data</h5>
                                </div>
                                <div class="card-body">
                                    <div style="overflow-x: auto; max-height: 400px;">
                                        <table class="table table-bordered table-striped" id="textTable1">
                                            <thead class="table-dark" style="position: sticky; top: 0; z-index: 10;">
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Name</th>
                                                    <th>Task</th>
                                                    <th>Subject / Address</th>
                                                    <th>Description</th>
                                                    <th>Status</th>
                                                    <th>Dwelling Type</th>
                                                    <th>Note</th>
                                                    <th>Minutes</th>
                                                </tr>
                                            </thead>
                                            <tbody id="report_body">
                                                <tr>
                                                    <td colspan="9" class="text-center">No data available. Please generate report first.</td>
                                                </tr>
                                            </tbody>
                                            <tfoot class="table-dark border-secondary text-center align-middle" style="position: sticky; bottom: 0;">
                                                <tr>
                                                    <th colspan="8" class="text-end">Total Hour : </th>
                                                    <th class="text-end" id="detail-total-hours">0.00</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Summary by Subject/Address</h5>
                                </div>
                                <div class="card-body">
                                    <div style="overflow-x: auto; max-height: 400px;">
                                        <table class="table table-bordered table-striped" id="textTable2">
                                            <thead class="table-dark" style="position: sticky; top: 0; z-index: 10;">
                                                <tr>
                                                    <th>Subject / Address</th>
                                                    <th>Category</th>
                                                    <th>Date</th>
                                                    <th>Name</th>
                                                    <th>Task</th>
                                                    <th>Description</th>
                                                    <th>Status</th>
                                                    <th>Dwelling Type</th>
                                                    <th>Note</th>
                                                    <th>Minutes</th>
                                                </tr>
                                            </thead>
                                            <tbody id="summary_body">
                                                <tr>
                                                    <td colspan="10" class="text-center">No data available. Please generate report first.</td>
                                                </tr>
                                            </tbody>
                                            <tfoot class="table-dark border-secondary text-center align-middle" style="position: sticky; bottom: 0;">
                                                <tr>
                                                    <th colspan="9" class="text-end">Total Hour : </th>
                                                    <th class="text-end" id="summary-total-hours">0.00</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tableMarge.js/1.0.0/tableMarge.min.js"></script>
<script src="https://www.gstatic.com/charts/loader.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

<script>
const mainRoute = 'report/bi';

let currentReportData = null;

document.addEventListener('DOMContentLoaded', function() {
    initializeDropdowns();
    // Load filter data immediately without waiting for dates
    loadFilterData();
    setTimeout(() => {
        setDate().then(() => {
            // Auto-load data after setting dates
            getData();
        });
    }, 100); // Delay to ensure DOM is ready
});

function setDate() {
    console.log('Setting dates...');
    fetch(`/${mainRoute}/setdate`)
        .then(response => response.json())
        .then(data => {
            console.log('Date data received:', data);
            document.getElementById('date1').value = data.min_date;
            document.getElementById('date2').value = data.max_date;
        })
        .catch(error => console.error('Error setting dates:', error));
}

function loadFilterData() {
    console.log('Loading filter data from database...');
    
    // Get selected employees for cascading filter
    const selectedEmployees = getSelectedValues('employee');
    console.log('Selected employees for cascading:', selectedEmployees);
    
    // Build query string for selected employees
    let url = `/${mainRoute}/getfilterdata`;
    if (selectedEmployees.length > 0) {
        const params = new URLSearchParams();
        selectedEmployees.forEach(employee => {
            params.append('employees[]', employee);
        });
        url += '?' + params.toString();
    }
    
    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Filter data received:', data);
            console.log('Employees data:', data.employees);
            console.log('Employees count:', data.employees ? data.employees.length : 'undefined');
            
            // Always populate employee dropdown with all employees
            if (data.employees) {
                console.log('Populating employee dropdown...');
                populateDropdown('employee', data.employees);
            } else {
                console.error('No employees data received!');
            }
            
            // Only populate other dropdowns if they have data
            if (data.categories && data.categories.length > 0) populateDropdown('category', data.categories);
            if (data.tasks && data.tasks.length > 0) populateDropdown('task', data.tasks);
            if (data.dwellings && data.dwellings.length > 0) populateDropdown('dwelling', data.dwellings);
            if (data.builders && data.builders.length > 0) populateDropdown('builder', data.builders);
            if (data.statuses && data.statuses.length > 0) populateDropdown('status', data.statuses);
            if (data.subjects && data.subjects.length > 0) populateDropdown('subject', data.subjects);
        })
        .catch(error => {
            console.error('Error loading filter data:', error);
            // Still populate with empty data to show no options available
            populateDropdown('employee', []);
            populateDropdown('category', []);
            populateDropdown('task', []);
            populateDropdown('dwelling', []);
            populateDropdown('builder', []);
            populateDropdown('status', []);
            populateDropdown('subject', []);
        });
}

function populateDropdown(type, data) {
    console.log(`Populating ${type} dropdown with:`, data);
    const list = document.getElementById(`${type}-list`);
    
    if (!list) {
        console.error(`Element with id '${type}-list' not found!`);
        return;
    }
    
    // Save current selections before clearing
    const currentSelections = getSelectedValues(type);
    console.log(`Current ${type} selections before repopulate:`, currentSelections);
    
    list.innerHTML = '';
    
    if (!data || data.length === 0) {
        console.log(`No data for ${type} dropdown`);
        return;
    }
    
    data.forEach(item => {
        const div = document.createElement('div');
        div.className = 'dropdown-item';
        const isSelected = currentSelections.includes(item.id.toString());
        div.innerHTML = `
            <input type="checkbox" value="${item.id}" id="${type}-${item.id}" ${isSelected ? 'checked' : ''}>
            <label for="${type}-${item.id}">${item.name}</label>
        `;
        list.appendChild(div);
    });
    
    console.log(`Added ${data.length} items to ${type} dropdown`);
    console.log(`Restored ${currentSelections.length} selections for ${type}`);
}

function initializeDropdowns() {
    const dropdownTypes = ['employee', 'category', 'task', 'dwelling', 'builder', 'status', 'subject'];
    
    dropdownTypes.forEach(type => {
        const filter = document.getElementById(`${type}-filter`);
        const dropdown = document.getElementById(`${type}-dropdown`);
        const search = document.getElementById(`${type}-search`);
        const selectAll = document.getElementById(`${type}-select-all`);
        const list = document.getElementById(`${type}-list`);
        
        // Toggle dropdown
        filter.addEventListener('click', function(e) {
            e.stopPropagation();
            closeAllDropdowns();
            dropdown.parentElement.classList.toggle('active');
        });
        
        // Search functionality
        search.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const items = list.querySelectorAll('.dropdown-item');
            
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchTerm) ? 'block' : 'none';
            });
        });
        
        // Select all functionality
        selectAll.addEventListener('change', function() {
            const checkboxes = list.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateFilterDisplay(type);
        });
        
        // Individual checkbox change
        list.addEventListener('change', function(e) {
            if (e.target.type === 'checkbox') {
                updateSelectAllState(type);
                updateFilterDisplay(type);
            }
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!dropdown.contains(e.target) && !filter.contains(e.target)) {
                dropdown.parentElement.classList.remove('active');
            }
        });
    });
}

function updateSelectAllState(type) {
    const selectAll = document.getElementById(`${type}-select-all`);
    const checkboxes = document.getElementById(`${type}-list`).querySelectorAll('input[type="checkbox"]');
    const checkedBoxes = document.getElementById(`${type}-list`).querySelectorAll('input[type="checkbox"]:checked');
    
    selectAll.checked = checkboxes.length === checkedBoxes.length;
    selectAll.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < checkboxes.length;
}

function updateFilterDisplay(type) {
    const filter = document.getElementById(`${type}-filter`);
    const checkboxes = document.getElementById(`${type}-list`).querySelectorAll('input[type="checkbox"]:checked');
    
    if (checkboxes.length === 0) {
        filter.value = '';
    } else if (checkboxes.length === 1) {
        filter.value = checkboxes[0].nextElementSibling.textContent;
    } else {
        filter.value = `${checkboxes.length} selected`;
    }
}

function closeAllDropdowns() {
    document.querySelectorAll('.dropdown-filter').forEach(dropdown => {
        dropdown.classList.remove('active');
    });
}

function getData() {
    const date1 = document.getElementById('date1').value;
    const date2 = document.getElementById('date2').value;
    const worktime = document.getElementById('worktime').value;
    const subject = document.getElementById('subject-filter').value;
    const description = document.getElementById('description-filter').value;
    
    // Get selected filters
    const filters = {
        employees: getSelectedValues('employee'),
        categories: getSelectedValues('category'),
        tasks: getSelectedValues('task'),
        dwellings: getSelectedValues('dwelling'),
        builders: getSelectedValues('builder'),
        statuses: getSelectedValues('status'),
        subjects: getSelectedValues('subject')
    };
    
    // Debug logging
    console.log('BI Report getData - Filters:', filters);
    console.log('Selected employees:', filters.employees);
    console.log('Employees count:', filters.employees.length);
    
    if (!date1 || !date2) {
        alert('Please select date range');
        return;
    }
    
    const params = new URLSearchParams();
    params.append('date1', date1);
    params.append('date2', date2);
    params.append('worktime', worktime);
    params.append('subject', subject);
    params.append('description', description);
    
    // Add array parameters
    filters.employees.forEach(id => params.append('employees[]', id));
    filters.categories.forEach(id => params.append('categories[]', id));
    filters.tasks.forEach(id => params.append('tasks[]', id));
    filters.dwellings.forEach(id => params.append('dwellings[]', id));
    filters.builders.forEach(id => params.append('builders[]', id));
    filters.statuses.forEach(id => params.append('statuses[]', id));
    filters.subjects.forEach(id => params.append('subjects[]', id));
    
    // Debug: log final URL
    console.log('Final URL:', `/${mainRoute}/getdata?${params}`);
    
    fetch(`/${mainRoute}/getdata?${params}`)
        .then(response => response.json())
        .then(data => {
            currentReportData = data;
            createReport(data);
            createCharts(data);
        })
        .catch(error => console.error('Error:', error));
}

function getSelectedValues(type) {
    const checkboxes = document.querySelectorAll(`#${type}-list input[type="checkbox"]:checked`);
    return Array.from(checkboxes).map(cb => cb.value);
}

function createReport(data) {
    const { report_data, category_stats, task_stats, dwelling_stats } = data;
    
    const reportBody = document.getElementById('report_body');
    const summaryBody = document.getElementById('summary_body');
    
    if (!report_data || report_data.length === 0) {
        reportBody.innerHTML = '<tr><td colspan="9" class="text-center">No data available for selected criteria.</td></tr>';
        summaryBody.innerHTML = '<tr><td colspan="10" class="text-center">No data available for selected criteria.</td></tr>';
        
        // Update total hours in tfoot even when no data
        const detailTotalElement = document.getElementById('detail-total-hours');
        detailTotalElement.innerHTML = '0.00';
        
        const summaryTotalElement = document.getElementById('summary-total-hours');
        summaryTotalElement.innerHTML = '0.00';
        return;
    }
    
    reportBody.innerHTML = '';
    summaryBody.innerHTML = '';
    
    let detailTotalHours = 0;
    
    report_data.forEach(log => {
        const minutes = parseFloat(log.hours);
        const hours = minutes / 60;
        detailTotalHours += hours;
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${log.date}</td>
            <td>${log.name}</td>
            <td>${log.task}</td>
            <td>${log.subject}</td>
            <td>${log.description}</td>
            <td>${log.status}</td>
            <td>${log.dwelling_type}</td>
            <td>${log.note}</td>
            <td>${minutes} min</td>
        `;
        reportBody.appendChild(row);
    });
    
    // Update total hours in tfoot for detail table
    const detailTotalElement = document.getElementById('detail-total-hours');
    detailTotalElement.innerHTML = detailTotalHours.toFixed(2);
    
    const summaryData = {};
    report_data.forEach(log => {
        const key = log.subject;
        if (!summaryData[key]) {
            summaryData[key] = {
                subject: log.subject,
                category: log.category || 'N/A',
                date: log.date,
                name: log.name,
                task: log.task,
                description: log.description,
                status: log.status,
                dwelling_type: log.dwelling_type,
                note: log.note,
                total_hours: 0
            };
        }
        summaryData[key].total_hours += parseFloat(log.hours); // Keep in minutes for consistency
    });
    
    let grandTotalHours = 0;
    
    Object.values(summaryData).forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.subject}</td>
            <td>${item.category}</td>
            <td>${item.date}</td>
            <td>${item.name}</td>
            <td>${item.task}</td>
            <td>${item.description}</td>
            <td>${item.status}</td>
            <td>${item.dwelling_type}</td>
            <td>${item.note}</td>
            <td>${item.total_hours} min</td>
        `;
        summaryBody.appendChild(row);
        grandTotalHours += item.total_hours / 60; // Convert to hours for total
    });
    
    // Update total hours in tfoot for summary table
    const summaryTotalElement = document.getElementById('summary-total-hours');
    summaryTotalElement.innerHTML = grandTotalHours.toFixed(2);
}

function createCharts(data) {
    const { category_stats, task_stats, dwelling_stats } = data;
    
    google.charts.load('current', {packages: ['corechart']});
    google.charts.setOnLoadCallback(() => {
        createCategoryChart(category_stats);
        createTaskChart(task_stats);
        createDwellingChart(dwelling_stats);
    });
}

function createCategoryChart(categoryStats) {
    const data = new google.visualization.DataTable();
    data.addColumn('string', 'Category');
    data.addColumn('number', 'Hours');
    data.addColumn({type: 'string', role: 'annotation'});
    
    Object.values(categoryStats).forEach(stat => {
        const minutes = parseFloat(stat.total_hours);
        const hours = minutes / 60; // Convert minutes to hours
        data.addRow([stat.name, hours, hours.toFixed(2)]);
    });
    
    const options = {
        title: 'Category Hours Chart',
        titleTextStyle: {
            fontSize: 16,
            bold: true,
            color: '#333'
        },
        hAxis: {
            title: 'Hours',
            titleTextStyle: {
                fontSize: 12,
                color: '#666'
            },
            textStyle: {
                fontSize: 10,
                color: '#666'
            },
            gridlines: {
                color: '#f5f5f5',
                count: 6
            },
            minorGridlines: {
                color: '#fafafa'
            }
        },
        vAxis: {
            textStyle: {
                fontSize: 11,
                color: '#333'
            }
        },
        colors: ['#4285f4'],
        bar: {
            groupWidth: '40%'
        },
        chartArea: {
            left: 140,
            top: 40,
            right: 40,
            bottom: 40,
            width: '100%',
            height: '85%'
        },
        height: 400,
        width: '100%',
        annotations: {
            textStyle: {
                fontSize: 12,
                color: '#333',
                bold: true
            },
            alwaysOutside: true,
            stem: {
                color: 'transparent'
            }
        },
        legend: {
            position: 'none'
        }
    };
    
    const chart = new google.visualization.BarChart(document.getElementById('chart-category'));
    chart.draw(data, options);
}

function createTaskChart(taskStats) {
    const data = new google.visualization.DataTable();
    data.addColumn('string', 'Task');
    data.addColumn('number', 'Hours');
    
    // Sort by hours descending and limit to top 8 tasks
    const sortedStats = Object.values(taskStats)
        .sort((a, b) => parseFloat(b.total_hours) - parseFloat(a.total_hours))
        .slice(0, 8);
    
    sortedStats.forEach(stat => {
        const minutes = parseFloat(stat.total_hours);
        const hours = minutes / 60; // Convert minutes to hours
        data.addRow([stat.name, hours]);
    });

    
    const options = {
        title: 'Task Hours Percentage Chart',
        titleTextStyle: {
            fontSize: 16,
            bold: true,
            color: '#333'
        },
        pieHole: 0.3,
        height: 400,
        width: '100%',
        chartArea: {
            left: 20,
            top: 50,
            right: 20,
            bottom: 20,
            width: '100%',
            height: '80%'
        },
        colors: ['#4285f4', '#ea4335', '#fbbc04', '#34a853', '#ff6d01', '#9c27b0', '#00bcd4', '#795548'],
        pieSliceText: 'percentage',
        pieSliceTextStyle: {
            fontSize: 11,
            color: 'white',
            bold: true
        },
        legend: {
            position: 'right',
            textStyle: {
                fontSize: 10,
                color: '#333'
            },
            maxLines: 3
        },
        tooltip: {
            textStyle: {
                fontSize: 12
            }
        }
    };
    
    const chart = new google.visualization.PieChart(document.getElementById('chart-task'));
    chart.draw(data, options);
}

function createDwellingChart(dwellingStats) {
    const data = new google.visualization.DataTable();
    data.addColumn('string', 'Dwelling Type');
    data.addColumn('number', 'Hours');
    data.addColumn({type: 'string', role: 'annotation'});
    
    Object.values(dwellingStats).forEach(stat => {
        const minutes = parseFloat(stat.total_hours);
        const hours = minutes / 60; // Convert minutes to hours
        data.addRow([stat.name, hours, hours.toFixed(2)]);
    });
    
    const options = {
        title: 'Dwelling Hours Chart',
        titleTextStyle: {
            fontSize: 16,
            bold: true,
            color: '#333'
        },
        hAxis: {
            title: 'Hours',
            titleTextStyle: {
                fontSize: 12,
                color: '#666'
            },
            textStyle: {
                fontSize: 10,
                color: '#666'
            },
            gridlines: {
                color: '#f5f5f5',
                count: 6
            },
            minorGridlines: {
                color: '#fafafa'
            }
        },
        vAxis: {
            textStyle: {
                fontSize: 11,
                color: '#333'
            }
        },
        colors: ['#4285f4'],
        bar: {
            groupWidth: '40%'
        },
        chartArea: {
            left: 220,
            top: 40,
            right: 40,
            bottom: 40,
            width: '100%',
            height: '85%'
        },
        height: 400,
        width: '100%',
        annotations: {
            textStyle: {
                fontSize: 12,
                color: '#333',
                bold: true
            },
            alwaysOutside: true,
            stem: {
                color: 'transparent'
            }
        },
        legend: {
            position: 'none'
        }
    };
    
    const chart = new google.visualization.BarChart(document.getElementById('chart-dweling'));
    chart.draw(data, options);
}

document.getElementById('date1').addEventListener('change', loadFilterData);
document.getElementById('date2').addEventListener('change', loadFilterData);

// Add event listener for employee dropdown changes to trigger cascading filter
document.addEventListener('change', function(e) {
    console.log('Change event detected:', e.target);
    
    // Check if it's an employee checkbox
    if (e.target.matches('#employee-list input[type="checkbox"]')) {
        console.log('Employee checkbox changed:', e.target.value, e.target.checked);
        console.log('Selected employees after change:', getSelectedValues('employee'));
        
        // Clear other dropdowns first (but NOT employee dropdown)
        clearOtherDropdowns('employee');
        
        // Reload filter data with new employee selection (but preserve employee selections)
        setTimeout(() => {
            loadFilterDataPreservingEmployees();
        }, 100); // Small delay to ensure checkbox state is updated
    }
});

function loadFilterDataPreservingEmployees() {
    console.log('Loading filter data while preserving employee selections...');
    
    // Get selected employees for cascading filter
    const selectedEmployees = getSelectedValues('employee');
    console.log('Selected employees for cascading:', selectedEmployees);
    
    // Build query string for selected employees
    let url = `/${mainRoute}/getfilterdata`;
    if (selectedEmployees.length > 0) {
        const params = new URLSearchParams();
        selectedEmployees.forEach(employee => {
            params.append('employees[]', employee);
        });
        url += '?' + params.toString();
    }
    
    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then(response => response.json())
        .then(data => {
            console.log('Filter data received (preserving employees):', data);
            
            // DON'T repopulate employee dropdown - preserve selections
            // Only populate other dropdowns
            if (data.categories && data.categories.length > 0) populateDropdown('category', data.categories);
            if (data.tasks && data.tasks.length > 0) populateDropdown('task', data.tasks);
            if (data.dwellings && data.dwellings.length > 0) populateDropdown('dwelling', data.dwellings);
            if (data.builders && data.builders.length > 0) populateDropdown('builder', data.builders);
            if (data.statuses && data.statuses.length > 0) populateDropdown('status', data.statuses);
            if (data.subjects && data.subjects.length > 0) populateDropdown('subject', data.subjects);
        })
        .catch(error => {
            console.error('Error loading filter data:', error);
        });
}

function clearOtherDropdowns(excludeField) {
    console.log('Clearing other dropdowns, excluding:', excludeField);
    const fields = ['category', 'task', 'dwelling', 'builder', 'status', 'subject'];
    fields.forEach(field => {
        if (field !== excludeField) {
            const dropdown = document.querySelector(`.dropdown-filter[data-field="${field}"]`);
            if (dropdown) {
                const input = dropdown.querySelector('input');
                const list = dropdown.querySelector('.dropdown-list');
                if (input) {
                    input.value = '';
                    console.log(`Cleared input for ${field}`);
                }
                if (list) {
                    list.innerHTML = '';
                    console.log(`Cleared list for ${field}`);
                }
            } else {
                console.log(`Dropdown not found for ${field}`);
            }
        }
    });
}
</script>

<style>
.table th, .table td {
    font-size: 12px;
    padding: 4px;
    white-space: nowrap;
}

.table thead th {
    background-color: #343a40;
    color: white;
    border-color: #454d55;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0,0,0,.05);
}

.table-bordered {
    border: 1px solid #dee2e6;
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #004085;
}

.form-control {
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}

.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Dropdown Filter Styles */
.dropdown-filter {
    position: relative;
    display: inline-block;
    width: 100%;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: white;
    min-width: 100%;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1000;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    max-height: 300px;
    overflow-y: auto;
}

.dropdown-content input[type="text"] {
    width: 100%;
    padding: 8px 12px;
    border: none;
    border-bottom: 1px solid #dee2e6;
    border-radius: 0;
    font-size: 14px;
}

.dropdown-content input[type="text"]:focus {
    outline: none;
    border-bottom-color: #80bdff;
}

.select-all {
    display: block;
    padding: 8px 12px;
    margin: 0;
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
}

.select-all input[type="checkbox"] {
    margin-right: 8px;
}

.dropdown-list {
    max-height: 200px;
    overflow-y: auto;
}

.dropdown-item {
    display: block;
    padding: 8px 12px;
    cursor: pointer;
    border-bottom: 1px solid #f1f3f4;
    font-size: 14px;
    transition: background-color 0.2s;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.dropdown-item input[type="checkbox"] {
    margin-right: 8px;
}

.dropdown-item.selected {
    background-color: #e3f2fd;
}

.dropdown-filter.active .dropdown-content {
    display: block;
}

/* Custom scrollbar for dropdown */
.dropdown-content::-webkit-scrollbar {
    width: 6px;
}

.dropdown-content::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.dropdown-content::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.dropdown-content::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Modern Chart Styling */
.chart-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid #e0e0e0;
}

.chart-container h5 {
    color: #333;
    font-weight: 600;
    margin-bottom: 15px;
    font-size: 16px;
}

/* Clean Chart Styling */
h5 {
    color: #333;
    font-weight: 600;
    font-size: 16px;
    margin-bottom: 15px;
}

#chart-category, #chart-task, #chart-dweling {
    background: white;
    border-radius: 4px;
}
</style>
@endsection
