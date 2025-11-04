@extends('layouts.app')

@section('title', 'Backup Data - Daily Log System')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="pagetitle">
    <h1>Database Backup</h1>
</div>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Backup</li>
    </ol>
</nav>

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<section class="section">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body pt-3">
                    <h4 class="mb-4">Export Database Tables to CSV</h4>
                    <p class="text-muted">Download data from each table as CSV files for backup purposes.</p>
                    
                    <!-- Download All Button -->
                    <!-- <div class="mb-4">
                        <a href="{{ route('backup.all') }}" class="btn btn-success btn-lg">
                            <i class="bi bi-download"></i> Download All Tables (ZIP)
                        </a>
                    </div> -->

                    <hr>

                    <!-- Master Data Tables -->
                    <div class="mb-4">
                        <h5 class="text-primary mb-3"><i class="bi bi-table"></i> Master Data Tables</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="card border-primary h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">Divisions</h6>
                                        <p class="card-text text-muted small">Export all division data</p>
                                        <a href="{{ route('backup.divisions') }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-download"></i> Download CSV
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-primary h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">Sub Divisions</h6>
                                        <p class="card-text text-muted small">Export all sub division data</p>
                                        <a href="{{ route('backup.subdivisions') }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-download"></i> Download CSV
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-primary h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">Roles</h6>
                                        <p class="card-text text-muted small">Export all role data</p>
                                        <a href="{{ route('backup.roles') }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-download"></i> Download CSV
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-primary h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">Positions</h6>
                                        <p class="card-text text-muted small">Export all position data</p>
                                        <a href="{{ route('backup.positions') }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-download"></i> Download CSV
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-primary h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">Categories</h6>
                                        <p class="card-text text-muted small">Export all category data</p>
                                        <a href="{{ route('backup.categories') }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-download"></i> Download CSV
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-primary h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">Tasks</h6>
                                        <p class="card-text text-muted small">Export all task data</p>
                                        <a href="{{ route('backup.tasks') }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-download"></i> Download CSV
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-primary h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">Builders</h6>
                                        <p class="card-text text-muted small">Export all builder data</p>
                                        <a href="{{ route('backup.builders') }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-download"></i> Download CSV
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-primary h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">Dwelings</h6>
                                        <p class="card-text text-muted small">Export all dweling data</p>
                                        <a href="{{ route('backup.dwelings') }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-download"></i> Download CSV
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-primary h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">Status</h6>
                                        <p class="card-text text-muted small">Export all status data</p>
                                        <a href="{{ route('backup.status') }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-download"></i> Download CSV
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-primary h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">Work Status</h6>
                                        <p class="card-text text-muted small">Export all work status data</p>
                                        <a href="{{ route('backup.workstatus') }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-download"></i> Download CSV
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-primary h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">Leave Types</h6>
                                        <p class="card-text text-muted small">Export all leave type data</p>
                                        <a href="{{ route('backup.leavetypes') }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-download"></i> Download CSV
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Transaction Data Tables -->
                    <div class="mb-4">
                        <h5 class="text-success mb-3"><i class="bi bi-file-earmark-text"></i> Transaction Data Tables</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="card border-success h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">Employees</h6>
                                        <p class="card-text text-muted small">Export all employee data</p>
                                        <a href="{{ route('backup.employees') }}" class="btn btn-success btn-sm">
                                            <i class="bi bi-download"></i> Download CSV
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-success h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">Daily Logs</h6>
                                        <p class="card-text text-muted small">Export all daily log data</p>
                                        <a href="{{ route('backup.logs') }}" class="btn btn-success btn-sm">
                                            <i class="bi bi-download"></i> Download CSV
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-success h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">Off Work</h6>
                                        <p class="card-text text-muted small">Export all off work data</p>
                                        <a href="{{ route('backup.offwork') }}" class="btn btn-success btn-sm">
                                            <i class="bi bi-download"></i> Download CSV
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-success h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">Holidays</h6>
                                        <p class="card-text text-muted small">Export all holiday data</p>
                                        <a href="{{ route('backup.holidays') }}" class="btn btn-success btn-sm">
                                            <i class="bi bi-download"></i> Download CSV
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- System Data Tables -->
                    <div class="mb-4">
                        <h5 class="text-info mb-3"><i class="bi bi-gear"></i> System Data Tables</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="card border-info h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">Users</h6>
                                        <p class="card-text text-muted small">Export all user data</p>
                                        <a href="{{ route('backup.users') }}" class="btn btn-info btn-sm">
                                            <i class="bi bi-download"></i> Download CSV
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-info h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">Notifications</h6>
                                        <p class="card-text text-muted small">Export all notification data</p>
                                        <a href="{{ route('backup.notifications') }}" class="btn btn-info btn-sm">
                                            <i class="bi bi-download"></i> Download CSV
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-info h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">Time Cutoff</h6>
                                        <p class="card-text text-muted small">Export time cutoff settings</p>
                                        <a href="{{ route('backup.timecutoff') }}" class="btn btn-info btn-sm">
                                            <i class="bi bi-download"></i> Download CSV
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .card-title {
        font-weight: 600;
        color: #333;
    }
</style>
@endsection
