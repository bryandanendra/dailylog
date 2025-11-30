@extends('layouts.app')

@section('title', 'Employee Management - Daily Log System')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="pagetitle">
    <h1 class="text-capitalize">Employee</h1>
</div>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Employee</li>
    </ol>
</nav>


<section class="section">
    <div class="row">
        <div class="card">
            <div class="card-body pt-3">
                <h4 class="text-capitalize mb-4">Manage Employee</h4>
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex gap-2">
                        <button type="button" id="btn-add" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i>&nbsp;Register</button>
                        <div id="approval-buttons" style="display: none;">
                            <button type="button" id="btn-bulk-approve" class="btn btn-sm btn-success"><i class="bi bi-check-square"></i>&nbsp;Approve</button>
                            <button type="button" id="btn-bulk-reject" class="btn btn-sm btn-danger"><i class="bi bi-x-square"></i>&nbsp;Reject</button>
                        </div>
                        <button type="button" id="btn-current" class="btn btn-sm btn-success ms-5">Current</button>
                        <button type="button" id="btn-archive" class="btn btn-sm btn-secondary ms-1">Archive</button>
                        <button type="button" id="btn-register" class="btn btn-sm btn-secondary ms-1">Registration</button>
                    </div>
                    <div class="search-bar">
                        <div class="search-data d-flex align-items-center">
                            <input type="text" id="search" class="search" placeholder="Search Name" title="Enter search keyword">
                            <button type="button" id="btnSearch" title="Search"><i class="bi bi-search"></i></button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="table-1" class="table table-bordered table-sm table-striped mt-1">
                        <thead class="table-dark border-secondary text-center align-top">
                          <tr>
                            <th draggable="true" style="width: 30px; min-width: 30px;" field="select">
                              <input type="checkbox" id="select-all" style="cursor: pointer;">
                            </th>
                            <th draggable="true" style="width: 80px; min-width: 80px;" field="action">Action</th>
                            <th draggable="true" style="width: 50px; min-width: 50px;" field="id">#</th>
                            <th draggable="true" style="width: 150px; min-width: 150px;" field="title">Name</th>
                            <th draggable="true" style="width: 200px; min-width: 200px;" field="username">User Name</th>
                            <th draggable="true" style="width: 100px; min-width: 100px;" field="join_date">Join Date</th>
                            <th draggable="true" style="width: 70px; min-width: 70px;" field="administrator">Admin</th>
                            <th draggable="true" style="width: 90px; min-width: 90px;" class="approved" field="approved">Approval</th>
                            <th draggable="true" style="width: 100px; min-width: 100px;" field="archivereport">Cut Off Exception</th>
                            <th draggable="true" style="width: 120px; min-width: 120px;" field="parent_id">Supervisor</th>
                            <th draggable="true" style="width: 120px; min-width: 120px;" field="division_id">Division</th>
                            <th draggable="true" style="width: 120px; min-width: 120px;" field="subdivision_id">Sub Division</th>
                            <th draggable="true" style="width: 120px; min-width: 120px;" field="role_id">Role</th>
                            <th draggable="true" style="width: 100px; min-width: 100px;" field="position_id">Position</th>
                            <th draggable="true" style="width: 150px; min-width: 150px;" field="description">Description</th>
                          </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded here via JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div class="toolbar-bottom d-flex mt-3 align-items-center justify-content-between" style="visibility: visible;">
                    <div class="d-flex align-items-center border rounded" style="width:94px">
                        <div class="bg-primary px-2" title="item per page"><i class="bi bi-book-half text-white" style="font-size: 20px"></i></div>
                        <input type="number" id="limit" class="border-0 ps-1 w-100 text-primary" min="1" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
                    </div>
                    <div class="d-flex">
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm m-0"><li class="page-item active"><a class="page-link" style="cursor: pointer">1</a></li></ul>
                        </nav>
                    </div>
                </div>
                <div class="d-none">
                    <input type="text" id="page">
                    <input type="text" id="sort">
                    <input type="text" id="order">
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Table styling */
    #table-1 {
        width: 100%;
        table-layout: auto;
        border-collapse: collapse;
    }
    
    #table-1 th {
        background-color: #343a40;
        color: white;
        font-weight: 600;
        padding: 12px 8px;
        text-align: center;
        border: 1px solid #dee2e6;
        white-space: nowrap;
    }
    
    #table-1 td {
        padding: 8px;
        border: 1px solid #dee2e6;
        vertical-align: middle;
    }
    
    #table-1 tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }
    
    #table-1 tbody tr:hover {
        background-color: #e9ecef;
    }
    
    /* Checkbox styling */
    input[type=checkbox] {
        height: 18px;
        width: 18px;
        margin: 0;
        cursor: pointer;
    }
    
    /* Input and select styling */
    input[type=date], select {
        width: 100%;
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 4px 8px;
        font-size: 14px;
        background-color: #fff;
    }
    
    input[type=date]:focus, select:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    /* Action icons */
    td i {
        font-size: 18px !important;
        margin: 0 2px;
    }
    
    /* Search styling */
    .search-data {
        width: 250px;
    }
    
    .search-data input {
        border: 1px solid rgba(1, 41, 112, 0.2);
        font-size: 14px;
        color: #012970;
        padding: 6px 30px 6px 8px;
        border-radius: 4px;
        transition: 0.3s;
        width: 100%;
    }
    
    .search-data input:focus,
    .search-data input:hover {
        outline: none;
        box-shadow: 0 0 10px 0 rgba(1, 41, 112, 0.15);
        border: 1px solid rgba(1, 41, 112, 0.3);
    }
    
    .search-data button {
        border: 0;
        padding: 0;
        margin-left: -24px;
        background: none;
    }
    
    .search-data button i {
        color: #012970;
    }
    
    /* Modal styling */
    .modal input[type=text],
    .modal input[type=date],
    .modal input[type=number],
    .modal textarea,
    .modal select {
        width: 100%;
        border: 1px solid #ccc;
        background-color: #fff;
        border-radius: 4px;
        padding: 8px;
        font-size: 14px;
    }
    
    /* Editable content */
    .edit {
        border: none;
        background: transparent;
        width: 100%;
        padding: 4px;
    }
    
    .edit:focus {
        outline: none;
        background-color: #fff;
        border: 1px solid #007bff;
        border-radius: 4px;
    }
    
    /* Table responsive */
    .table-responsive {
        border-radius: 8px;
        overflow-x: auto;
        overflow-y: visible;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        max-height: 70vh;
        width: 100%;
    }
    
    /* Ensure table has minimum width for horizontal scroll */
    #table-1 {
        min-width: 1200px;
        width: 100%;
        table-layout: fixed;
    }
    
    /* Force horizontal scroll on smaller screens */
    @media (max-width: 1200px) {
        .table-responsive {
            overflow-x: scroll;
        }
    }
    
    /* Hide elements */
    .showhide {
        display: none;
    }
    
    /* Pagination styling */
    .pagination {
        margin: 0;
    }
    
    .pagination .page-link {
        color: #007bff;
        border: 1px solid #dee2e6;
        padding: 0.375rem 0.75rem;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
    }
    
    /* Date input styling */
    input[type="date"]::-webkit-calendar-picker-indicator {
        cursor: pointer;
        opacity: 1;
        display: block;
    }
    
    /* Future dates (disabled) */
    td[data-date].disabled,
    .calendar-popup td.disabled,
    .datepicker-days td.disabled {
        cursor: not-allowed !important;
        pointer-events: none !important;
        opacity: 0.5 !important;
        color: #999 !important;
        background-color: #f5f5f5 !important;
        text-decoration: line-through;
    }
    
    /* Modal styling */
    .modal {
        z-index: 1055;
    }
    
    .modal-backdrop {
        z-index: 1050;
    }
    
    .modal-dialog {
        max-width: 800px;
    }
    
    .modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var combo = '', timeout = null, tab = 1;
    const table = document.querySelector('table'),
        tbody = document.querySelector('tbody'),
        btnAdd = document.getElementById('btn-add'),
        btnCur = document.getElementById('btn-current'),
        btnReg = document.getElementById('btn-register'),
        btnArc = document.getElementById('btn-archive'),
        search = document.getElementById('search'),
        limit = document.getElementById('limit'),
        page = document.getElementById('page'),
        sort = document.getElementById('sort'),
        order = document.getElementById('order'),
        sortBtn = document.querySelectorAll('thead th');
    
    // Initialize default values
    limit.value = 25;
    page.value = 1;
    sort.value = 'id';
    order.value = 'ASC';
    
    const loadCombo = (arr, val) => {
        let opt = '<option value="">Choose</option>';
        arr.forEach(option => {
            let selected = (val === option.id) ? 'selected' : '';
            opt += `<option value="${option.id}" ${selected}>${option.title}</option>`;
        });
        return opt;
    };
    
    const loadRow = (obj) => {
        let row = '';
        obj.rows.forEach(data => {   
            const joinDate = data.join_date || '',
                administrator = data.administrator ? 'checked' : '',
                archivereport = data.archivereport ? 'checked' : '',
                approved = data.approved ? 'checked' : '',
                title = (data.title) ? data.title : '',
                username = (data.username) ? data.username : '',
                description = (data.description) ? data.description : '';
            // Different actions based on tab
            let actionButtons = '';
            if (tab === 2) { // Registration Request tab
                actionButtons = `
                    <div class="d-flex justify-content-center gap-1">
                        <i class="bi bi-check-square text-success" title="Approve" onclick="approveEmployee(${data.id})" style="cursor: pointer;"></i>
                        <i class="bi bi-x-square text-danger" title="Reject" onclick="rejectEmployee(${data.id})" style="cursor: pointer;"></i>
                        <i class="bi bi-pencil-square text-info" title="Edit" onclick="editRow(this.closest('tr').id)" style="cursor: pointer;"></i>
                    </div>
                `;
            } else { // Current Employees and Archive tabs
                actionButtons = `
                    <div class="d-flex justify-content-center gap-1">
                        <i class="bi bi-x-square text-danger" title="Remove" onclick="removeRow(${data.id})" style="cursor: pointer;"></i>
                        <i class="bi bi-pencil-square text-info" title="Edit" onclick="editRow(this.closest('tr').id)" style="cursor: pointer;"></i>
                        <i class="bi bi-exclamation-square text-warning" title="Reset Password" onclick="resetpwd(this.closest('tr').id)" style="cursor: pointer;"></i>
                    </div>
                `;
            }

            row += `
            <tr id="${data.id}" class="tr-${data.id}">
                <td class="text-center">
                    <input type="checkbox" name="employee_ids" value="${data.id}" class="employee-checkbox" style="cursor: pointer;">
                </td>
                <td class="text-center">
                    ${actionButtons}
                </td>
                <td class="text-center">${obj.offset+1}</td>
                <td class="pe-none"><div class="edit" contentEditable field="title" placeholder="Name" spellcheck="false">${title}</div></td>
                <td class="pe-none"><div class="edit" contentEditable field="username" placeholder="User Name" spellcheck="false">${username}</div></td>
                <td class="pe-none"><input class="edit" type="date" field="join_date" placeholder="Join Date" value="${joinDate}"/></td>
                <td class="pe-none text-center"><input class="edit" type="checkbox" class="administrator" field="administrator" ${administrator} /></td>
                <td class="pe-none text-center"><input class="edit" type="checkbox" class="approved" field="approved" ${approved}/></td>
                <td class="pe-none text-center"><input class="edit" type="checkbox" class="archivereport" field="archivereport" ${archivereport}/></td>
                <td class="pe-none"><select class="edit" field="parent_id">${loadCombo(combo.parent, data.parent_id)}</select></td>
                <td class="pe-none"><select class="edit" field="division_id">${loadCombo(combo.division, data.division_id)}</select></td>
                <td class="pe-none"><select class="edit" field="subdivision_id">${loadCombo(combo.subdivision, data.subdivision_id)}</select></td>
                <td class="pe-none"><select class="edit" field="role_id">${loadCombo(combo.role, data.role_id)}</select></td>
                <td class="pe-none"><select class="edit" field="position_id">${loadCombo(combo.position, data.position_id)}</select></td>
                <td class="pe-none"><div class="edit" contentEditable field="description" placeholder="Description" spellcheck="false">${description}</div></td>
            </tr>`;
            obj.offset ++;
        });
        tbody.innerHTML = row;
    };
    
    const getCombo = () => {
        fetch(`/employee/getCombo`).then((response) => {
            return response.json();
        }).then((datas) => {
            const data = JSON.parse(datas);
            combo = data;
        }).then(() => {
            getData(true);
        });
    };

    const editRow = (ele) => {
        modalEmployee(ele);
        setTimeout(() => {
            const myModal = new bootstrap.Modal(document.querySelector('#modal-table')),
                myModalEl = document.getElementById('modal-table');
            myModal.show();
            myModalEl.addEventListener('hidden.bs.modal', () => {
                myModalEl.remove();
            });
        }, 100);
    };

    const removeRow = (id) => {
        if(confirm('Are you sure? this data will be remove')) {
            fetch(`/employee/remove?id=${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    alert(data.message || 'Data has been removed');
                    getData();
                } else {
                    alert('Failed: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting');
            });
        }
    };

    const getData = (first) => {
        const params = {
            'page': document.getElementById('page').value,
            'limit': document.getElementById('limit').value,
            'search': document.getElementById('search').value,
            'sort': document.getElementById('sort').value,
            'order': document.getElementById('order').value,
            'tab': tab
        }
        fetch(`/employee/getData?data=${encodeURIComponent(JSON.stringify(params))}`).then((response) => {
            return response.json();
        }).then((datas) => {
            tbody.innerHTML = '';
            const data = JSON.parse(datas);
            if(data.total > 0) {
                document.querySelector('.toolbar-bottom').style.visibility = 'visible';
                loadRow(data);
                return data;
            } else {
                tbody.innerHTML = '<tr><td colspan="13" class="text-center text-danger p-4" style="font-size:20px">No data found</td></tr>';
                document.querySelector('.toolbar-bottom').style.visibility = 'hidden';
            }
        }).then((data) => {
            if(data) {
                const pages = (data.total < data.limit) ? 1 : Math.ceil(data.total / data.limit);
                document.querySelector('.pagination').innerHTML = '';
                pagination(pages, data.page, 2);
                return data;
            }
        }).then((data) => {
            if(data) {
                document.getElementById('search').value = data.search;
                document.getElementById('limit').value = data.limit;
                document.getElementById('page').value = data.page;
                document.getElementById('sort').value = data.sort;
                document.getElementById('order').value = data.order;
            }
        });
    };

    const resetpwd = (id) => {
        let text, newpass = prompt("Please enter new password:", "asc123");
        if (newpass == null || newpass == "") {
            alert('New password not change, please try again');
        } else {
            text = newpass;
            fetch(`/employee/resetpass?id=${id}&newpass=${text}`).then((response) => {
                return response.json();
            }).then((data) => {
                setTimeout(() => {
                    alert('Password has been change');
                }, 500);
            });
        };
    };

    // Employee Registration Approval Functions
    const approveEmployee = (id) => {
        if (confirm('Are you sure you want to approve this employee registration?')) {
            fetch('/employee/approve', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert(result.message);
                    loadData(); // Reload data
                } else {
                    alert('Failed to approve employee: ' + (result.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error approving employee');
            });
        }
    };

    const rejectEmployee = (id) => {
        if (confirm('Are you sure you want to reject this employee registration?')) {
            fetch('/employee/reject', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert(result.message);
                    loadData(); // Reload data
                } else {
                    alert('Failed to reject employee: ' + (result.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error rejecting employee');
            });
        }
    };

    // Event listeners
    sortBtn.forEach(element => {
        element.addEventListener('click', e => {
            const field = e.target.getAttribute('field');
            if(field) {
                sort.value = field;
                order.value = (order.value === 'ASC') ? 'DESC' : 'ASC';
                setTimeout(() => {getData(false);}, 100);
            }
        });
    });

    search.addEventListener('keyup', () => {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            page.value = 1;
            setTimeout(() => {getData(false);}, 100);
        }, 500);
    });

    // Tab switching logic
    document.getElementById('btn-current').addEventListener('click', () => {
        tab = 1;
        updateTabButtons();
        document.getElementById('approval-buttons').style.display = 'none';
        getData(false);
    });

    document.getElementById('btn-archive').addEventListener('click', () => {
        tab = 3;
        updateTabButtons();
        document.getElementById('approval-buttons').style.display = 'none';
        getData(false);
    });

    document.getElementById('btn-register').addEventListener('click', () => {
        tab = 2;
        updateTabButtons();
        document.getElementById('approval-buttons').style.display = 'flex';
        getData(false);
    });

    // Bulk approval buttons
    document.getElementById('btn-bulk-approve').addEventListener('click', () => {
        bulkApprove();
    });

    document.getElementById('btn-bulk-reject').addEventListener('click', () => {
        bulkReject();
    });

    function updateTabButtons() {
        // Reset all buttons
        document.getElementById('btn-current').className = 'btn btn-sm btn-secondary ms-5';
        document.getElementById('btn-archive').className = 'btn btn-sm btn-secondary ms-1';
        document.getElementById('btn-register').className = 'btn btn-sm btn-secondary ms-1';
        
        // Set active button
        if (tab === 1) {
            document.getElementById('btn-current').className = 'btn btn-sm btn-success ms-5';
        } else if (tab === 2) {
            document.getElementById('btn-register').className = 'btn btn-sm btn-success ms-1';
        } else if (tab === 3) {
            document.getElementById('btn-archive').className = 'btn btn-sm btn-success ms-1';
        }
    }

    function bulkApprove() {
        const selectedIds = getSelectedEmployeeIds();
        if (selectedIds.length === 0) {
            alert('Please select employees to approve');
            return;
        }

        if (confirm(`Are you sure you want to approve ${selectedIds.length} employee registrations?`)) {
            fetch('/employee/bulk-approve', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ ids: selectedIds })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert(result.message);
                    getData(false); // Reload data
                } else {
                    alert('Failed to approve employees: ' + (result.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error approving employees');
            });
        }
    }

    function bulkReject() {
        const selectedIds = getSelectedEmployeeIds();
        if (selectedIds.length === 0) {
            alert('Please select employees to reject');
            return;
        }

        if (confirm(`Are you sure you want to reject ${selectedIds.length} employee registrations?`)) {
            fetch('/employee/bulk-reject', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ ids: selectedIds })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert(result.message);
                    getData(false); // Reload data
                } else {
                    alert('Failed to reject employees: ' + (result.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error rejecting employees');
            });
        }
    }

    function getSelectedEmployeeIds() {
        // Get all checked checkboxes
        const checkboxes = document.querySelectorAll('input[type="checkbox"][name="employee_ids"]:checked');
        return Array.from(checkboxes).map(cb => cb.value);
    }
    
    // Select all checkbox functionality
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('input[type="checkbox"][name="employee_ids"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    limit.addEventListener('change', e => {
        limit.value = parseInt(e.target.value);
        page.value = 1;
        setTimeout(() => {getData(false);}, 100);
    });

    btnAdd.addEventListener('click', () => {
        modalEmployee();
        setTimeout(() => {
            const myModal = new bootstrap.Modal(document.querySelector('#modal-table')),
                myModalEl = document.getElementById('modal-table');
            myModal.show();
            myModalEl.addEventListener('hidden.bs.modal', () => {
                myModalEl.remove();
            });
        }, 100);
    });

    // Tab switching
    btnCur.addEventListener('click', e => {
        btnReg.classList.remove('btn-success');
        btnReg.classList.add('btn-secondary');
        btnArc.classList.remove('btn-success');
        btnArc.classList.add('btn-secondary');
        e.target.classList.remove('btn-secondary');
        e.target.classList.add('btn-success');
        tab = 1;
        getCombo();
    });
    
    btnReg.addEventListener('click', e => {
        btnCur.classList.remove('btn-success');
        btnCur.classList.add('btn-secondary');
        btnArc.classList.remove('btn-success');
        btnArc.classList.add('btn-secondary');
        e.target.classList.remove('btn-secondary');
        e.target.classList.add('btn-success');
        tab = 2;
        getCombo();
    });
    
    btnArc.addEventListener('click', e => {
        btnCur.classList.remove('btn-success');
        btnCur.classList.add('btn-secondary');
        btnReg.classList.remove('btn-success');
        btnReg.classList.add('btn-secondary');
        e.target.classList.remove('btn-secondary');
        e.target.classList.add('btn-success');
        tab = 3;
        getCombo();
    });

    // Initialize on page load
    window.addEventListener('load', () => {
        getCombo();
        setMaxDateToToday();
    });

    // Set max date for all date inputs to today
    function setMaxDateToToday() {
        const today = new Date().toISOString().split('T')[0];
        document.querySelectorAll('input[type="date"]').forEach(input => {
            input.setAttribute('max', today);
        });
    }
    
    // Modal function for employee
    function modalEmployee(id = null) {
        let modalContent = `
            <div class="modal fade" id="modal-table" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white py-2">
                            <h1 class="modal-title fs-5 text-capitalize" id="modalLabel">${id ? 'Edit' : 'Add'} Employee</h1>
                            <i data-bs-dismiss="modal" class="bi bi-x-lg" style="font-size: 20px; cursor: pointer;"></i>
                        </div>
                        <form id="form-modal" class="needs-validation" novalidate>
                            <div class="modal-body">
                                <input type="hidden" id="id" value="${id || ''}">
                                <div class="row mb-2">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label mb-1">Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" required>
                                        <div class="invalid-feedback">Please provide a valid name.</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="username" class="form-label mb-1">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="username" required>
                                        <div class="invalid-feedback">Please provide a valid email.</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label mb-1">Password ${id ? '' : '<span class="text-danger">*</span>'}</label>
                                        <input type="password" class="form-control" id="password" ${id ? '' : 'required'}>
                                        <div class="invalid-feedback">Please provide a valid password.</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="join_date" class="form-label mb-1">Join Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="join_date" max="{{ date('Y-m-d') }}" required>
                                        <div class="invalid-feedback">Please provide a valid join date.</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="division_id" class="form-label mb-1">Division <span class="text-danger">*</span></label>
                                        <select class="form-control" id="division_id" required>
                                            <option value="">Choose Division</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a division.</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="sub_division_id" class="form-label mb-1">Sub Division <span class="text-danger">*</span></label>
                                        <select class="form-control" id="sub_division_id" required>
                                            <option value="">Choose Sub Division</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a sub division.</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="role_id" class="form-label mb-1">Role <span class="text-danger">*</span></label>
                                        <select class="form-control" id="role_id" required>
                                            <option value="">Choose Role</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a role.</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="position_id" class="form-label mb-1">Position <span class="text-danger">*</span></label>
                                        <select class="form-control" id="position_id" required>
                                            <option value="">Choose Position</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a position.</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="superior_id" class="form-label mb-1">Supervisor</label>
                                        <select class="form-control" id="superior_id">
                                            <option value="">Choose Supervisor</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="description" class="form-label mb-1">Description</label>
                                        <textarea class="form-control" id="description" rows="3"></textarea>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_admin">
                                            <label class="form-check-label" for="is_admin">
                                                Administrator
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="can_approve">
                                            <label class="form-check-label" for="can_approve">
                                                Can Approve
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="cutoff_exception">
                                            <label class="form-check-label" for="cutoff_exception">
                                                Cut Off Exception
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-center align-items-center p-1">
                                <button type="button" id="submit-form" class="btn btn-primary btn-sm" style="width:100px">
                                    <i class="bi bi-save pe-2"></i>SUBMIT
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>`;
        
        document.querySelector('body').insertAdjacentHTML('beforeend', modalContent);
        
        // Load combo data
        loadComboForModal();
        
        // Load data if editing
        if (id) {
            loadEmployeeData(id);
        }
        
        // Set max date to today
        setMaxDateToToday();
        
        // Setup form submission
        setupEmployeeFormSubmission(id);
    }

    function loadComboForModal() {
        fetch('/employee/getCombo')
            .then(response => response.json())
            .then(datas => {
                const data = JSON.parse(datas);
                
                // Load divisions
                const divisionSelect = document.getElementById('division_id');
                divisionSelect.innerHTML = '<option value="">Choose Division</option>';
                data.division.forEach(div => {
                    divisionSelect.innerHTML += `<option value="${div.id}">${div.title}</option>`;
                });
                
                // Load sub divisions
                const subDivisionSelect = document.getElementById('sub_division_id');
                subDivisionSelect.innerHTML = '<option value="">Choose Sub Division</option>';
                data.subdivision.forEach(sub => {
                    subDivisionSelect.innerHTML += `<option value="${sub.id}">${sub.title}</option>`;
                });
                
                // Load roles
                const roleSelect = document.getElementById('role_id');
                roleSelect.innerHTML = '<option value="">Choose Role</option>';
                data.role.forEach(role => {
                    roleSelect.innerHTML += `<option value="${role.id}">${role.title}</option>`;
                });
                
                // Load positions
                const positionSelect = document.getElementById('position_id');
                positionSelect.innerHTML = '<option value="">Choose Position</option>';
                data.position.forEach(pos => {
                    positionSelect.innerHTML += `<option value="${pos.id}">${pos.title}</option>`;
                });
                
                // Load supervisors
                const superiorSelect = document.getElementById('superior_id');
                superiorSelect.innerHTML = '<option value="">Choose Supervisor</option>';
                data.parent.forEach(emp => {
                    superiorSelect.innerHTML += `<option value="${emp.id}">${emp.title}</option>`;
                });
            });
    }

    function loadEmployeeData(id) {
        fetch(`/employee/${id}`)
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    const data = result.data;
                    document.getElementById('name').value = data.name || '';
                    document.getElementById('username').value = data.username || '';
                    document.getElementById('join_date').value = data.join_date || '';
                    document.getElementById('division_id').value = data.division_id || '';
                    document.getElementById('sub_division_id').value = data.sub_division_id || '';
                    document.getElementById('role_id').value = data.role_id || '';
                    document.getElementById('position_id').value = data.position_id || '';
                    document.getElementById('superior_id').value = data.superior_id || '';
                    document.getElementById('description').value = data.description || '';
                    document.getElementById('is_admin').checked = data.is_admin || false;
                    document.getElementById('can_approve').checked = data.can_approve || false;
                    document.getElementById('cutoff_exception').checked = data.cutoff_exception || false;
                }
            })
            .catch(error => {
                console.error('Error loading employee data:', error);
            });
    }

    function setupEmployeeFormSubmission(id) {
        document.getElementById('submit-form').addEventListener('click', function() {
            const form = document.getElementById('form-modal');
            
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }
            
            const data = {
                name: document.getElementById('name').value,
                username: document.getElementById('username').value,
                password: document.getElementById('password').value,
                join_date: document.getElementById('join_date').value,
                division_id: document.getElementById('division_id').value,
                sub_division_id: document.getElementById('sub_division_id').value,
                role_id: document.getElementById('role_id').value,
                position_id: document.getElementById('position_id').value,
                superior_id: document.getElementById('superior_id').value,
                description: document.getElementById('description').value,
                is_admin: document.getElementById('is_admin').checked,
                can_approve: document.getElementById('can_approve').checked,
                cutoff_exception: document.getElementById('cutoff_exception').checked
            };
            
            const url = id ? `/employee/${id}` : '/employee';
            const method = id ? 'PUT' : 'POST';
            
            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(async response => {
                if (response.status === 422) {
                    const result = await response.json();
                    let errorMessage = 'Validation Error:\n';
                    if (result.errors) {
                        Object.keys(result.errors).forEach(key => {
                            errorMessage += `- ${result.errors[key][0]}\n`;
                        });
                    } else {
                        errorMessage += result.message || 'Unknown validation error';
                    }
                    throw new Error(errorMessage);
                }

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(result => {
                if (result.success) {
                    alert('Employee saved successfully');
                    bootstrap.Modal.getInstance(document.getElementById('modal-table')).hide();
                    getData();
                } else {
                    let errorMessage = 'Error saving employee';
                    if (result.message) {
                        errorMessage += ': ' + result.message;
                    }
                    if (result.errors) {
                        errorMessage += '\nValidation errors: ' + JSON.stringify(result.errors);
                    }
                    alert(errorMessage);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again. Error: ' + error.message);
            });
        });
    }

    // Pagination function
    function pagination(pages, currentPage, maxVisible) {
        const pagination = document.querySelector('.pagination');
        let html = '';
        
        // Previous button
        if (currentPage > 1) {
            html += `<li class="page-item"><a class="page-link" style="cursor: pointer">${currentPage - 1}</a></li>`;
        }
        
        // Current page
        html += `<li class="page-item active"><a class="page-link" style="cursor: pointer">${currentPage}</a></li>`;
        
        // Next button
        if (currentPage < pages) {
            html += `<li class="page-item"><a class="page-link" style="cursor: pointer">${currentPage + 1}</a></li>`;
        }
        
        pagination.innerHTML = html;
        
        // Add event listeners to pagination links
        document.querySelectorAll('.pagination a').forEach(element => {
            element.addEventListener('click', e => {
                page.value = parseInt(e.target.textContent);
                setTimeout(() => {getData(false);}, 200);
            });
        });
    }
</script>
@endsection
