@extends('layouts.app')

@section('title', 'Holiday - Daily Log System')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="pagetitle">
    <h1 class="text-capitalize">Holiday</h1>
</div>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Holiday</li>
    </ol>
</nav>

<section class="section">
    <div class="row">
        <div class="card">
            <div class="card-body pt-3">
                <h4 class="text-capitalize mb-3">Manage Holiday</h4>
                
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex gap-2">
                        <button type="button" id="btn-add" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-lg"></i>&nbsp;Add new public holiday
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
                            <th style="width: 250px;">Title</th>
                            <th style="width: 150px;">Date</th>
                            <th>Description</th>
                          </tr>
                        </thead>
                        <tbody id="table-body">
                            <tr>
                                <td colspan="5" class="text-center">Loading...</td>
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
                <input type="hidden" id="order" value="asc">
            </div>
        </div>
    </div>
</section>

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

<!-- Add/Edit Modal -->
<div class="modal fade" id="holidayModal" tabindex="-1" aria-labelledby="holidayModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="holidayModalLabel">Add Holiday</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="holidayForm">
                    <input type="hidden" id="holiday-id">
                    <div class="mb-3">
                        <label for="holiday-title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="holiday-title" required>
                    </div>
                    <div class="mb-3">
                        <label for="holiday-date" class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="holiday-date" required>
                    </div>
                    <div class="mb-3">
                        <label for="holiday-description" class="form-label">Description</label>
                        <textarea class="form-control" id="holiday-description" rows="3"></textarea>
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

<script>
    let currentPage = 1;
    let currentLimit = 15;
    let currentSearch = '';
    let currentSort = 'date';
    let currentOrder = 'asc';
    let holidayModal;

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize modal
        holidayModal = new bootstrap.Modal(document.getElementById('holidayModal'));

        // Load data
        loadData();

        // Add button
        document.getElementById('btn-add').addEventListener('click', function() {
            document.getElementById('holidayModalLabel').textContent = 'Add Holiday';
            document.getElementById('holidayForm').reset();
            document.getElementById('holiday-id').value = '';
            holidayModal.show();
        });

        // Save button
        document.getElementById('btn-save').addEventListener('click', saveHoliday);

        // Search
        document.getElementById('btnSearch').addEventListener('click', function() {
            currentSearch = document.getElementById('search').value;
            currentPage = 1;
            loadData();
        });

        document.getElementById('search').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                currentSearch = this.value;
                currentPage = 1;
                loadData();
            }
        });

        // Limit change
        document.getElementById('limit').addEventListener('change', function() {
            currentLimit = parseInt(this.value);
            currentPage = 1;
            loadData();
        });
    });

    function loadData() {
        const params = {
            page: currentPage,
            limit: currentLimit,
            search: currentSearch,
            sort: currentSort,
            order: currentOrder
        };

        fetch(`{{ url('/offwork/holiday/getData') }}?data=${encodeURIComponent(JSON.stringify(params))}`)
            .then(response => response.json())
            .then(data => {
                renderTable(data.rows);
                renderPagination(data.total, data.limit, data.page);
            })
            .catch(error => {
                console.error('Error loading data:', error);
                document.getElementById('table-body').innerHTML = 
                    '<tr><td colspan="5" class="text-center text-danger">Error loading data</td></tr>';
            });
    }

    function renderTable(holidays) {
        const tbody = document.getElementById('table-body');
        if (!holidays || holidays.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center">No holiday records found. Click "Add new public holiday" to create one.</td></tr>';
            return;
        }

        tbody.innerHTML = holidays.map((holiday, index) => `
            <tr>
                <td class="text-center">
                    <i class="bi bi-pencil-square text-info btn-edit" style="font-size: 18px; cursor: pointer; margin: 0 2px;" 
                       data-id="${holiday.id}" data-title="${holiday.title}" 
                       data-date="${holiday.date.split('/').reverse().join('-')}" 
                       data-description="${holiday.description || ''}" 
                       title="Edit"></i>
                    <i class="bi bi-x-square text-danger btn-delete" style="font-size: 18px; cursor: pointer; margin: 0 2px;" 
                       data-id="${holiday.id}" title="Delete"></i>
                </td>
                <td class="text-center">${(currentPage - 1) * currentLimit + index + 1}</td>
                <td>${holiday.title}</td>
                <td class="editable-date" 
                    data-id="${holiday.id}"
                    data-title="${holiday.title}"
                    data-date="${holiday.date.split('/').reverse().join('-')}"
                    data-description="${holiday.description || ''}"
                    style="cursor: pointer; position: relative; display: flex; align-items: center; justify-content: space-between;">
                    <span>${holiday.date}</span>
                    <i class="bi bi-calendar3" style="color: #6c757d; font-size: 16px;"></i>
                    <input type="date" 
                           class="date-input" 
                           value="${holiday.date.split('/').reverse().join('-')}"
                           style="position: absolute; opacity: 0; pointer-events: none; width: 1px; height: 1px;">
                </td>
                <td class="editable-description" 
                    data-id="${holiday.id}" 
                    data-title="${holiday.title}"
                    data-date="${holiday.date.split('/').reverse().join('-')}"
                    contenteditable="true" 
                    style="cursor: text; min-height: 20px;"
                    title="Click to edit description">${holiday.description || 'Description'}</td>
            </tr>
        `).join('');

        // Add event listeners
        document.querySelectorAll('.btn-edit').forEach(icon => {
            icon.addEventListener('click', function() {
                editHoliday(this.dataset);
            });
        });

        document.querySelectorAll('.btn-delete').forEach(icon => {
            icon.addEventListener('click', function() {
                deleteHoliday(this.dataset.id);
            });
        });

        // Add inline date picker
        document.querySelectorAll('.editable-date').forEach(cell => {
            const dateInput = cell.querySelector('.date-input');
            
            cell.addEventListener('click', function(e) {
                // Trigger date picker
                dateInput.style.pointerEvents = 'auto';
                dateInput.style.opacity = '1';
                dateInput.style.width = 'auto';
                dateInput.style.height = 'auto';
                dateInput.focus();
                dateInput.showPicker();
            });

            dateInput.addEventListener('change', function() {
                const newDate = this.value;
                const cell = this.closest('.editable-date');
                
                // Update to database
                updateDate(
                    cell.dataset.id,
                    cell.dataset.title,
                    newDate,
                    cell.dataset.description
                );
                
                // Hide input again
                this.style.pointerEvents = 'none';
                this.style.opacity = '0';
                this.style.width = '1px';
                this.style.height = '1px';
            });

            dateInput.addEventListener('blur', function() {
                // Hide input on blur
                this.style.pointerEvents = 'none';
                this.style.opacity = '0';
                this.style.width = '1px';
                this.style.height = '1px';
            });
        });

        // Add inline editing for description
        document.querySelectorAll('.editable-description').forEach(cell => {
            let originalValue = cell.textContent;
            
            cell.addEventListener('focus', function() {
                originalValue = this.textContent;
                if (this.textContent === 'Description') {
                    this.textContent = '';
                }
            });

            cell.addEventListener('blur', function() {
                const newValue = this.textContent.trim();
                
                // If empty, restore placeholder
                if (!newValue) {
                    this.textContent = 'Description';
                    return;
                }
                
                // If changed, save to database
                if (newValue !== originalValue && originalValue !== 'Description') {
                    updateDescription(
                        this.dataset.id,
                        this.dataset.title,
                        this.dataset.date,
                        newValue
                    );
                } else if (originalValue === 'Description' && newValue) {
                    updateDescription(
                        this.dataset.id,
                        this.dataset.title,
                        this.dataset.date,
                        newValue
                    );
                }
            });

            cell.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    this.blur();
                } else if (e.key === 'Escape') {
                    this.textContent = originalValue;
                    this.blur();
                }
            });
        });
    }

    function renderPagination(total, limit, page) {
        const totalPages = Math.ceil(total / limit);
        const pagination = document.getElementById('pagination');
        
        if (totalPages <= 1) {
            pagination.innerHTML = '<li class="page-item active"><a class="page-link" style="cursor: pointer">1</a></li>';
            return;
        }

        let html = '';
        
        // Previous button
        if (page > 1) {
            html += `<li class="page-item"><a class="page-link" style="cursor: pointer" onclick="changePage(${page - 1})">‹</a></li>`;
        }

        // Smart pagination with ellipsis
        if (totalPages <= 7) {
            // Show all pages if 7 or less
            for (let i = 1; i <= totalPages; i++) {
                html += `<li class="page-item ${i === page ? 'active' : ''}">
                    <a class="page-link" style="cursor: pointer" onclick="changePage(${i})">${i}</a>
                </li>`;
            }
        } else {
            // Show first page
            html += `<li class="page-item ${page === 1 ? 'active' : ''}">
                <a class="page-link" style="cursor: pointer" onclick="changePage(1)">1</a>
            </li>`;

            if (page > 3) {
                html += `<li class="page-item disabled"><a class="page-link">...</a></li>`;
            }

            // Show pages around current page
            let start = Math.max(2, page - 1);
            let end = Math.min(totalPages - 1, page + 1);

            for (let i = start; i <= end; i++) {
                html += `<li class="page-item ${i === page ? 'active' : ''}">
                    <a class="page-link" style="cursor: pointer" onclick="changePage(${i})">${i}</a>
                </li>`;
            }

            if (page < totalPages - 2) {
                html += `<li class="page-item disabled"><a class="page-link">...</a></li>`;
            }

            // Show last page
            html += `<li class="page-item ${page === totalPages ? 'active' : ''}">
                <a class="page-link" style="cursor: pointer" onclick="changePage(${totalPages})">${totalPages}</a>
            </li>`;
        }

        // Next button
        if (page < totalPages) {
            html += `<li class="page-item"><a class="page-link" style="cursor: pointer" onclick="changePage(${page + 1})">›</a></li>`;
        }

        pagination.innerHTML = html;
    }

    function changePage(page) {
        currentPage = page;
        loadData();
    }

    function editHoliday(data) {
        document.getElementById('holidayModalLabel').textContent = 'Edit Holiday';
        document.getElementById('holiday-id').value = data.id;
        document.getElementById('holiday-title').value = data.title;
        document.getElementById('holiday-date').value = data.date;
        document.getElementById('holiday-description').value = data.description;
        holidayModal.show();
    }

    function saveHoliday() {
        const id = document.getElementById('holiday-id').value;
        const title = document.getElementById('holiday-title').value;
        const date = document.getElementById('holiday-date').value;
        const description = document.getElementById('holiday-description').value;

        if (!title || !date) {
            alert('Please fill in all required fields');
            return;
        }

        const url = id ? `{{ url('/offwork/holiday') }}/${id}` : `{{ url('/offwork/holiday') }}`;
        const method = id ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                title: title,
                date: date,
                description: description
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                holidayModal.hide();
                loadData();
            } else {
                alert(data.message || 'Error saving holiday');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error saving holiday');
        });
    }

    function deleteHoliday(id) {
        if (!confirm('Are you sure you want to delete this holiday?')) {
            return;
        }

        fetch(`{{ url('/offwork/holiday') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                loadData();
            } else {
                alert(data.message || 'Error deleting holiday');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting holiday');
        });
    }

    function updateDescription(id, title, date, description) {
        fetch(`{{ url('/offwork/holiday') }}/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                title: title,
                date: date,
                description: description
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Silently update without alert
                console.log('Description updated successfully');
            } else {
                alert(data.message || 'Error updating description');
                loadData(); // Reload to restore original value
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating description');
            loadData(); // Reload to restore original value
        });
    }

    function updateDate(id, title, date, description) {
        fetch(`{{ url('/offwork/holiday') }}/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                title: title,
                date: date,
                description: description
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Silently update without alert
                console.log('Date updated successfully');
                loadData(); // Reload to show new date
            } else {
                alert(data.message || 'Error updating date');
                loadData(); // Reload to restore original value
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating date');
            loadData(); // Reload to restore original value
        });
    }
</script>
@endsection

