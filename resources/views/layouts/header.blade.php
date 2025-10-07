<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <button class="btn btn-link text-white me-3" id="sidebar-hide-btn" title="Hide Sidebar">
                <i class="bi bi-chevron-left"></i>
            </button>
            <a href="/" class="logo d-flex align-items-center">
                <img src="/images/logo.png" alt="">
                <span class="d-lg-block">GMC</span>
            </a>
        </div>
        <i class="bi bi-list toggle-sidebar-btn" id="sidebar-toggle" title="Collapse Sidebar"></i>
    </div>
    <div class="search-bar">
        <form class="search-form d-flex align-items-center" method="POST" action="#">
            <!-- <input type="text" name="query" placeholder="Search" title="Enter search keyword" disabled>
            <button type="button" title="Search"><i class="bi bi-search"></i></button> -->
        </form>
    </div>
    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <!-- Notification -->
            <li id="notif-approval" class="nav-item dropdown">
            </li><li id="notif" class="nav-item dropdown">
                <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    <span class="badge bg-primary badge-number">2</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                    <li class="dropdown-header fw-bold text-primary">You have 2 new notifications</li>
                    <li class="text-center" style="border-bottom: 2px solid blue;"><a href="#" id="mark-all-read" class="text-secondary" style="font-size: 0.9em;">Mark All as Read</a></li>
                    <ul class="m-0 p-0 overflow-auto" style="max-height: 480px">
                    <li id="8396" class="notification-item" date="2025-09-01T00:00:00.000Z" style="cursor: pointer; border-bottom: 1px solid #444; background-color: unset;">
                        <div class="">
                            <h4>Daily Log Date: 01/09/2025</h4>
                            <p class="text-dark">Your Supervisor has Approved <b>8</b> Items on your Task.<br></p>
                            <p class="text-secondary text-end">at 01/09/2025 07:00:00</p>
                        </div>
                    </li>
                    <li id="8395" class="notification-item" date="2025-09-01T00:00:00.000Z" style="cursor: pointer; border-bottom: 1px solid #444; background-color: unset;">
                        <div class="">
                            <h4>Daily Log Date: 01/09/2025</h4>
                            <p class="text-dark">Your Supervisor has Approved <b>10</b> Items on your Task.<br></p>
                            <p class="text-secondary text-end">at 01/09/2025 07:00:00</p>
                        </div>
                    </li>
                    <li id="8390" class="notification-item" date="2025-09-02T00:00:00.000Z" style="cursor: pointer; border-bottom: 1px solid #444; background-color: unset;">
                        <div class="">
                            <h4>Daily Log Date: 02/09/2025</h4>
                            <p class="text-dark">Your Supervisor has Approved <b>9</b> Items on your Task.<br></p>
                            <p class="text-secondary text-end">at 02/09/2025 07:00:00</p>
                        </div>
                    </li>
                    <li id="8389" class="notification-item" date="2025-09-02T00:00:00.000Z" style="cursor: pointer; border-bottom: 1px solid #444; background-color: unset;">
                        <div class="">
                            <h4>Daily Log Date: 02/09/2025</h4>
                            <p class="text-dark">Your Supervisor has Approved <b>10</b> Items on your Task.<br></p>
                            <p class="text-secondary text-end">at 02/09/2025 07:00:00</p>
                        </div>
                    </li>
                    <li id="8349" class="notification-item" date="2025-08-29T00:00:00.000Z" style="cursor: pointer; border-bottom: 1px solid #444; background-color: rgba(0,0,0, 0.1);">
                        <div class="">
                            <h4>Daily Log Date: 29/08/2025</h4>
                            <p class="text-dark">Your Supervisor has Approved <b>8</b> Items on your Task.<br></p>
                            <p class="text-secondary text-end">at 29/08/2025 07:00:00</p>
                        </div>
                    </li>
                    <li id="8348" class="notification-item" date="2025-08-29T00:00:00.000Z" style="cursor: pointer; border-bottom: 1px solid #444; background-color: rgba(0,0,0, 0.1);">
                        <div class="">
                            <h4>Daily Log Date: 29/08/2025</h4>
                            <p class="text-dark">Your Supervisor has Approved <b>10</b> Items on your Task.<br></p>
                            <p class="text-secondary text-end">at 29/08/2025 07:00:00</p>
                        </div>
                    </li>
                    <li id="8343" class="notification-item" date="2025-08-28T00:00:00.000Z" style="cursor: pointer; border-bottom: 1px solid #444; background-color: rgba(0,0,0, 0.1);">
                        <div class="">
                            <h4>Daily Log Date: 28/08/2025</h4>
                            <p class="text-dark">Your Supervisor has Approved <b>7</b> Items on your Task.<br></p>
                            <p class="text-secondary text-end">at 28/08/2025 07:00:00</p>
                        </div>
                    </li>
                    <li id="8342" class="notification-item" date="2025-08-28T00:00:00.000Z" style="cursor: pointer; border-bottom: 1px solid #444; background-color: rgba(0,0,0, 0.1);">
                        <div class="">
                            <h4>Daily Log Date: 28/08/2025</h4>
                            <p class="text-dark">Your Supervisor has Approved <b>10</b> Items on your Task.<br></p>
                            <p class="text-secondary text-end">at 28/08/2025 07:00:00</p>
                        </div>
                    </li>
                    <li id="8336" class="notification-item" date="2025-08-27T00:00:00.000Z" style="cursor: pointer; border-bottom: 1px solid #444; background-color: rgba(0,0,0, 0.1);">
                        <div class="">
                            <h4>Daily Log Date: 27/08/2025</h4>
                            <p class="text-dark">Your Supervisor has Approved <b>4</b> Items on your Task.<br></p>
                            <p class="text-secondary text-end">at 27/08/2025 07:00:00</p>
                        </div>
                    </li>
                    <li id="8335" class="notification-item" date="2025-08-27T00:00:00.000Z" style="cursor: pointer; border-bottom: 1px solid #444; background-color: rgba(0,0,0, 0.1);">
                        <div class="">
                            <h4>Daily Log Date: 27/08/2025</h4>
                            <p class="text-dark">Your Supervisor has Approved <b>10</b> Items on your Task.<br></p>
                            <p class="text-secondary text-end">at 27/08/2025 07:00:00</p>
                        </div>
                    </li>
                    <li id="8327" class="notification-item" date="2025-08-22T00:00:00.000Z" style="cursor: pointer; border-bottom: 1px solid #444; background-color: rgba(0,0,0, 0.1);">
                        <div class="">
                            <h4>Daily Log Date: 22/08/2025</h4>
                            <p class="text-dark">Your Supervisor has Approved <b>9</b> Items on your Task.<br></p>
                            <p class="text-secondary text-end">at 22/08/2025 07:00:00</p>
                        </div>
                    </li>
                    <li id="8326" class="notification-item" date="2025-08-22T00:00:00.000Z" style="cursor: pointer; border-bottom: 1px solid #444; background-color: rgba(0,0,0, 0.1);">
                        <div class="">
                            <h4>Daily Log Date: 22/08/2025</h4>
                            <p class="text-dark">Your Supervisor has Approved <b>10</b> Items on your Task.<br></p>
                            <p class="text-secondary text-end">at 22/08/2025 07:00:00</p>
                        </div>
                    </li>
                    <li id="8321" class="notification-item" date="2025-08-21T00:00:00.000Z" style="cursor: pointer; border-bottom: 1px solid #444; background-color: rgba(0,0,0, 0.1);">
                        <div class="">
                            <h4>Daily Log Date: 21/08/2025</h4>
                            <p class="text-dark">Your Supervisor has Approved <b>8</b> Items on your Task.<br></p>
                            <p class="text-secondary text-end">at 21/08/2025 07:00:00</p>
                        </div>
                    </li>
                    <li id="8320" class="notification-item" date="2025-08-21T00:00:00.000Z" style="cursor: pointer; border-bottom: 1px solid #444; background-color: rgba(0,0,0, 0.1);">
                        <div class="">
                            <h4>Daily Log Date: 21/08/2025</h4>
                            <p class="text-dark">Your Supervisor has Approved <b>10</b> Items on your Task.<br></p>
                            <p class="text-secondary text-end">at 21/08/2025 07:00:00</p>
                        </div>
                    </li>
                    <li id="8315" class="notification-item" date="2025-08-20T00:00:00.000Z" style="cursor: pointer; border-bottom: 1px solid #444; background-color: rgba(0,0,0, 0.1);">
                        <div class="">
                            <h4>Daily Log Date: 20/08/2025</h4>
                            <p class="text-dark">Your Supervisor has Approved <b>9</b> Items on your Task.<br></p>
                            <p class="text-secondary text-end">at 20/08/2025 07:00:00</p>
                        </div>
                    </li>
                    <li id="8314" class="notification-item" date="2025-08-20T00:00:00.000Z" style="cursor: pointer; border-bottom: 1px solid #444; background-color: rgba(0,0,0, 0.1);">
                        <div class="">
                            <h4>Daily Log Date: 20/08/2025</h4>
                            <p class="text-dark">Your Supervisor has Approved <b>10</b> Items on your Task.<br></p>
                            <p class="text-secondary text-end">at 20/08/2025 07:00:00</p>
                        </div>
                    </li>
                    <li id="8309" class="notification-item" date="2025-08-19T00:00:00.000Z" style="cursor: pointer; border-bottom: 1px solid #444; background-color: rgba(0,0,0, 0.1);">
                        <div class="">
                            <h4>Daily Log Date: 19/08/2025</h4>
                            <p class="text-dark">Your Supervisor has Approved <b>2</b> Items on your Task.<br></p>
                            <p class="text-secondary text-end">at 19/08/2025 07:00:00</p>
                        </div>
                    </li>
                    <li id="8308" class="notification-item" date="2025-08-19T00:00:00.000Z" style="cursor: pointer; border-bottom: 1px solid #444; background-color: rgba(0,0,0, 0.1);">
                        <div class="">
                            <h4>Daily Log Date: 19/08/2025</h4>
                            <p class="text-dark">Your Supervisor has Approved <b>10</b> Items on your Task.<br></p>
                            <p class="text-secondary text-end">at 19/08/2025 07:00:00</p>
                        </div>
                    </li>
                    <li id="8052" class="notification-item" date="2025-08-18T00:00:00.000Z" style="cursor: pointer; border-bottom: 1px solid #444; background-color: rgba(0,0,0, 0.1);">
                        <div class="">
                            <h4>Daily Log Date: 18/08/2025</h4>
                            <p class="text-dark">Your Supervisor has Approved <b>12</b> Items on your Task.<br></p>
                            <p class="text-secondary text-end">at 18/08/2025 00:00:00</p>
                        </div>
                    </li>
                    <li id="8041" class="notification-item" date="2025-08-15T00:00:00.000Z" style="cursor: pointer; border-bottom: 1px solid #444; background-color: rgba(0,0,0, 0.1);">
                        <div class="">
                            <h4>Daily Log Date: 15/08/2025</h4>
                            <p class="text-dark">Your Supervisor has Approved <b>17</b> Items on your Task.<br></p>
                            <p class="text-secondary text-end">at 15/08/2025 00:00:00</p>
                        </div>
                    </li></ul></ul></li><li class="nav-item dropdown pe-3">
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <img src="/images/none.png" alt="Profile" class="rounded-circle">
                    <span class="d-none d-md-block dropdown-toggle ps-2 text-capitalize">{{ Auth::user() ? Auth::user()->name : 'Guest' }}</span>
                </a>
                
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6 class="text-capitalize">{{ Auth::user() ? Auth::user()->name : 'Guest' }}</h6>
                        <div class="text-start">
                            @if(Auth::user() && Auth::user()->employee)
                                <div><span class="text-capitalize">Division: {{ Auth::user()->employee->division->title ?? 'N/A' }}</span></div>
                                <div><span class="text-capitalize">Sub Division: {{ Auth::user()->employee->subDivision->title ?? 'N/A' }}</span></div>
                                <div><span class="text-capitalize">Role: {{ Auth::user()->employee->role->title ?? 'N/A' }}</span></div>
                                <div><span class="text-capitalize">Position: {{ Auth::user()->employee->position->title ?? 'N/A' }}</span></div>
                                @if(Auth::user() && Auth::user()->employee && Auth::user()->employee->superior)
                                    <div><span class="text-capitalize">Superior: {{ Auth::user()->employee->superior->name }}</span></div>
                                @endif
                            @else
                                <div><span class="text-capitalize">Division: N/A</span></div>
                                <div><span class="text-capitalize">Sub Division: N/A</span></div>
                                <div><span class="text-capitalize">Role: N/A</span></div>
                                <div><span class="text-capitalize">Position: N/A</span></div>
                            @endif
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="bi bi-key"></i>
                            <span>Change Password</span>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center w-100" style="border: none; background: none; text-align: left;">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</header>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="changePasswordForm" method="POST" action="{{ route('change-password') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required minlength="8">
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required minlength="8">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('new_password_confirmation').value;
    
    if (newPassword !== confirmPassword) {
        alert('New password and confirmation password do not match!');
        return;
    }
    
    if (newPassword.length < 8) {
        alert('New password must be at least 8 characters long!');
        return;
    }
    
    // Submit the form
    this.submit();
});
</script>
