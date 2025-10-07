@extends('layouts.app')

@section('title', 'Offwork - Daily Log System')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="pagetitle">
    <h1 class="text-capitalize">Offwork</h1>
</div>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Offwork</li>
    </ol>
</nav>

<section class="section">
    <div class="row">
        <div class="card">
            <div class="card-body pt-3">
                <h4 class="text-capitalize mb-3">Manage Offwork</h4>
                <p class="text-muted mb-4">Offwork Database<br>Use This Table To Add, Remove Or Modify The Leave Record</p>
                
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex gap-2">
                        <button type="button" id="btn-add" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-lg"></i>&nbsp;Add Row
                        </button>
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
                            <th style="width: 80px;">Action</th>
                            <th style="width: 50px;">#</th>
                            <th style="width: 200px;">Title</th>
                            <th style="width: 120px;">Date</th>
                            <th style="width: 200px;">Leave</th>
                            <th style="width: 200px;">Employee</th>
                            <th>Description</th>
                          </tr>
                        </thead>
                        <tbody id="table-body">
                            <tr>
                                <td colspan="7" class="text-center">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="toolbar-bottom d-flex mt-3 align-items-center justify-content-between">
                    <div class="d-flex align-items-center border rounded" style="width:94px">
                        <div class="bg-primary px-2" title="item per page"><i class="bi bi-book-half text-white" style="font-size: 20px"></i></div>
                        <input type="number" id="limit" class="border-0 ps-1 w-100 text-primary" value="15" min="1">
                    </div>
                    <div class="d-flex">
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm m-0" id="pagination">
                                <li class="page-item active"><a class="page-link" style="cursor: pointer">1</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
                
                <input type="hidden" id="page" value="1">
                <input type="hidden" id="sort" value="date">
                <input type="hidden" id="order" value="desc">
            </div>
        </div>
    </div>
</section>

<!-- Add/Edit Modal -->
<div class="modal fade" id="offworkModal" tabindex="-1" aria-labelledby="offworkModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="offworkModalLabel">Add Offwork Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="offworkForm">
                    <input type="hidden" id="offwork-id">
                    <div class="mb-3">
                        <label for="offwork-title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="offwork-title" required>
                    </div>
                    <div class="mb-3">
                        <label for="offwork-date" class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="offwork-date" required>
                    </div>
                    <div class="mb-3">
                        <label for="offwork-leave-type" class="form-label">Leave Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="offwork-leave-type" required>
                            <option value="">Choose...</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="offwork-employee" class="form-label">Employee</label>
                        <select class="form-select" id="offwork-employee">
                            <option value="">Choose...</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="offwork-description" class="form-label">Description</label>
                        <textarea class="form-control" id="offwork-description" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btn-save">Save</button>
            </div>
        </div>
    </div>
</div>

<style>
    .search-bar {
        min-width: 300px;
    }
    
    .search-data {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        overflow: hidden;
    }
    
    .search-data input {
        border: none;
        padding: 0.375rem 0.75rem;
        outline: none;
        flex: 1;
    }
    
    .search-data button {
        background: #0d6efd;
        color: white;
        border: none;
        padding: 0.375rem 0.75rem;
        cursor: pointer;
    }
    
    .search-data button:hover {
        background: #0b5ed7;
    }
</style>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let currentPage = 1;
    let currentLimit = 15;
    let currentSearch = '';
    let currentSort = 'date';
    let currentOrder = 'desc';
    let employees = [];
    let leaveTypes = [];

    // Load data
    function loadData() {
        const params = {
            page: currentPage,
            limit: currentLimit,
            search: currentSearch,
            sort: currentSort,
            order: currentOrder
        };

        fetch(`{{ url('/offwork/getData') }}?data=${encodeURIComponent(JSON.stringify(params))}`)
            .then(response => response.json())
            .then(data => {
                renderTable(data.rows);
                renderPagination(data.total, data.limit, data.page);
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('table-body').innerHTML = '<tr><td colspan="7" class="text-center text-danger">Error loading data</td></tr>';
            });
    }

    // Load employees for dropdown
    function loadEmployees() {
        fetch('{{ url('/offwork/getEmployees') }}')
            .then(response => response.json())
            .then(data => {
                employees = data;
                const select = document.getElementById('offwork-employee');
                select.innerHTML = '<option value="">Choose...</option>' + 
                    data.map(emp => `<option value="${emp.id}">${emp.name}</option>`).join('');
            })
            .catch(error => console.error('Error loading employees:', error));
    }

    // Load leave types for dropdown
    function loadLeaveTypes() {
        fetch('{{ url('/offwork/getLeaveTypes') }}')
            .then(response => response.json())
            .then(data => {
                leaveTypes = data;
                const select = document.getElementById('offwork-leave-type');
                select.innerHTML = '<option value="">Choose...</option>' + 
                    data.map(type => `<option value="${type}">${type}</option>`).join('');
            })
            .catch(error => console.error('Error loading leave types:', error));
    }

    // Render table
    function renderTable(leaves) {
        const tbody = document.getElementById('table-body');
        if (!leaves || leaves.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center">No leave records found. Click "Add Row" to create one.</td></tr>';
            return;
        }

        tbody.innerHTML = leaves.map((leave, index) => `
            <tr>
                <td class="text-center">
                    <i class="bi bi-pencil-square text-info btn-edit" style="font-size: 18px; cursor: pointer; margin: 0 2px;" 
                       data-id="${leave.id}" data-title="${leave.title}" 
                       data-date="${leave.date.split('/').reverse().join('-')}" data-leave-type="${leave.leave_type}" 
                       data-employee-id="${leave.employee_id || ''}" data-description="${leave.description || ''}" 
                       title="Edit"></i>
                    <i class="bi bi-x-square text-danger btn-delete" style="font-size: 18px; cursor: pointer; margin: 0 2px;" 
                       data-id="${leave.id}" title="Delete"></i>
                </td>
                <td class="text-center">${(currentPage - 1) * currentLimit + index + 1}</td>
                <td>${leave.title}</td>
                <td>${leave.date}</td>
                <td>${leave.leave_type}</td>
                <td>${leave.employee_name}</td>
                <td>${leave.description || '-'}</td>
            </tr>
        `).join('');

        // Add event listeners
        document.querySelectorAll('.btn-edit').forEach(icon => {
            icon.addEventListener('click', function() {
                editOffwork(this.dataset.id, this.dataset.title, this.dataset.date, 
                           this.dataset.leaveType, this.dataset.employeeId, this.dataset.description);
            });
        });

        document.querySelectorAll('.btn-delete').forEach(icon => {
            icon.addEventListener('click', function() {
                deleteOffwork(this.dataset.id);
            });
        });
    }

    // Render pagination
    function renderPagination(total, perPage, currentPg) {
        const totalPages = Math.ceil(total / perPage);
        const pagination = document.getElementById('pagination');
        
        if (totalPages <= 1) {
            pagination.innerHTML = '<li class="page-item active"><a class="page-link" style="cursor: pointer">1</a></li>';
            return;
        }
        
        let html = '';
        const maxVisible = 7;
        
        // Previous button
        if (currentPg > 1) {
            html += `<li class="page-item">
                <a class="page-link" style="cursor: pointer" data-page="${currentPg - 1}">«</a>
            </li>`;
        }
        
        // Calculate range
        let startPage = Math.max(1, currentPg - 3);
        let endPage = Math.min(totalPages, currentPg + 3);
        
        if (currentPg <= 4) {
            endPage = Math.min(maxVisible, totalPages);
        }
        if (currentPg >= totalPages - 3) {
            startPage = Math.max(1, totalPages - maxVisible + 1);
        }
        
        // First page + ellipsis
        if (startPage > 1) {
            html += `<li class="page-item">
                <a class="page-link" style="cursor: pointer" data-page="1">1</a>
            </li>`;
            if (startPage > 2) {
                html += `<li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>`;
            }
        }
        
        // Page numbers
        for (let i = startPage; i <= endPage; i++) {
            html += `<li class="page-item ${i === currentPg ? 'active' : ''}">
                <a class="page-link" style="cursor: pointer" data-page="${i}">${i}</a>
            </li>`;
        }
        
        // Last page + ellipsis
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                html += `<li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>`;
            }
            html += `<li class="page-item">
                <a class="page-link" style="cursor: pointer" data-page="${totalPages}">${totalPages}</a>
            </li>`;
        }
        
        // Next button
        if (currentPg < totalPages) {
            html += `<li class="page-item">
                <a class="page-link" style="cursor: pointer" data-page="${currentPg + 1}">»</a>
            </li>`;
        }
        
        pagination.innerHTML = html;
        
        // Add event listeners
        document.querySelectorAll('.page-link').forEach(link => {
            if (link.dataset.page) {
                link.addEventListener('click', function() {
                    currentPage = parseInt(this.dataset.page);
                    loadData();
                });
            }
        });
    }

    // Add offwork
    document.getElementById('btn-add').addEventListener('click', function() {
        document.getElementById('offworkModalLabel').textContent = 'Add Offwork Record';
        document.getElementById('offworkForm').reset();
        document.getElementById('offwork-id').value = '';
        // Set default date to today
        document.getElementById('offwork-date').value = new Date().toISOString().split('T')[0];
        new bootstrap.Modal(document.getElementById('offworkModal')).show();
    });

    // Edit offwork
    function editOffwork(id, title, date, leaveType, employeeId, description) {
        document.getElementById('offworkModalLabel').textContent = 'Edit Offwork Record';
        document.getElementById('offwork-id').value = id;
        document.getElementById('offwork-title').value = title;
        document.getElementById('offwork-date').value = date;
        document.getElementById('offwork-leave-type').value = leaveType;
        document.getElementById('offwork-employee').value = employeeId;
        document.getElementById('offwork-description').value = description;
        new bootstrap.Modal(document.getElementById('offworkModal')).show();
    }

    // Save offwork
    document.getElementById('btn-save').addEventListener('click', function() {
        const id = document.getElementById('offwork-id').value;
        const title = document.getElementById('offwork-title').value;
        const date = document.getElementById('offwork-date').value;
        const leaveType = document.getElementById('offwork-leave-type').value;
        const employeeId = document.getElementById('offwork-employee').value;
        const description = document.getElementById('offwork-description').value;

        if (!title || !date || !leaveType) {
            alert('Title, Date, and Leave Type are required');
            return;
        }

        const url = id ? `{{ url('/offwork') }}/${id}` : '{{ url('/offwork') }}';
        const method = id ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ 
                title, 
                date, 
                leave_type: leaveType,
                employee_id: employeeId || null,
                description 
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('offworkModal')).hide();
                loadData();
                alert(data.message || 'Offwork record saved successfully');
            } else {
                alert(data.message || 'Error saving offwork record');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error saving offwork record');
        });
    });

    // Delete offwork
    function deleteOffwork(id) {
        if (!confirm('Are you sure you want to delete this offwork record?')) {
            return;
        }

        fetch(`{{ url('/offwork') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadData();
                alert(data.message || 'Offwork record deleted successfully');
            } else {
                alert(data.message || 'Error deleting offwork record');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting offwork record');
        });
    }

    // Search
    document.getElementById('btnSearch').addEventListener('click', function() {
        currentSearch = document.getElementById('search').value;
        currentPage = 1;
        loadData();
    });

    document.getElementById('search').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('btnSearch').click();
        }
    });

    // Limit change
    document.getElementById('limit').addEventListener('change', function() {
        currentLimit = parseInt(this.value);
        currentPage = 1;
        loadData();
    });

    // Initial load
    document.addEventListener('DOMContentLoaded', function() {
        loadEmployees();
        loadLeaveTypes();
        loadData();
    });
</script>
@endsection

