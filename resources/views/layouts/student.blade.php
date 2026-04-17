<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRAD</title>

    <link rel="icon"  style="height: 10px; width: 10px;" href="{{ asset('img/Untitled_design-removebg-preview.png') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    @yield('styles')

</head>

<body>
    <div class="d-flex">
        <div id="sidebar" class="d-flex flex-column flex-shrink-0 sidebar">
            <button id="sidebar-toggle" class="sidebar-chevron-toggle">
                <i class="bi bi-chevron-left"></i>
            </button>
            <div class="user-profile">
                <img src="{{ asset('img/avatar.png') }}" alt="Logo" class="img-fluid rounded-circle mb-2"
                    style="width: 60px; height: 60px;" id="sidebarProfileImage">
                <h5 class="mb-0">Student</h5>
                <small>{{ str_replace('s', '', Auth::user()->email ?? 'N/A') }}</small>
            </div>

            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ url('/std-dashboard') }}" class="nav-link text-white">
                        <i class="bi bi-speedometer fs-5"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('submission') }}" class="nav-link">
                        <i class="bi bi-cloud-upload fs-5"></i>
                        <span>Submission</span>
                    </a>

                </li>

                <li>
                    <a href="{{url('/view-panel-adviser')}}" class="nav-link">
                        <i class="bi bi-person-plus-fill fs-5"></i>
                        <span>View Assigned Adviser & Panel </span>
                    </a>

                </li>

                <li>
                    <a href="{{url('/view-sched')}}" class="nav-link">
                        <i class="bi bi-calendar4-week fs-5"></i>
                        <span>View Schedules </span>
                    </a>

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
                                {{ $notifications->where('read', false)->count() }}
                            </span>
                        </a>
                    </div>
                    


                    <div class="dropdown">
                        <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="userDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-4"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <h6 class="dropdown-header">{{ Auth::user()->name ?? 'Juan Dela Cruz' }}</h6>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
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
                        <li class="breadcrumb-item"><a href="{{ url('std-dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
              
                <div class="container-fluid">
                    @yield('content')
              
                </div>
            <footer class="footer">
                Center for Research And Development &copy;
            </footer>
        </div>
        <div class="sidebar-overlay"></div>
    </div>
    
<!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">Student Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div class="position-relative d-inline-block">
                        <img src="{{ asset('img/avatar.png') }}" alt="Profile Picture" id="studentProfileImage"
                             class="rounded-circle" width="100" height="100">
                        <button id="studentProfileBtn"type="button" class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle" 
                                style="width: 30px; height: 30px; padding: 0;">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <input type="file" id="studentProfileInput" accept="image/*" style="display: none;" onchange="previewStudentImage(event)">
                    </div>
                </div>
                <p><strong>Group No:</strong> {{ Auth::user()->group_no ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ Auth::user()->email ?? 'N/A' }}</p>
                <p><strong>Department:</strong> {{ Auth::user()->department ?? 'N/A' }}</p>
                <p><strong>Section/Cluster:</strong> {{ Auth::user()->cluster ?? 'N/A' }}</p>
                <hr>
                <p class="fw-bold mb-2">Group Members:</p>
                @php
                    $groupId = str_replace('s', '', Auth::user()->email ?? '');
                    $group = \App\Models\Group::where('group_id', $groupId)->first();
                @endphp
                @if($group)
                    <ul class="list-group">
                        @for($i = 1; $i <= 5; $i++)
                            @if($group->{"member{$i}_name"})
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $group->{"member{$i}_name"} }}
                                    @if($i == $group->leader_member)
                                        <span class="badge bg-primary">Leader</span>
                                    @endif
                                </li>
                            @endif
                        @endfor
                    </ul>
                @else
                    <p class="text-muted">No group members found</p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveStudentProfile">Save Changes</button>
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
            <div class="modal-body p-0" style="max-height: 500px; overflow-y: auto;">
                @forelse($notifications ?? collect() as $notification)
                    <a href="{{ route('student.notification.details', $notification->id) }}" class="text-decoration-none text-dark">
                        <div class="notification-item p-3 border-bottom {{ $notification->read ? '' : 'bg-light' }}" style="cursor: pointer; transition: background-color 0.2s;">
                            <div class="d-flex align-items-start">
                                <div class="notification-icon me-3">
                                    @if($notification->type == 'Approved')
                                        <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-check-circle text-white"></i>
                                        </div>
                                    @elseif($notification->type == 'Rejected')
                                        <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-x-circle text-white"></i>
                                        </div>
                                    @else
                                        <div class="bg-info rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-chat-left-text text-white"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="notification-content flex-grow-1">
                                    <div class="notification-message fw-semibold mb-1">
                                        {{ $notification->message }}
                                    </div>
                                    <div class="notification-meta d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </small>
                                        @if(!$notification->read)
                                            <span class="badge bg-primary">New</span>
                                        @endif
                                    </div>
                                    @if($notification->submission)
                                        <hr class="my-2">
                                        <div class="mt-2">
                                            <small class="text-muted d-block fw-bold">Submission Details</small>
                                            <small class="text-muted d-block"><strong>Document:</strong> {{ $notification->submission->documents ?? 'N/A' }}</small>
                                            <small class="text-muted d-block"><strong>Status:</strong> <span class="badge bg-{{ $notification->submission->status == 'Approved' ? 'success' : ($notification->submission->status == 'Rejected' ? 'danger' : 'warning') }}">{{ $notification->submission->status }}</span></small>
                                            <small class="text-muted d-block"><strong>Department:</strong> {{ $notification->submission->department ?? 'N/A' }}</small>
                                            <small class="text-muted d-block"><strong>Group:</strong> {{ $notification->submission->group_no ?? 'N/A' }}</small>
                                            @if($notification->submission->feedback)
                                                <small class="text-muted d-block"><strong>Feedback:</strong> {{ $notification->submission->feedback }}</small>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-bell-slash text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3">No notifications yet</p>
                    </div>
                @endforelse
            </div>
            <div class="modal-footer">
                <a href="{{ route('student.notifications') }}" class="btn btn-success">
                    <i class="bi bi-check-all me-1"></i>Mark All as Read
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
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

            // Logout confirmation handler
            const confirmLogoutBtn = document.getElementById('confirmLogoutBtn');
            const logoutForm = document.getElementById('logoutForm');
            
            if (confirmLogoutBtn && logoutForm) {
                confirmLogoutBtn.addEventListener('click', function() {
                    logoutForm.submit();
                });
            }
        });
    </script>
    <script>
        function previewStudentImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('studentProfileImage').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const savedImage = localStorage.getItem('studentProfileImage');
            if (savedImage) {
                document.getElementById('studentProfileImage').src = savedImage;
                document.getElementById('sidebarProfileImage').src = savedImage;
            }
            
            const saveBtn = document.getElementById('saveStudentProfile');
            if (saveBtn) {
                saveBtn.addEventListener('click', function() {
                    const imageSrc = document.getElementById('studentProfileImage').src;
                    localStorage.setItem('studentProfileImage', imageSrc);
                    document.getElementById('sidebarProfileImage').src = imageSrc;
                    
                    const profileModal = bootstrap.Modal.getInstance(document.getElementById('profileModal'));
                    profileModal.hide();
                    
                    const successModal = new bootstrap.Modal(document.getElementById('profileSaveSuccessModal'));
                    successModal.show();
                });
            }
        });

        document.getElementById('studentProfileBtn')
  .addEventListener('click', () => {
    document.getElementById('studentProfileInput').click();
  });
    </script>
    @yield('scripts')

</body>

</html>