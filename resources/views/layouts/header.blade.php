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
        <ul class="d-flex align-items-center" style="gap: 4px;">
            <!-- Notification -->
            <li id="notif" class="nav-item dropdown">
                <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown" style="position: relative;">
                    <i class="bi bi-bell"></i>
                    <span class="badge bg-danger badge-number" id="notif-count" style="display: none;">0</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                    <li class="dropdown-header fw-bold text-primary" id="notif-header">You have 0 new notifications</li>
                    <li class="text-center" style="border-bottom: 2px solid blue;"><a href="#" id="mark-all-read" class="text-secondary" style="font-size: 0.9em;">Mark All as Read</a></li>
                    <ul class="m-0 p-0 overflow-auto" id="notif-list" style="max-height: 480px">
                        <!-- Notifications will be loaded here dynamically -->
                    </ul>
                </ul>
            </li>
            <li class="nav-item dropdown pe-3">
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

// Notification System
function loadNotifications() {
    // Load unread count
    fetch('{{ route('notifications.count') }}')
        .then(response => response.json())
        .then(data => {
            const count = data.count || 0;
            const badge = document.getElementById('notif-count');
            const header = document.getElementById('notif-header');
            
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'inline';
                header.textContent = `You have ${count} new notification${count > 1 ? 's' : ''}`;
            } else {
                badge.style.display = 'none';
                header.textContent = 'No new notifications';
            }
        })
        .catch(error => console.error('Error loading notification count:', error));
    
    // Load notification list
    fetch('{{ route('notifications.index') }}')
        .then(response => response.json())
        .then(data => {
            const notifList = document.getElementById('notif-list');
            notifList.innerHTML = '';
            
            if (data.notifications.length === 0) {
                notifList.innerHTML = `
                    <li class="text-center py-3 text-muted">
                        <i class="bi bi-inbox"></i> No notifications
                    </li>
                `;
                return;
            }
            
            data.notifications.forEach(notif => {
                const li = document.createElement('li');
                li.className = 'notification-item';
                li.id = `notif-${notif.id}`;
                li.style.cssText = `cursor: pointer; border-bottom: 1px solid #ddd; padding: 12px; ${notif.read_status ? 'background-color: rgba(0,0,0, 0.05);' : 'background-color: #e3f2fd;'}`;
                
                li.innerHTML = `
                    <div>
                        <h6 class="mb-1 fw-bold text-primary">${notif.title}</h6>
                        <p class="mb-1 text-dark small">${notif.message}</p>
                        <p class="mb-0 text-secondary text-end" style="font-size: 0.75rem;">${notif.created_at}</p>
                    </div>
                `;
                
                // Mark as read on click
                li.addEventListener('click', function() {
                    if (!notif.read_status) {
                        markAsRead(notif.id);
                    }
                });
                
                notifList.appendChild(li);
            });
        })
        .catch(error => console.error('Error loading notifications:', error));
}

function markAsRead(notifId) {
    fetch(`/notifications/${notifId}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadNotifications(); // Reload notifications
        }
    })
    .catch(error => console.error('Error marking notification as read:', error));
}

// Mark all as read
document.getElementById('mark-all-read').addEventListener('click', function(e) {
    e.preventDefault();
    
    fetch('{{ route('notifications.readall') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadNotifications(); // Reload notifications
        }
    })
    .catch(error => console.error('Error marking all as read:', error));
});

// Load notifications on page load
document.addEventListener('DOMContentLoaded', function() {
    loadNotifications();
    
    // Reload notifications every 30 seconds
    setInterval(loadNotifications, 30000);
});
</script>
