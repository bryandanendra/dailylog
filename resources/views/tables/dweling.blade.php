@extends('layouts.app')

@section('title', 'Dwelling - Daily Log System')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="pagetitle">
    <h1 class="text-capitalize">Dwelling</h1>
</div>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item">Tables</li>
        <li class="breadcrumb-item active">Dwelling</li>
    </ol>
</nav>

<section class="section">
    <div class="row">
        <div class="card">
            <div class="card-body pt-3">
                <h4 class="text-capitalize mb-4">Manage Dwelling</h4>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex gap-2">
                        <button type="button" id="btn-add" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i>&nbsp;Add Dwelling</button>
                    </div>
                    <div class="search-bar">
                        <div class="search-data d-flex align-items-center">
                            <input type="text" id="search" class="search" placeholder="Search Dwelling" title="Enter search keyword">
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
                            <th>Dwelling Name</th>
                            <th style="width: 200px;">Description</th>
                          </tr>
                        </thead>
                        <tbody id="table-body">
                            <tr>
                                <td colspan="4" class="text-center">Loading...</td>
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
                <input type="hidden" id="sort" value="title">
                <input type="hidden" id="order" value="asc">
            </div>
        </div>
    </div>
</section>

<!-- Add/Edit Modal -->
<div class="modal fade" id="dwelingModal" tabindex="-1" aria-labelledby="dwelingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dwelingModalLabel">Add Dwelling</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="dwelingForm">
                    <input type="hidden" id="dweling-id">
                    <div class="mb-3">
                        <label for="dweling-title" class="form-label">Dwelling Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="dweling-title" required>
                    </div>
                    <div class="mb-3">
                        <label for="dweling-description" class="form-label">Description</label>
                        <textarea class="form-control" id="dweling-description" rows="3"></textarea>
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
    let currentSort = 'title';
    let currentOrder = 'asc';

    // Load data
    function loadData() {
        const params = {
            page: currentPage,
            limit: currentLimit,
            search: currentSearch,
            sort: currentSort,
            order: currentOrder
        };

        fetch(`{{ url('/dweling/getData') }}?data=${encodeURIComponent(JSON.stringify(params))}`)
            .then(response => response.json())
            .then(data => {
                renderTable(data.rows);
                renderPagination(data.total, data.limit, data.page);
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('table-body').innerHTML = '<tr><td colspan="4" class="text-center text-danger">Error loading data</td></tr>';
            });
    }

    // Render table
    function renderTable(dwelings) {
        const tbody = document.getElementById('table-body');
        if (!dwelings || dwelings.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center">No data found</td></tr>';
            return;
        }

        tbody.innerHTML = dwelings.map((div, index) => `
            <tr>
                <td class="text-center">
                    <button class="btn btn-sm btn-warning btn-edit" data-id="${div.id}" data-title="${div.title}" data-description="${div.description || ''}">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-delete" data-id="${div.id}">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
                <td class="text-center">${(currentPage - 1) * currentLimit + index + 1}</td>
                <td>${div.title}</td>
                <td>${div.description || '-'}</td>
            </tr>
        `).join('');

        // Add event listeners
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                editDwelling(this.dataset.id, this.dataset.title, this.dataset.description);
            });
        });

        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                deleteDwelling(this.dataset.id);
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
        const maxVisible = 7; // Show max 7 page buttons
        
        // Previous button
        if (currentPg > 1) {
            html += `<li class="page-item">
                <a class="page-link" style="cursor: pointer" data-page="${currentPg - 1}">«</a>
            </li>`;
        }
        
        // Calculate range
        let startPage = Math.max(1, currentPg - 3);
        let endPage = Math.min(totalPages, currentPg + 3);
        
        // Adjust if near boundaries
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

    // Add dweling
    document.getElementById('btn-add').addEventListener('click', function() {
        document.getElementById('dwelingModalLabel').textContent = 'Add Dwelling';
        document.getElementById('dwelingForm').reset();
        document.getElementById('dweling-id').value = '';
        new bootstrap.Modal(document.getElementById('dwelingModal')).show();
    });

    // Edit dweling
    function editDwelling(id, title, description) {
        document.getElementById('dwelingModalLabel').textContent = 'Edit Dwelling';
        document.getElementById('dweling-id').value = id;
        document.getElementById('dweling-title').value = title;
        document.getElementById('dweling-description').value = description;
        new bootstrap.Modal(document.getElementById('dwelingModal')).show();
    }

    // Save dweling
    document.getElementById('btn-save').addEventListener('click', function() {
        const id = document.getElementById('dweling-id').value;
        const title = document.getElementById('dweling-title').value;
        const description = document.getElementById('dweling-description').value;

        if (!title) {
            alert('Dwelling name is required');
            return;
        }

        const url = id ? `{{ url('/dweling') }}/${id}` : '{{ url('/dweling') }}';
        const method = id ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ title, description })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('dwelingModal')).hide();
                loadData();
                alert(data.message || 'Dwelling saved successfully');
            } else {
                alert(data.message || 'Error saving dweling');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error saving dweling');
        });
    });

    // Delete dweling
    function deleteDwelling(id) {
        if (!confirm('Are you sure you want to delete this dweling?')) {
            return;
        }

        fetch(`{{ url('/dweling') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadData();
                alert(data.message || 'Dwelling deleted successfully');
            } else {
                alert(data.message || 'Error deleting dweling');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting dweling');
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
        loadData();
    });
</script>
@endsection

