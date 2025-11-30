<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Daily Log System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #e3f2fd;
            width: 200px;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s;
        }
        .sidebar.collapsed {
            width: 50px;
        }
        .sidebar.collapsed nav {
            display: none;
        }
        .sidebar.collapsed .sidebar-toggle-btn {
            display: flex !important;
            justify-content: center;
            margin: 0;
            padding: 12px 0;
        }
        .sidebar.hidden {
            transform: translateX(-100%);
        }
        .sidebar .nav-link {
            color: #1976d2;
            padding: 8px 16px;
            border-radius: 0;
            transition: all 0.3s;
            font-size: 0.85rem;
        }
        .sidebar .nav-link:hover {
            background-color: #bbdefb;
            color: #0d47a1;
        }
        .sidebar .nav-link.active {
            background-color: #2196f3;
            color: white;
        }
        .sidebar .nav-link i {
            width: 16px;
            margin-right: 8px;
            font-size: 0.9rem;
        }
        .sidebar .nav .nav {
            margin-left: 0;
        }
        .sidebar .nav .nav .nav-link {
            padding: 6px 12px;
            font-size: 0.8rem;
        }
        .sidebar .nav .nav .nav-link i {
            width: 12px;
            margin-right: 6px;
            font-size: 0.8rem;
        }
        .main-content {
            margin-left: 200px;
            margin-top: 70px;
            transition: all 0.3s;
        }
        .main-content.expanded {
            margin-left: 50px;
        }
        .main-content.sidebar-hidden {
            margin-left: 0;
        }
        .header {
            background-color: #1976d2;
            color: white;
            padding: 15px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
            transition: all 0.3s;
        }
        .header.sidebar-hidden {
            left: 0;
        }
        .logo {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        .logo img {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }
        .toggle-sidebar-btn {
            font-size: 1.5rem;
            cursor: pointer;
            color: white;
        }
        .search-bar {
            flex: 1;
            max-width: 400px;
            margin: 0 20px;
        }
        .header-nav {
            display: flex;
            align-items: center;
        }
        .header-nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .header-nav .nav-item {
            position: relative;
        }
        .nav-icon {
            position: relative;
            color: white;
            font-size: 1.3rem;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: background-color 0.2s;
            border-radius: 4px;
        }
        .nav-icon:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        .badge-number {
            position: absolute;
            top: 2px;
            right: 4px;
            font-size: 0.65rem;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2px 5px;
            border-radius: 10px;
        }
        .nav-profile {
            color: white;
            text-decoration: none;
            padding: 4px 8px;
            border-radius: 4px;
            transition: background-color 0.2s;
        }
        .nav-profile:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        .nav-profile img {
            width: 36px;
            height: 36px;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        /* Notification Dropdown */
        .dropdown-menu.notifications {
            min-width: 320px;
            max-width: 400px;
            padding: 0;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1050;
            position: absolute !important;
            top: 100% !important;
            right: 0 !important;
            margin-top: -8px !important;
        }
        .dropdown-menu.notifications:not(.show) {
            display: none !important;
        }
        .dropdown-menu.notifications .dropdown-header {
            padding: 12px 16px;
            border-bottom: 1px solid #e0e0e0;
            background-color: #f8f9fa;
            font-size: 0.9rem;
        }
        .dropdown-menu.notifications #notif-list {
            max-height: 400px;
            overflow-y: auto;
        }
        .dropdown-menu.notifications .notification-item {
            padding: 12px 16px;
            border-bottom: 1px solid #e0e0e0;
            transition: background-color 0.2s;
        }
        .dropdown-menu.notifications .notification-item:hover {
            background-color: #f5f5f5;
        }
        .dropdown-menu.notifications .notification-item h6 {
            margin: 0 0 4px 0;
            font-size: 0.9rem;
        }
        .dropdown-menu.notifications .notification-item p {
            margin: 0;
            font-size: 0.85rem;
            line-height: 1.4;
        }
        /* Profile Dropdown */
        .dropdown-menu.profile {
            min-width: 280px;
            max-width: 320px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1050;
            position: absolute !important;
            top: 100% !important;
            right: 0 !important;
            margin-top: -4px !important;
        }
        .dropdown-menu.profile:not(.show) {
            display: none !important;
        }
        .dropdown-menu.profile .dropdown-header {
            padding: 16px;
            border-bottom: 1px solid #e0e0e0;
            background-color: #f8f9fa;
        }
        .dropdown-menu.profile .dropdown-header h6 {
            margin: 0 0 8px 0;
            font-size: 1rem;
            font-weight: 600;
            color: #333;
        }
        .dropdown-menu.profile .dropdown-header div {
            font-size: 0.85rem;
            line-height: 1.6;
            color: #666;
        }
        .dropdown-menu.profile .dropdown-header div span {
            display: block;
            margin-bottom: 2px;
        }
        .content-area {
            padding: 20px;
            background-color: #f8f9fa;
            min-height: calc(100vh - 140px);
        }
        .main-content {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .breadcrumb {
            background: none;
            padding: 0;
            margin-bottom: 20px;
        }
        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
        }
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #f44336;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        body {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="p-2">
            <div class="d-flex align-items-center justify-content-center mb-3">
                <button class="btn btn-link p-0 text-primary sidebar-toggle-btn" id="sidebar-toggle-inner" title="Toggle Sidebar" style="font-size: 1.3rem; line-height: 1;">
                    <i class="bi bi-list"></i>
                </button>
            </div>
        </div>
        
        <nav class="nav flex-column">
            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i>
                <span class="nav-text">Dashboard</span>
            </a>
            
            <a class="nav-link {{ request()->is('log*') ? 'active' : '' }}" href="{{ route('log.index') }}">
                <i class="bi bi-journals"></i>
                <span class="nav-text">Daily Log</span>
            </a>
            
            <div class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#reports-nav" role="button">
                    <i class="bi bi-pie-chart"></i>
                    <span class="nav-text">Reports</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="reports-nav">
                    <div class="nav flex-column ms-3">
                        @if(hasReportAccess('monthly'))
                        <a class="nav-link" href="{{ route('report.monthly') }}">
                            <i class="bi bi-circle-fill"></i>
                            <span class="nav-text">Monthly Report</span>
                        </a>
                        @endif
                        @if(hasReportAccess('bi'))
                        <a class="nav-link" href="{{ route('report.bi') }}">
                            <i class="bi bi-circle-fill"></i>
                            <span class="nav-text">BI Report</span>
                        </a>
                        @endif
                        @if(hasReportAccess('spdr'))
                        <a class="nav-link" href="{{ route('report.spdr') }}">
                            <i class="bi bi-circle-fill"></i>
                            <span class="nav-text">SPDR Report</span>
                        </a>
                        @endif
                        @if(hasReportAccess('category'))
                        <a class="nav-link" href="{{ route('report.category') }}">
                            <i class="bi bi-circle-fill"></i>
                            <span class="nav-text">Category Report</span>
                        </a>
                        @endif
                        @if(hasReportAccess('tma'))
                        <a class="nav-link" href="{{ route('report.tma') }}">
                            <i class="bi bi-circle-fill"></i>
                            <span class="nav-text">TMA Report</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            
            @php
                $user = Auth::user();
                $employee = \App\Models\Employee::where('email', $user->email)->first();
                $canApprove = $employee ? $employee->can_approve : $user->can_approve;
            @endphp
            @if($canApprove)
            <a class="nav-link {{ request()->is('approved*') ? 'active' : '' }}" href="{{ route('approval.index') }}">
                <i class="bi bi-check-square"></i>
                <span class="nav-text">Approval</span>
            </a>
            @endif
            
            <div class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#offwork-nav" role="button">
                    <i class="bi bi-receipt-cutoff"></i>
                    <span class="nav-text">Off Work</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="offwork-nav">
                    <div class="nav flex-column ms-3">
                        <a class="nav-link" href="{{ route('offwork.index') }}">
                            <i class="bi bi-receipt-cutoff"></i>
                            <span class="nav-text">Offwork</span>
                        </a>
                        <a class="nav-link" href="{{ route('offwork.leave') }}">
                            <i class="bi bi-receipt-cutoff"></i>
                            <span class="nav-text">Leave</span>
                        </a>
                        <a class="nav-link" href="{{ route('offwork.holiday') }}">
                            <i class="bi bi-receipt-cutoff"></i>
                            <span class="nav-text">Holidays</span>
                        </a>
                    </div>
                </div>
            </div>
            
            @if(Auth::user()->is_admin)
            <a class="nav-link {{ request()->is('employee*') ? 'active' : '' }}" href="{{ route('employee.index') }}">
                <i class="bi bi-person-square"></i>
                <span class="nav-text">Employees</span>
            </a>
            @endif
            
            <a class="nav-link {{ request()->is('backup*') ? 'active' : '' }}" href="{{ route('backup.index') }}">
                <i class="bi bi-database"></i>
                <span class="nav-text">Backup</span>
            </a>
            
            <a class="nav-link {{ request()->is('cutoff*') ? 'active' : '' }}" href="{{ route('cutoff.index') }}">
                <i class="bi bi-gear-fill"></i>
                <span class="nav-text">Time Cut Off</span>
            </a>
            
            {{-- User Guide link disabled --}}
            {{-- <a class="nav-link {{ request()->is('manual*') ? 'active' : '' }}" href="{{ route('manual.index') }}">
                <i class="bi bi-book"></i>
                <span class="nav-text">User Guide</span>
            </a> --}}
            
            <div class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#tables-nav" role="button">
                    <i class="bi bi-table"></i>
                    <span class="nav-text">Tables</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="tables-nav">
                    <div class="nav flex-column ms-3">
                        <a class="nav-link" href="/tables/division">
                            <i class="bi bi-circle"></i>
                            <span class="nav-text">Division</span>
                        </a>
                        <a class="nav-link" href="/tables/subdivision">
                            <i class="bi bi-circle"></i>
                            <span class="nav-text">Sub Division</span>
                        </a>
                        <a class="nav-link" href="/tables/role">
                            <i class="bi bi-circle"></i>
                            <span class="nav-text">Role</span>
                        </a>
                        <a class="nav-link" href="/tables/position">
                            <i class="bi bi-circle"></i>
                            <span class="nav-text">Position</span>
                        </a>
                        <a class="nav-link" href="/tables/category">
                            <i class="bi bi-circle"></i>
                            <span class="nav-text">Category</span>
                        </a>
                        <a class="nav-link" href="/tables/task">
                            <i class="bi bi-circle"></i>
                            <span class="nav-text">Task</span>
                        </a>
                        <a class="nav-link" href="/tables/builder">
                            <i class="bi bi-circle"></i>
                            <span class="nav-text">Builder</span>
                        </a>
                        <a class="nav-link" href="/tables/dweling">
                            <i class="bi bi-circle"></i>
                            <span class="nav-text">Dweling</span>
                        </a>
                        <a class="nav-link" href="/tables/status">
                            <i class="bi bi-circle"></i>
                            <span class="nav-text">Status</span>
                        </a>
                        <a class="nav-link" href="/tables/wtime">
                            <i class="bi bi-circle"></i>
                            <span class="nav-text">Work Status</span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <!-- Header -->
    @include('layouts.header')

    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Content Area -->
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Inner sidebar toggle button (inside sidebar)
        const innerToggle = document.getElementById('sidebar-toggle-inner');
        if (innerToggle) {
            innerToggle.addEventListener('click', function() {
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.getElementById('main-content');
                
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            });
        }

        // Hide/Show sidebar button functionality
        document.addEventListener('DOMContentLoaded', function() {
            const hideBtn = document.getElementById('sidebar-hide-btn');
            if (hideBtn) {
                hideBtn.addEventListener('click', function() {
                    const sidebar = document.getElementById('sidebar');
                    const mainContent = document.getElementById('main-content');
                    const header = document.getElementById('header');
                    const hideIcon = hideBtn.querySelector('i');
                    
                    if (sidebar.classList.contains('hidden')) {
                        // Show sidebar
                        sidebar.classList.remove('hidden');
                        mainContent.classList.remove('sidebar-hidden');
                        if (header) header.classList.remove('sidebar-hidden');
                        hideIcon.className = 'bi bi-chevron-left';
                        hideBtn.title = 'Hide Sidebar';
                    } else {
                        // Hide sidebar
                        sidebar.classList.add('hidden');
                        mainContent.classList.add('sidebar-hidden');
                        if (header) header.classList.add('sidebar-hidden');
                        hideIcon.className = 'bi bi-chevron-right';
                        hideBtn.title = 'Show Sidebar';
                    }
                });
            }
        });
    </script>
</body>
</html>
