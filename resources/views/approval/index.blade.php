@extends('layouts.app')

@section('content')
<style>
.tbl-employee {
    border-collapse: collapse;
    width: 100%;
}

.tbl-employee td {
    padding: 2px 8px;
    border: none;
    font-size: 14px;
}

.tbl-employee td:first-child {
    font-weight: bold;
    min-width: 80px;
}

.checkBox-sub {
    margin: 0;
}

.note-sub {
    border: 1px solid #ccc;
    padding: 4px;
    min-height: 20px;
    background: white;
    border-radius: 3px;
}

.emoji-sub, .emoji-row {
    border: 1px solid #ccc;
    padding: 2px;
    border-radius: 3px;
}

.log[contenteditable] {
    border: 1px solid transparent;
    padding: 2px;
    min-height: 20px;
    background: transparent;
}

.log[contenteditable]:focus {
    border: 1px solid #007bff;
    background: white;
    outline: none;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0,0,0,.02);
}

.table-dark {
    background-color: #343a40;
    color: #fff;
}

.table-dark th {
    border-color: #454d55;
}

.total-duration {
    font-weight: bold;
    color: #fff;
}

/* Fix table layout and card responsiveness */
#approvalContent .table {
    table-layout: auto !important;
    width: 100% !important;
    margin-bottom: 0;
}

#approvalContent .table th,
#approvalContent .table td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    vertical-align: middle;
}

#approvalContent .table th:nth-child(2),
#approvalContent .table td:nth-child(2),
#approvalContent .table th:nth-child(3),
#approvalContent .table td:nth-child(3),
#approvalContent .table th:nth-child(11),
#approvalContent .table td:nth-child(11),
#approvalContent .table th:nth-child(14),
#approvalContent .table td:nth-child(14) {
    white-space: normal;
    word-wrap: break-word;
}

/* Make table responsive */
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

/* Fix card body padding */
.card-body {
    padding: 0;
}

/* Approval content styling */
#approvalContent {
    padding: 1rem;
}

#approvalContent .mt-4 {
    margin-top: 2rem !important;
}

#approvalContent .mb-5 {
    margin-bottom: 2rem !important;
}

/* Add spacing between employee blocks */
#approvalContent > div:not(:first-child) {
    margin-top: 3rem !important;
}

/* Employee block styling */
.employee-block {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    background-color: #fafafa;
}

.employee-block:last-child {
    margin-bottom: 0;
}

/* Employee info table responsive */
.tbl-employee {
    min-width: 400px;
}

@media (max-width: 768px) {
    .tbl-employee {
        min-width: 300px;
        font-size: 12px;
    }
    
    .tbl-employee td {
        padding: 1px 4px;
    }
    
    .note-sub {
        width: 150px !important;
    }
    
    .bg-dark {
        padding: 0.5rem !important;
    }
    
    .bg-dark .d-flex {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .bg-dark .d-flex > div {
        width: 100% !important;
    }
}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
        <div class="pagetitle">
                    <h1 class="text-capitalize">Approval</h1>   </div>
                </div> 

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Approval</li>
    </ol>
</nav>
            <div class="card">
                <!-- <div class="card-header">
                    <h3 class="card-title">Approval</h3>
                </div> -->
                
                <div class="card-body p-0">
                    <div class="p-3 border-bottom">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="date1" class="form-label">Date From</label>
                                <input type="date" class="form-control" id="date1" max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="date2" class="form-label">Date To</label>
                                <input type="date" class="form-control" id="date2" max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div>
                                    <button type="button" class="btn btn-primary" id="loadData">Load Data</button>
                                    <button type="button" class="btn btn-success" id="submitApproval">Submit Approval</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Unapproved Logs Section -->
                    <div class="border-bottom">
                        <div class="bg-danger text-white p-2">
                            <h5 class="mb-0">Unapproved Logs</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead class="bg-danger text-white">
                                    <tr>
                                        <th style="width: 50px;">No</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody id="unapprovedLogsTable">
                                    <!-- Will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Off Duty List Section -->
                    <div class="border-bottom">
                        <div class="bg-danger text-white p-2">
                            <h5 class="mb-0">Off Duty List</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead class="bg-danger text-white">
                                    <tr>
                                        <th style="width: 50px;">No</th>
                                        <th>Name</th>
                                        <th>Division</th>
                                        <th>Sub Division</th>
                                        <th>Role</th>
                                        <th>Level</th>
                                        <th>Reason</th>
                                    </tr>
                                </thead>
                                <tbody id="offDutyTable">
                                    <!-- Will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="approvalContent">
                        <!-- Employee approval content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const date1Input = document.getElementById('date1');
    const date2Input = document.getElementById('date2');
    const loadDataBtn = document.getElementById('loadData');
    const submitApprovalBtn = document.getElementById('submitApproval');
    const approvalContent = document.getElementById('approvalContent');

    loadDataBtn.addEventListener('click', function() {
        const date1 = date1Input.value;
        const date2 = date2Input.value;
        
        if (!date1 || !date2) {
            alert('Please select both dates');
            return;
        }

        loadApprovalData(date1, date2);
    });

    // Load unapproved logs and off duty list on page load
    loadUnapprovedLogs();
    loadOffDutyList();
    
    // Auto-load data for today's date
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('date1').value = today;
    document.getElementById('date2').value = today;

    submitApprovalBtn.addEventListener('click', function() {
        const date1 = date1Input.value;
        const date2 = date2Input.value;
        
        if (!date1 || !date2) {
            alert('Please select both dates');
            return;
        }

        submitApproval(date1, date2);
    });

    function loadApprovalData(date1, date2, employeeId = null) {
        approvalContent.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        
        console.log('Loading data for date:', date1, 'employeeId:', employeeId);
        
        let url = `/approved/getData?date=${date1}`;
        if (employeeId) {
            url += `&id=${employeeId}`;
        }
        
        fetch(url)
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);
                if (Array.isArray(data)) {
                    renderApprovalData(data, date1, date2);
                } else {
                    console.error('Invalid data format:', data);
                    approvalContent.innerHTML = '<div class="alert alert-warning">Invalid data format received</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                approvalContent.innerHTML = '<div class="alert alert-danger">Error loading data: ' + error.message + '<br>Please check console for details.</div>';
            });
    }

    function renderApprovalData(data, date1, date2) {
        console.log('Rendering data for', data.length, 'employees');
        let html = '';
        
        if (!data || data.length === 0) {
            approvalContent.innerHTML = '<div class="alert alert-info">No data found for the selected date</div>';
            return;
        }
        
        data.forEach(employee => {
            
            
            // Calculate total hours
            const totalMinutes = employee.log.reduce((sum, log) => sum + (parseInt(log.duration) || 0), 0);
            const totalHours = (totalMinutes / 60).toFixed(2);
            
            html += `
                <div class="mt-4 mb-5 employee-block">
                    <div class="d-flex justify-content-between align-items-end">
                        <div class="border rounded d-inline-block p-1 mb-2" style="background-color: #F2F2F2">
                            <table class="tbl-employee">
                                <tbody>
                                    <tr>
                                        <td>Name</td>
                                        <td class="fw-bold pe-none" default="" onclick="getData(${employee.id})">: ${employee.title}</td>
                                        <td rowspan="3" class="px-3"></td>
                                        <td>Role</td>
                                        <td>: ${employee.role}</td>
                                    </tr>
                                    <tr>
                                        <td>Division</td>
                                        <td>: ${employee.division}</td>
                                        <td>Level</td>
                                        <td>: ${employee.position}</td>
                                    </tr>
                                    <tr>
                                        <td>Sub Division</td>
                                        <td>: ${employee.subdivision}</td>
                                        <td>Supervisor</td>
                                        <td>: {{ Auth::user()->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Note</td>
                                        <td colspan="4" class="fw-bold">: Employee is ${employee.archive ? 'Inactive' : 'Active'}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="bg-dark border rounded p-2 mb-2 mt-4">
                            <div class="text-center text-light mb-1 fw-bold">Approve Per Employee</div>
                            <div class="d-flex">
                                <div><input id="checkBox-${employee.id}" class="checkBox-sub" type="checkbox"></div>
                                <div class="mx-2" style="width: 224px"><div contenteditable="" id="textarea-${employee.id}" class="note-sub" placeholder="Approved note" spellcheck="false"></div></div>
                                <div><select id="select-${employee.id}" class="emoji-sub emoji">
                                    <option value=""></option>
                                    <option value="128559">😯</option>
                                    <option value="128535">😗</option>
                                    <option value="128512">😀</option>
                                    <option value="128525">😍</option>
                                    <option value="128077">👍</option>
                                    <option value="9996">✌</option>
                                    <option value="128079">👏</option>
                                    <option value="129309">🤝</option>
                                    <option value="128591">🙏</option>
                                    <option value="128078">👎</option>
                                </select></div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="table-${employee.id}" class="table table-bordered table-sm table-striped">
                            <thead class="table-dark border-secondary text-center align-top">
                                <tr>
                                    <th style="width:40px">#</th>
                                    <th style="min-width:160px">Subject</th>
                                    <th style="min-width:160px">Description</th>
                                    <th style="width:60px">Unit Qty</th>
                                    <th style="min-width:100px">Category</th>
                                    <th style="min-width:100px">Task</th>
                                    <th style="min-width:100px">Builder</th>
                                    <th style="min-width:100px">Dwelling</th>
                                    <th style="min-width:100px">Status</th>
                                    <th style="width:80px">Duration Minutes</th>
                                    <th style="min-width:160px">Additional Notes</th>
                                    <th style="width:80px">Work Status</th>
                                    <th style="width:40px"><i class="bi bi-check-lg"></i></th>
                                    <th style="min-width:160px">Approved Note</th>
                                    <th style="width:60px">Icon</th>
                                </tr>
                            </thead>
                        <tbody>
            `;
            
            employee.log.forEach((log, index) => {
                html += `
                    <tr id="tr-${log.id}" style="color: #000">
                        <td class="text-end">${index + 1}</td>
                        <td><div contenteditable="" class="log" specs="non" field="title" placeholder="Subject" data="${log.title}" spellcheck="false" style="background-color: transparent;">${log.title}</div></td>
                        <td><div contenteditable="" class="log" specs="non" field="description" placeholder="Description" data="${log.description || ''}" spellcheck="false">${log.description || ''}</div></td>
                        <td class="text-end"><div contenteditable="" class="log" specs="non" field="qty" placeholder="Qty" data="${log.qty || ''}" spellcheck="false">${log.qty || ''}</div></td>
                        <td><div contenteditable="" class="category" specs="non" field="category" placeholder="Category" data="${log.category}" spellcheck="false" style="background-color: transparent;">${log.category}</div></td>
                        <td><div contenteditable="" class="task" specs="non" field="task" placeholder="Task" data="${log.task}" spellcheck="false" style="background-color: transparent;">${log.task}</div></td>
                        <td><div contenteditable="" class="builder" specs="non" field="builder" placeholder="Builder" data="${log.builder}" spellcheck="false">${log.builder}</div></td>
                        <td><div contenteditable="" class="dweling" specs="non" field="dweling" placeholder="Dweling" data="${log.dweling}" spellcheck="false">${log.dweling}</div></td>
                        <td><div contenteditable="" class="status" specs="non" field="status" placeholder="Status" data="${log.status}" spellcheck="false" style="background-color: transparent;">${log.status}</div></td>
                        <td class="text-end"><div contenteditable="" class="log" specs="non" field="duration" placeholder="Duration" min="0" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" data="${log.duration}" spellcheck="false" style="background-color: transparent;">${log.duration}</div></td>
                        <td><div contenteditable="" class="log" specs="non" field="note" placeholder="Additional Notes" data="${log.note || ''}" spellcheck="false">${log.note || ''}</div></td>
                        <td><div contenteditable="" class="wtime" specs="non" field="wtime" placeholder="Work Time" data="${log.wtime}" spellcheck="false">${log.wtime}</div></td>
                        <td class="text-center"><input class="log approved-checkbox" type="checkbox" field="approved" data="${log.approved ? '1' : '0'}" data-log-id="${log.id}" ${log.approved ? 'checked' : ''}></td>
                        <td><div contenteditable="" class="log approved-note" field="approved_note" placeholder="Approved Note" spellcheck="false" data="${log.approved_note || ''}" data-log-id="${log.id}">${log.approved_note || ''}</div></td>
                        <td><select class="emoji-row emoji" field="approved_emoji" data="${log.approved_emoji || ''}">
                            <option value=""></option>
                            <option value="128559">😯</option>
                            <option value="128535">😗</option>
                            <option value="128512">😀</option>
                            <option value="128525">😍</option>
                            <option value="128077">👍</option>
                            <option value="9996">✌</option>
                            <option value="128079">👏</option>
                            <option value="129309">🤝</option>
                            <option value="128591">🙏</option>
                            <option value="128078">👎</option>
                        </select></td>
                    </tr>
                `;
            });
            
            html += `
                        </tbody>
                        <tfoot class="table-dark border-secondary text-center align-top">
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Total Hours:</th>
                                <th class="text-center total-duration">${totalHours}</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                        </table>
                    </div>
                </div>
            `;
        });
        
        approvalContent.innerHTML = html;
    }

    function submitApproval(date1, date2) {
        const approvalData = [];
        
        document.querySelectorAll('.approved-checkbox').forEach(checkbox => {
            const logId = checkbox.dataset.logId;
            const approved = checkbox.checked;
            const noteInput = document.querySelector(`.approved-note[data-log-id="${logId}"]`);
            const approvedNote = noteInput ? noteInput.value : '';
            
            approvalData.push({
                id: logId,
                approved: approved,
                approved_date: date2,
                approved_note: approvedNote,
                approved_emoji: ''
            });
        });

        const params = new URLSearchParams({
            date1: date1,
            date2: date2,
            data: JSON.stringify(approvalData)
        });

        fetch(`/approved/submit?${params}`)
            .then(response => response.json())
            .then(data => {
                if (data) {
                    alert('Approval submitted successfully');
                    loadApprovalData(date1, date2);
                } else {
                    alert('Error submitting approval');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error submitting approval');
            });
    }

    function approveAllForEmployee(employeeId, employeeName) {
        const noteInput = document.getElementById(`employeeNote_${employeeId}`);
        const generalNote = noteInput ? noteInput.value : '';
        
        if (!confirm(`Approve all logs for ${employeeName}?`)) {
            return;
        }

        const checkboxes = document.querySelectorAll(`.approved-checkbox`);
        const noteInputs = document.querySelectorAll(`.approved-note`);
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
        
        noteInputs.forEach(input => {
            if (generalNote) {
                input.value = generalNote;
            }
        });
        
        alert('All logs marked for approval. Click "Submit Approval" to save.');
    }

    function loadUnapprovedLogs() {
        fetch('/approved/getUnapprovedLogs')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('unapprovedLogsTable');
                let html = '';
                
                if (data.result && data.result.length > 0) {
                    // Get unique dates
                    const uniqueDates = [...new Set(data.result.map(log => log.log_date))];
                    
                    uniqueDates.forEach((date, index) => {
                        const dateObj = new Date(date);
                        const formattedDate = dateObj.toLocaleDateString('en-US', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                        
                        html += `
                            <tr>
                                <td>${index + 1}</td>
                                <td><a href="#" onclick="loadLogsForDate('${date}')">${formattedDate}</a></td>
                            </tr>
                        `;
                    });
                } else {
                    html = '<tr><td colspan="2" class="text-center">No unapproved logs</td></tr>';
                }
                
                tableBody.innerHTML = html;
            })
            .catch(error => {
                console.error('Error loading unapproved logs:', error);
            });
    }

    function loadOffDutyList() {
        // This would typically load from a separate endpoint for off-duty employees
        const tableBody = document.getElementById('offDutyTable');
        tableBody.innerHTML = '<tr><td colspan="7" class="text-center">No off-duty employees</td></tr>';
    }

    function loadLogsForDate(date) {
        // Convert date format from "2025-09-04T00:00:00.000000Z" to "2025-09-04"
        const dateOnly = date.split('T')[0];
        document.getElementById('date1').value = dateOnly;
        document.getElementById('date2').value = dateOnly;
        loadApprovalData(dateOnly, dateOnly);
    }

    window.getData = function(employeeId) {
        const date1 = document.getElementById('date1').value;
        const date2 = document.getElementById('date2').value;
        
        if (!date1 || !date2) {
            alert('Please select both dates');
            return;
        }

        loadApprovalData(date1, date2, employeeId);
    };

    window.editLog = function(logId) {
        fetch(`/approved/check?id=${logId}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    const log = data[0];
                    alert(`Log Details:\nSubject: ${log.title}\nDescription: ${log.description}\nDuration: ${log.duration} min\nCategory: ${log.category}\nTask: ${log.task}`);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    };
});
</script>
@endsection
