<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CRAD</title>

    <link rel="icon" style="height: 10px; width: 10px;" href="{{ asset('img/crad-removebg-preview.jpg') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    @yield('styles')

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

</head>

<body>
    <div class="d-flex">
        <div id="sidebar" class="d-flex flex-column flex-shrink-0 sidebar">
            <button id="sidebar-toggle" class="sidebar-chevron-toggle" aria-label="Toggle sidebar">
                <i class="bi bi-chevron-left"></i>
            </button>
            <div class="user-profile">
                <div class="d-flex align-items-center justify-content-center mb-3" style="gap: 15px;">
                    <img src="{{ asset('img/BCP-3.png') }}" alt="BCP Logo" class="img-fluid" style="height: 60px; width: auto;">
                    <img src="{{ asset('img/CRAD-2.png') }}" alt="CRAD Logo" class="img-fluid" style="height: 60px; width: auto;">
                </div>
                <h6 class="mb-0 fw-bold">Center for Research & Development</h6>
                <small>ADMIN</small>
            </div>

            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ url('/admin-dashboard') }}" class="nav-link text-white">
                        <i class="bi bi-speedometer fs-5"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('/track-proposal') }}" class="nav-link">
                        <i class="bi bi-cloud-upload fs-5"></i>
                        <span>Proposal Submission & Tracking </span>
                    </a>

                </li>

                <li>
                    <a href="{{ url('/panel-adviser') }}" class="nav-link">
                        <i class="bi bi-person-plus-fill fs-5"></i>
                        <span>Adviser & Panel Assignment </span>
                    </a>

                </li>

                <li>
                    <a href="{{ url('/def-sched') }}" class="nav-link">
                        <i class="bi bi-calendar4-week fs-5"></i>
                        <span>Defense Scheduling</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ url('/def-eval') }}" class="nav-link">
                        <i class="bi bi-clipboard-check fs-5"></i>
                        <span>Defense Evaluation</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/analytics') }}" class="nav-link">
                        <i class="bi bi-bar-chart-line fs-5"></i>
                        <span>Analytics & Reporting</span>
                    </a>
                </li>

                <li><hr class="dropdown-divider mx-3 my-3" style="border-top: 1px solid rgba(255, 255, 255, 0.3);"></li>
                
                <li>
                    <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#addEmailModal">
                        <i class="bi bi-plus-circle-fill fs-5"></i>
                        <span>Add Email</span>
                    </a>
                </li>
                
                <li>
                    <a href="#userManagementSubmenu" class="nav-link d-flex align-items-center" data-bs-toggle="collapse" aria-expanded="false">
                        <i class="bi bi-grid-fill"></i>
                        <span class="flex-grow-1">Others</span>
                        <i class="bi bi-chevron-right" id="userManagementChevron"></i>
                    </a>
                    <div class="collapse" id="userManagementSubmenu">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">
                                <a href="{{ url('/manage-coordinator') }}" class="nav-link">
                                    <i class="bi bi-people-fill"></i>
                                    <span>Manage Coordinator</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
        <div class="main-content-wrapper">
            <nav class="top-navbar d-flex justify-content-between align-items-center">
                <div>
                </div>
                <div class="d-flex align-items-center">
                    <span id="current-time" class="me-3 d-none d-sm-inline"></span>
                    <div class="me-3">
                        <a href="#" class="text-dark position-relative" data-bs-toggle="modal" data-bs-target="#notificationModal">
                            <i class="bi bi-bell fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                0
                            </span>
                        </a>
                    </div>


                    <div class="dropdown">
                        <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="userDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-4"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><h6 class="dropdown-header">{{ Auth::user()->name ?? 'Admin' }}</h6></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#logoutConfirmModal">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <main class="content-area">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('admin-dashboard') }}">Home</a></li>
                        @if(Request::is('def-sched'))
                            <li class="breadcrumb-item"><a href="{{ url('def-sched') }}">Defense Scheduling</a></li>
                            @if(request('defense_type') == 'PRE-ORAL')
                                <li class="breadcrumb-item active" aria-current="page">Pre-oral Defense</li>
                            @elseif(request('defense_type') == 'FINAL DEFENSE')
                                <li class="breadcrumb-item active" aria-current="page">Final Defense</li>
                            @elseif(request('defense_type') == 'REDEFENSE')
                                <li class="breadcrumb-item active" aria-current="page">Re-defense</li>
                            @else
                                <li class="breadcrumb-item active" aria-current="page">Defense Scheduling</li>
                            @endif
                        @elseif(Request::is('def-eval'))
                            <li class="breadcrumb-item"><a href="{{ url('def-eval') }}">Defense Evaluation</a></li>
                            @if(request('defense_type') == 'PRE-ORAL')
                                <li class="breadcrumb-item active" aria-current="page">Pre-oral Defense</li>
                            @elseif(request('defense_type') == 'FINAL DEFENSE')
                                <li class="breadcrumb-item active" aria-current="page">Final Defense</li>
                            @elseif(request('defense_type') == 'REDEFENSE')
                                <li class="breadcrumb-item active" aria-current="page">Re-defense</li>
                            @else
                                <li class="breadcrumb-item active" aria-current="page">Defense Evaluation</li>
                            @endif
                        @else
                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                        @endif
                    </ol>
                </nav>

                @hasSection('content')
                    @yield('content')
                @else
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <div class="card bg-primary text-white h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Pending Proposals</h5>
                                    <h2 class="card-text">12</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Approved</h5>
                                    <h2 class="card-text">24</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-warning text-dark h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Upcoming Defenses</h5>
                                    <h2 class="card-text">5</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </main>

            <footer class="footer">
                Center for Research And Development &copy;
            </footer>
        </div>
        <div class="sidebar-overlay"></div>
    </div>

    <!-- Notification Modal -->
    <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="notificationModalLabel">
                        <i class="bi bi-bell me-2"></i>Notifications
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" style="max-height: 500px; overflow-y: auto;" id="notificationList">
                    <div class="text-center py-5">
                        <i class="bi bi-bell-slash text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3">No notifications yet</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#markReadModal">
                        <i class="bi bi-check-all me-1"></i>Mark All as Read
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mark as Read Confirmation Modal -->
    <div class="modal fade" id="markReadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="bi bi-check-circle me-2"></i>Mark All as Read</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="bi bi-question-circle text-success" style="font-size: 3rem;"></i>
                    <p class="mt-3 mb-0">Are you sure you want to mark all notifications as read?</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="confirmMarkAllAsRead()">Yes, Mark All</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mark as Read Success Modal -->
    <div class="modal fade" id="markReadSuccessModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Success!</h5>
                    <p class="mb-0">All notifications marked as read!</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Save Success Modal -->
    <div class="modal fade" id="profileSaveSuccessModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Success!</h5>
                    <p class="mb-0">Profile picture saved successfully!</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel">Admin Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <div class="position-relative d-inline-block">
                            <img src="{{ asset('img/sms.png') }}" alt="Profile Picture" id="adminProfileImage"
                                 class="rounded-circle" width="100" height="100">
                            <button id="adminProfileBtn" type="button" class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle" 
                                    style="width: 30px; height: 30px; padding: 0;">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <input type="file" id="adminProfileInput" accept="image/*" style="display: none;" onchange="previewAdminImage(event)">
                        </div>
                    </div>
                    <p><strong>Name:</strong> {{ Auth::user()->name ?? 'Admin' }}</p>
                    <p><strong>Email:</strong> {{ Auth::user()->email ?? 'admin@crad.edu' }}</p>
                    <p><strong>Role:</strong> Administrator</p>
                    <p><strong>Department:</strong> Center for Research and Development</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveAdminProfile">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutConfirmModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header bg-danger text-white border-0">
                    <h5 class="modal-title">Confirm Logout</h5>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <i class="bi bi-exclamation-circle text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <p class="fs-5 mb-0">Are you sure you want to logout from your account?</p>
                </div>
                <div class="modal-footer border-0 justify-content-center gap-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmLogoutBtn">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Form (hidden) -->
    <form id="logoutForm" method="POST" action="{{ route('logout') }}" style="display: none;">
        @csrf
    </form>

    <!-- Add Email Modal -->
    <div class="modal fade" id="addEmailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title"><i class="bi bi-envelope-plus"></i> Manage Notification Emails</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if(session('notif_success'))
                        <div class="alert alert-success py-2">{{ session('notif_success') }}</div>
                    @endif
                    @if(session('notif_error'))
                        <div class="alert alert-danger py-2">{{ session('notif_error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('admin.notification-emails.store') }}" class="d-flex gap-2 mb-3">
                        @csrf
                        <input type="email"  id="email" autocomplete="email" class="form-control" placeholder="Enter email address" required>
                        <button type="submit" class="btn btn-dark text-nowrap">
                            <i class="bi bi-plus-circle"></i> Add
                        </button>
                    </form>
                    @error('email')
                        <div class="text-danger small mb-2">{{ $message }}</div>
                    @enderror

                    <hr>
                    <h6 class="fw-bold mb-3">Email List</h6>
                    @php
                        $notificationEmails = \App\Models\NotificationEmail::orderBy('created_at', 'desc')->get();
                    @endphp
                    @if($notificationEmails->isEmpty())
                        <p class="text-muted text-center my-3">No notification emails added yet.</p>
                    @else
                        <ul class="list-group">
                            @foreach($notificationEmails as $ne)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span>{{ $ne->email }}</span>
                                    @if($ne->confirmed)
                                        <span class="badge bg-success ms-2">Confirmed</span>
                                    @else
                                        <span class="badge bg-warning text-dark ms-2">Pending</span>
                                    @endif
                                </div>
                                <form method="POST" action="{{ route('admin.notification-emails.destroy', $ne->id) }}" onsubmit="return confirm('Remove this email?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        // Sidebar toggle
        document.getElementById('sidebar-toggle').addEventListener('click', function () {
            if (window.innerWidth > 991.98) {
                // Desktop: toggle between full and icon-only
                document.body.classList.toggle('sidebar-collapsed');
            } else {
                // Mobile: toggle sidebar visibility
                document.body.classList.toggle('sidebar-toggled');
            }
        });

        // Close sidebar when overlay is clicked
        document.querySelector('.sidebar-overlay').addEventListener('click', function () {
            document.body.classList.remove('sidebar-toggled');
        });

        // Live Clock
        function updateTime() {
            const timeElement = document.getElementById('current-time');
            if (timeElement) {
                const now = new Date();
                const hours = now.getHours();
                const minutes = now.getMinutes();
                const seconds = now.getSeconds();
                const ampm = hours >= 12 ? 'PM' : 'AM';
                const displayHours = hours % 12 || 12;
                
                timeElement.textContent = `${displayHours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')} ${ampm}`;
            }
        }
        setInterval(updateTime, 1000);
        updateTime();

        // Sidebar active state logic
        document.addEventListener("DOMContentLoaded", function () {
            const path = window.location.pathname;
            const navLinks = document.querySelectorAll('.sidebar .nav-link');

            navLinks.forEach(link => {
                // Remove 'active' class from all links first
                link.classList.remove('active');

                // Find the link that matches the current page's URL
                if (link.getAttribute('href') === path) {
                    // Add 'active' class to the matching link
                    link.classList.add('active');

                    // Check if this link is inside a collapsible sub-menu
                    let parentCollapse = link.closest('.collapse');
                    if (parentCollapse) {
                        // If it is, open the sub-menu by adding the 'show' class
                        parentCollapse.classList.add('show');

                        // Add an 'active' style to the parent link as well (optional, but good for UX)
                        let parentLink = parentCollapse.previousElementSibling;
                        if (parentLink && parentLink.classList.contains('nav-link')) {
                            parentLink.classList.add('active-parent');
                        }
                    }
                }
            });

            // Chevron rotation for User Management
            const userManagementSubmenu = document.getElementById('userManagementSubmenu');
            const userManagementChevron = document.getElementById('userManagementChevron');
            
            if (userManagementSubmenu && userManagementChevron) {
                userManagementSubmenu.addEventListener('show.bs.collapse', function() {
                    userManagementChevron.classList.remove('bi-chevron-right');
                    userManagementChevron.classList.add('bi-chevron-down');
                });
                
                userManagementSubmenu.addEventListener('hide.bs.collapse', function() {
                    userManagementChevron.classList.remove('bi-chevron-down');
                    userManagementChevron.classList.add('bi-chevron-right');
                });
            }

            // Logout confirmation handler
            const confirmLogoutBtn = document.getElementById('confirmLogoutBtn');
            const logoutForm = document.getElementById('logoutForm');
            
            if (confirmLogoutBtn) {
                confirmLogoutBtn.addEventListener('click', function() {
                    logoutForm.submit();
                });
            }
        });
    </script>
    <script>
        function previewAdminImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('adminProfileImage').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const savedImage = localStorage.getItem('adminProfileImage');
            if (savedImage) {
                document.getElementById('adminProfileImage').src = savedImage;
            }
            
            document.getElementById('saveAdminProfile').addEventListener('click', function() {
                const imageSrc = document.getElementById('adminProfileImage').src;
                localStorage.setItem('adminProfileImage', imageSrc);
                
                const profileModal = bootstrap.Modal.getInstance(document.getElementById('profileModal'));
                profileModal.hide();
                
                const successModal = new bootstrap.Modal(document.getElementById('profileSaveSuccessModal'));
                successModal.show();
            });
        });

        document.getElementById('adminProfileBtn')
        .addEventListener('click', () => {
        document.getElementById('adminProfileInput').click();
      });

        // Reopen Add Email Modal if there are session messages
        @if(session('notif_success') || session('notif_error'))
            var addEmailModal = new bootstrap.Modal(document.getElementById('addEmailModal'));
            addEmailModal.show();
        @endif
    </script>
    <style>
        .notification-item:hover {
            background-color: #f8f9fa !important;
        }
        .notification-item:last-child {
            border-bottom: none !important;
        }
        .notification-icon {
            flex-shrink: 0;
        }
    </style>
    <script>
        // Load admin notifications
        function loadNotifications() {
            fetch('/api/admin/notifications')
                .then(response => response.json())
                .then(data => {
                    const notificationList = document.getElementById('notificationList');
                    const badge = document.querySelector('.badge.bg-danger');
                    
                    badge.textContent = data.unread_count;
                    
                    if (data.notifications.length === 0) {
                        notificationList.innerHTML = `
                            <div class="text-center py-5">
                                <i class="bi bi-bell-slash text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3">No notifications yet</p>
                            </div>
                        `;
                    } else {
                        notificationList.innerHTML = data.notifications.map(notif => {
                            const iconMap = {
                                'submission': 'bi-file-earmark-plus',
                                'resubmission': 'bi-arrow-repeat',
                                'approved': 'bi-check-circle',
                                'rejected': 'bi-x-circle',
                                'feedback': 'bi-chat-left-text',
                                'clearance_update': 'bi-shield-check'
                            };
                            const icon = iconMap[notif.type] || 'bi-bell';
                            const bgClass = notif.read ? 'bg-light' : 'bg-white';
                            
                            let submissionDetails = '';
                            if (notif.submission) {
                                submissionDetails = `
                                    <hr class="my-2">
                                    <div class="mt-2">
                                        <small class="text-muted d-block fw-bold">Submission Details</small>
                                        <small class="text-muted d-block"><strong>Document:</strong> ${notif.submission.documents || 'N/A'}</small>
                                        <small class="text-muted d-block"><strong>Status:</strong> <span class="badge bg-${notif.submission.status === 'Approved' ? 'success' : notif.submission.status === 'Rejected' ? 'danger' : 'warning'}">${notif.submission.status}</span></small>
                                        <small class="text-muted d-block"><strong>Department:</strong> ${notif.submission.department || 'N/A'}</small>
                                        <small class="text-muted d-block"><strong>Group:</strong> ${notif.submission.group_no || 'N/A'}</small>
                                        ${notif.submission.feedback ? `<small class="text-muted d-block"><strong>Feedback:</strong> ${notif.submission.feedback}</small>` : ''}
                                    </div>
                                `;
                            }
                            
                            return `
                                <div class="notification-item p-3 border-bottom ${bgClass}">
                                    <div class="d-flex align-items-start">
                                        <div class="notification-icon me-3">
                                            <i class="bi ${icon} text-primary fs-4"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-1">${notif.message}</p>
                                            <small class="text-muted">${new Date(notif.created_at).toLocaleString()}</small>
                                            ${submissionDetails}
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('');
                    }
                })
                .catch(error => console.error('Error loading notifications:', error));
        }
        
        // Load notifications on page load
        document.addEventListener('DOMContentLoaded', loadNotifications);
        
        // Refresh notifications every 30 seconds
        setInterval(loadNotifications, 30000);
        
        // Admin notifications - placeholder for future implementation
        function confirmMarkAllAsRead() {
            // Close confirmation modal
            const confirmModal = bootstrap.Modal.getInstance(document.getElementById('markReadModal'));
            confirmModal.hide();
            
            // Call API to mark all as read
            fetch('/api/admin/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update badge to 0
                    document.querySelector('.badge.bg-danger').textContent = '0';
                    
                    // Reload notifications
                    loadNotifications();
                    
                    // Show success modal
                    setTimeout(() => {
                        const successModal = new bootstrap.Modal(document.getElementById('markReadSuccessModal'));
                        successModal.show();
                    }, 300);
                }
            })
            .catch(error => console.error('Error marking notifications as read:', error));
        }
    </script>
    @yield('scripts')

</body>

</html>