@extends('layouts.app')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<style>
    :root {
        --primary-blue: #1a73e8;
        --dark-blue-button: #003366;
        --light-bg: #f5f7fa;
        --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    body {
        background-color: var(--light-bg);
        font-family: 'Roboto', sans-serif;
    }

    .modal { z-index: 1055 !important; }
    .modal-backdrop { z-index: 1050 !important; }
</style>
@endsection

@section('content')
<div class="container-fluid" style="max-width: 1400px; margin: 0 auto;">
    <div class="bg-white p-4 rounded-3 mb-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 fw-bold text-primary">
                <i class="bi bi-people-fill me-2"></i>MANAGE COORDINATORS
            </h1>
            <div>
                <button class="btn btn-secondary me-2" data-bs-toggle="modal" data-bs-target="#archivesModal" onclick="loadArchives()">
                    <i class="bi bi-archive-fill me-1"></i> Archives
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCoordinatorModal">
                    <i class="bi bi-person-plus-fill me-1"></i> Add Coordinator
                </button>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-light">
                <label for="deptFilter" class="form-label me-2 mb-0">Filter by Department:</label>
                <select id="deptFilter" class="form-select w-auto d-inline-block" onchange="filterCoordinators()">
                    <option value="">All Departments</option>
                    <option value="BSIT">BSIT</option>
                    <option value="CRIM">CRIM</option>
                    <option value="EDUC">EDUC</option>
                    <option value="BSBA">BSBA</option>
                    <option value="Psychology">Psychology</option>
                    <option value="BSHM">BSHM</option>
                    <option value="BSTM">BSTM</option>
                </select>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Contact</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="coordinatorTableBody">
                            @forelse($coordinators as $coordinator)
                            <tr data-department="{{ $coordinator->department }}">
                                <td>{{ $coordinator->name }}</td>
                                <td>{{ $coordinator->email }}</td>
                                <td>{{ $coordinator->department }}</td>
                                <td>{{ $coordinator->contact ?? 'N/A' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary me-1" onclick="editCoordinator({{ $coordinator->id }}, '{{ $coordinator->name }}', '{{ $coordinator->email }}', '{{ $coordinator->department }}', '{{ $coordinator->contact }}')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="archiveCoordinator({{ $coordinator->id }})">
                                        <i class="bi bi-archive"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted">No coordinators found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Coordinator Modal -->
<div class="modal fade" id="addCoordinatorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addCoordinatorForm">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add New Coordinator</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password *</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control" required minlength="8">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <small class="text-muted">Minimum 8 characters</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password *</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required minlength="8">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Department *</label>
                        <select name="department" class="form-select" required>
                            <option value="">Select Department</option>
                            <option value="BSIT">BSIT</option>
                            <option value="CRIM">CRIM</option>
                            <option value="EDUC">EDUC</option>
                            <option value="BSBA">BSBA</option>
                            <option value="Psychology">Psychology</option>
                            <option value="BSHM">BSHM</option>
                            <option value="BSTM">BSTM</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact" class="form-control" placeholder="+63 912 345 6789">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="confirmSave()">Save Coordinator</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Coordinator Modal -->
<div class="modal fade" id="editCoordinatorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editCoordinatorForm">
                @csrf
                <input type="hidden" id="edit_coordinator_id">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Edit Coordinator</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name *</label>
                        <input type="text" id="edit_name" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" id="edit_email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Department *</label>
                        <select id="edit_department" name="department" class="form-select" required>
                            <option value="">Select Department</option>
                            <option value="BSIT">BSIT</option>
                            <option value="CRIM">CRIM</option>
                            <option value="EDUC">EDUC</option>
                            <option value="BSBA">BSBA</option>
                            <option value="Psychology">Psychology</option>
                            <option value="BSHM">BSHM</option>
                            <option value="BSTM">BSTM</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Number</label>
                        <input type="text" id="edit_contact" name="contact" class="form-control" placeholder="+63 912 345 6789">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" onclick="showResetPassword()"><i class="bi bi-key"></i> Reset Password</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="confirmUpdate()">Update Coordinator</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Confirm Update Modal -->
<div class="modal fade" id="confirmUpdateModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Confirm Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to update this coordinator?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitUpdate()">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Archive Modal -->
<div class="modal fade" id="confirmArchiveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Confirm Archive</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to archive this coordinator?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" onclick="submitArchive()">Archive</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Save Modal -->
<div class="modal fade" id="confirmSaveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Confirm Save</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to create this coordinator?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="submitSave()">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Error</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="errorMessage">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Success</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Coordinator account created successfully!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal" onclick="location.reload()">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Reset Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">New Password *</label>
                    <div class="input-group">
                        <input type="password" id="reset_password" class="form-control" required minlength="8">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('reset_password', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <small class="text-muted">Minimum 8 characters</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm New Password *</label>
                    <div class="input-group">
                        <input type="password" id="reset_password_confirmation" class="form-control" required minlength="8">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('reset_password_confirmation', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" onclick="validateResetPassword()">Reset Password</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Reset Password Modal -->
<div class="modal fade" id="confirmResetPasswordModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Confirm Reset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to reset this coordinator's password?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" onclick="submitResetPassword()">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Archives Modal -->
<div class="modal fade" id="archivesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title"><i class="bi bi-archive-fill me-2"></i>Archived Coordinators</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="archivedCoordinatorsBody">
                            <tr><td colspan="4" class="text-center">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Restore Modal -->
<div class="modal fade" id="confirmRestoreModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Confirm Restore</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to restore this coordinator?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="submitRestore()">Restore</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let archiveCoordinatorId = null;
let restoreCoordinatorId = null;
let addFormData = null;

function togglePassword(fieldId, button) {
    const field = document.getElementById(fieldId);
    const icon = button.querySelector('i');
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

function filterCoordinators() {
    const filter = document.getElementById('deptFilter').value.toLowerCase();
    document.querySelectorAll('#coordinatorTableBody tr').forEach(row => {
        const dept = row.dataset.department?.toLowerCase();
        row.style.display = (!filter || dept === filter) ? '' : 'none';
    });
}

function confirmSave() {
    const form = document.getElementById('addCoordinatorForm');
    const name = form.querySelector('[name="name"]').value.trim();
    const email = form.querySelector('[name="email"]').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirmation').value;
    const department = form.querySelector('[name="department"]').value;
    
    if (!name) {
        document.getElementById('errorMessage').textContent = 'Name is required!';
        new bootstrap.Modal(document.getElementById('errorModal')).show();
        return;
    }
    
    if (!email) {
        document.getElementById('errorMessage').textContent = 'Email is required!';
        new bootstrap.Modal(document.getElementById('errorModal')).show();
        return;
    }
    
    if (!password) {
        document.getElementById('errorMessage').textContent = 'Password is required!';
        new bootstrap.Modal(document.getElementById('errorModal')).show();
        return;
    }
    
    if (!confirmPassword) {
        document.getElementById('errorMessage').textContent = 'Confirm Password is required!';
        new bootstrap.Modal(document.getElementById('errorModal')).show();
        return;
    }
    
    if (!department) {
        document.getElementById('errorMessage').textContent = 'Department is required!';
        new bootstrap.Modal(document.getElementById('errorModal')).show();
        return;
    }
    
    if (password !== confirmPassword) {
        document.getElementById('errorMessage').textContent = 'Passwords do not match!';
        new bootstrap.Modal(document.getElementById('errorModal')).show();
        return;
    }
    
    if (password.length < 8) {
        document.getElementById('errorMessage').textContent = 'Password must be at least 8 characters long!';
        new bootstrap.Modal(document.getElementById('errorModal')).show();
        return;
    }
    
    addFormData = new FormData(document.getElementById('addCoordinatorForm'));
    bootstrap.Modal.getInstance(document.getElementById('addCoordinatorModal')).hide();
    new bootstrap.Modal(document.getElementById('confirmSaveModal')).show();
}

function submitSave() {
    fetch('/coordinators', {
        method: 'POST',
        body: addFormData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('confirmSaveModal')).hide();
            new bootstrap.Modal(document.getElementById('successModal')).show();
        }
    })
    .catch(error => {
        document.getElementById('errorMessage').textContent = 'Error: ' + error.message;
        new bootstrap.Modal(document.getElementById('errorModal')).show();
    });
}

document.getElementById('addCoordinatorForm').addEventListener('submit', function(e) {
    e.preventDefault();
});

function archiveCoordinator(id) {
    archiveCoordinatorId = id;
    new bootstrap.Modal(document.getElementById('confirmArchiveModal')).show();
}

function submitArchive() {
    fetch(`/coordinators/${archiveCoordinatorId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function editCoordinator(id, name, email, department, contact) {
    document.getElementById('edit_coordinator_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_department').value = department;
    document.getElementById('edit_contact').value = contact || '';
    new bootstrap.Modal(document.getElementById('editCoordinatorModal')).show();
}

function confirmUpdate() {
    bootstrap.Modal.getInstance(document.getElementById('editCoordinatorModal')).hide();
    new bootstrap.Modal(document.getElementById('confirmUpdateModal')).show();
}

function submitUpdate() {
    const id = document.getElementById('edit_coordinator_id').value;
    const formData = new FormData(document.getElementById('editCoordinatorForm'));
    
    fetch(`/coordinators/${id}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'X-HTTP-Method-Override': 'PUT'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
    });
}

function showResetPassword() {
    bootstrap.Modal.getInstance(document.getElementById('editCoordinatorModal')).hide();
    new bootstrap.Modal(document.getElementById('resetPasswordModal')).show();
}

function confirmResetPassword() {
    const password = document.getElementById('reset_password').value;
    const confirmPassword = document.getElementById('reset_password_confirmation').value;
    
    if (!password || !confirmPassword) {
        bootstrap.Modal.getInstance(document.getElementById('resetPasswordModal')).hide();
        document.getElementById('errorMessage').textContent = 'Both password fields are required!';
        document.getElementById('errorModal').querySelector('.modal-header').className = 'modal-header bg-danger text-white';
        document.getElementById('errorModal').querySelector('.modal-title').textContent = 'Error';
        new bootstrap.Modal(document.getElementById('errorModal')).show();
        return;
    }
    
    if (password !== confirmPassword) {
        bootstrap.Modal.getInstance(document.getElementById('resetPasswordModal')).hide();
        document.getElementById('errorMessage').textContent = 'Passwords do not match!';
        document.getElementById('errorModal').querySelector('.modal-header').className = 'modal-header bg-danger text-white';
        document.getElementById('errorModal').querySelector('.modal-title').textContent = 'Error';
        new bootstrap.Modal(document.getElementById('errorModal')).show();
        return;
    }
    
    if (password.length < 8) {
        bootstrap.Modal.getInstance(document.getElementById('resetPasswordModal')).hide();
        document.getElementById('errorMessage').textContent = 'Password must be at least 8 characters long!';
        document.getElementById('errorModal').querySelector('.modal-header').className = 'modal-header bg-danger text-white';
        document.getElementById('errorModal').querySelector('.modal-title').textContent = 'Error';
        new bootstrap.Modal(document.getElementById('errorModal')).show();
        return;
    }
    
    bootstrap.Modal.getInstance(document.getElementById('resetPasswordModal')).hide();
    new bootstrap.Modal(document.getElementById('confirmResetPasswordModal')).show();
}

function validateResetPassword() {
    confirmResetPassword();
}

function submitResetPassword() {
    const password = document.getElementById('reset_password').value;
    const confirmPassword = document.getElementById('reset_password_confirmation').value;
    const id = document.getElementById('edit_coordinator_id').value;
    const formData = new FormData();
    formData.append('password', password);
    formData.append('password_confirmation', confirmPassword);
    
    fetch(`/coordinators/${id}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'X-HTTP-Method-Override': 'PUT'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Server error');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('confirmResetPasswordModal')).hide();
            document.getElementById('errorMessage').textContent = 'Password reset successfully!';
            document.getElementById('errorModal').querySelector('.modal-header').className = 'modal-header bg-success text-white';
            document.getElementById('errorModal').querySelector('.modal-title').textContent = 'Success';
            new bootstrap.Modal(document.getElementById('errorModal')).show();
            setTimeout(() => location.reload(), 2000);
        }
    })
    .catch(error => {
        bootstrap.Modal.getInstance(document.getElementById('confirmResetPasswordModal')).hide();
        document.getElementById('errorMessage').textContent = 'Error: ' + error.message;
        document.getElementById('errorModal').querySelector('.modal-header').className = 'modal-header bg-danger text-white';
        document.getElementById('errorModal').querySelector('.modal-title').textContent = 'Error';
        new bootstrap.Modal(document.getElementById('errorModal')).show();
    });
}

function loadArchives() {
    fetch('/coordinators/archives-data', {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        const tbody = document.getElementById('archivedCoordinatorsBody');
        if (data.coordinators.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No archived coordinators found.</td></tr>';
        } else {
            tbody.innerHTML = data.coordinators.map(c => `
                <tr>
                    <td>${c.name}</td>
                    <td>${c.email}</td>
                    <td>${c.department}</td>
                    <td>
                        <button class="btn btn-sm btn-success" onclick="restoreCoordinator(${c.id})">
                            <i class="bi bi-arrow-counterclockwise"></i> Restore
                        </button>
                    </td>
                </tr>
            `).join('');
        }
    });
}

function restoreCoordinator(id) {
    restoreCoordinatorId = id;
    bootstrap.Modal.getInstance(document.getElementById('archivesModal')).hide();
    new bootstrap.Modal(document.getElementById('confirmRestoreModal')).show();
}

function submitRestore() {
    fetch(`/coordinators/${restoreCoordinatorId}/restore`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('confirmRestoreModal')).hide();
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to restore coordinator');
    });
}
</script>
@endsection
