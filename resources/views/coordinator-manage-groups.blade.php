@extends('layouts.coordinator')

@section('styles')
<style>
    .card {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    .member-input-group {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Manage Groups</h2>
            <p class="text-muted mb-0">Department: <strong>{{ $coordinatorDepartment }}</strong></p>
        </div>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGroupModal">
                <i class="bi bi-plus-circle me-1"></i> Add Group
            </button>
            <button class="btn btn-secondary ms-2" data-bs-toggle="modal" data-bs-target="#archiveGroupModal">
                <i class="bi bi-archive me-1"></i> Archived Groups
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($groups->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Group ID</th>
                                <th>Group #</th>
                                <th>Cluster/Section</th>
                                <th>Leader</th>
                                <th>Members</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="groupTableBody">
                            @foreach($groups as $group)
                                <tr>
                                    <td><strong>{{ $group->group_id }}</strong></td>
                                    <td><span class="badge bg-primary">{{ $group->group_number }}</span></td>
                                    <td>
                                        @if($coordinatorDepartment === 'BSIT')
                                            <span class="badge bg-info">{{ 4101 + floor(($group->group_number - 1) / 10) }}</span>
                                        @else
                                            <span class="badge bg-secondary">Section {{ ceil($group->group_number / 10) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $leaderName = $group->{'member' . $group->leader_member . '_name'};
                                        @endphp
                                        @if($leaderName)
                                            {{ $leaderName }}
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                    <td>
                                        <ul class="list-unstyled mb-0">
                                            @if($group->member1_name)
                                                <li><small>{{ $group->member1_name }} ({{ $group->member1_student_id }})</small></li>
                                            @endif
                                            @if($group->member2_name)
                                                <li><small>{{ $group->member2_name }} ({{ $group->member2_student_id }})</small></li>
                                            @endif
                                            @if($group->member3_name)
                                                <li><small>{{ $group->member3_name }} ({{ $group->member3_student_id }})</small></li>
                                            @endif
                                            @if($group->member4_name)
                                                <li><small>{{ $group->member4_name }} ({{ $group->member4_student_id }})</small></li>
                                            @endif
                                            @if($group->member5_name)
                                                <li><small>{{ $group->member5_name }} ({{ $group->member5_student_id }})</small></li>
                                            @endif
                                        </ul>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editGroupModal{{ $group->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-secondary" onclick="deleteGroup({{ $group->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-muted">No groups found. Add one to get started.</p>
            @endif
        </div>
    </div>
</div>

<!-- Add Group Modal -->
<div class="modal fade" id="addGroupModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('groups.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add New Group</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Group ID *</label>
                            <input type="text" name="group_id" class="form-control" placeholder="e.g., IT001" required>
                            <small class="text-muted">This will be used for student login</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Group Number *</label>
                            <input type="text" name="group_number" class="form-control" placeholder="e.g., 1" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Password *</label>
                            <input type="password" name="password" class="form-control" placeholder="Min 6 characters" required minlength="6">
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>Department: <strong>{{ $coordinatorDepartment }}</strong>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Group Leader *</label>
                        <select name="leader_member" class="form-select" required>
                            <option value="1">Group Leader</option>
                            <option value="2">Member 2</option>
                            <option value="3">Member 3</option>
                            <option value="4">Member 4</option>
                            <option value="5">Member 5</option>
                        </select>
                        <small class="text-muted">Select which member is the group leader</small>
                    </div>

                    <h6 class="mb-3">Group Members (Up to 5)</h6>
                    
                    @for($i = 1; $i <= 5; $i++)
                        <div class="member-input-group">
                            <h6 class="text-primary mb-2">{{ $i == 1 ? 'Group Leader' : 'Member ' . $i }}</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="member{{ $i }}_name" class="form-control" placeholder="Enter full name">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Student ID</label>
                                    <input type="text" name="member{{ $i }}_student_id" class="form-control" placeholder="Enter student ID">
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Group</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Group Modals -->
@foreach($groups as $group)
    <div class="modal fade" id="editGroupModal{{ $group->id }}" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form action="{{ route('groups.update', $group->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Edit Group</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Group ID *</label>
                                <input type="text" name="group_id" class="form-control" value="{{ $group->group_id }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Group Number *</label>
                                <input type="text" name="group_number" class="form-control" value="{{ $group->group_number }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current" minlength="6">
                                <small class="text-muted">Only fill if you want to change password</small>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>Department: <strong>{{ $coordinatorDepartment }}</strong>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Group Leader *</label>
                            <select name="leader_member" class="form-select" required>
                                <option value="1" {{ $group->leader_member == 1 ? 'selected' : '' }}>Group Leader</option>
                                <option value="2" {{ $group->leader_member == 2 ? 'selected' : '' }}>Member 2</option>
                                <option value="3" {{ $group->leader_member == 3 ? 'selected' : '' }}>Member 3</option>
                                <option value="4" {{ $group->leader_member == 4 ? 'selected' : '' }}>Member 4</option>
                                <option value="5" {{ $group->leader_member == 5 ? 'selected' : '' }}>Member 5</option>
                            </select>
                            <small class="text-muted">Select which member is the group leader</small>
                        </div>

                        <h6 class="mb-3">Group Members</h6>
                        
                        @for($i = 1; $i <= 5; $i++)
                            <div class="member-input-group">
                                <h6 class="text-primary mb-2">{{ $i == 1 ? 'Group Leader' : 'Member ' . $i }}</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" name="member{{ $i }}_name" class="form-control" value="{{ $group->{'member' . $i . '_name'} }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Student ID</label>
                                        <input type="text" name="member{{ $i }}_student_id" class="form-control" value="{{ $group->{'member' . $i . '_student_id'} }}">
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Group</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- Archive Modal -->
<div class="modal fade" id="archiveGroupModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title">Archived Groups</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Group ID</th>
                                <th>Archived Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="archivedGroupsBody">
                            <tr><td colspan="3" class="text-center text-muted">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="confirmationMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmationBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Success</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                <p id="successMessage" class="mt-3"></p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let confirmationCallback = null;

    function showConfirmation(message, callback) {
        document.getElementById('confirmationMessage').textContent = message;
        confirmationCallback = callback;
        const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        modal.show();
    }

    document.getElementById('confirmationBtn').addEventListener('click', function() {
        if (confirmationCallback) {
            confirmationCallback();
            bootstrap.Modal.getInstance(document.getElementById('confirmationModal')).hide();
            confirmationCallback = null;
        }
    });

    function deleteGroup(groupId) {
        showConfirmation('Are you sure you want to archive this group?', function() {
            fetch(`/groups/${groupId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('successMessage').textContent = 'Group archived successfully!';
                    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                    document.getElementById('successModal').addEventListener('hidden.bs.modal', () => {
                        location.reload();
                    }, { once: true });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while archiving the group.');
            });
        });
    }

    function loadArchivedGroups() {
        fetch('/groups/archived')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('archivedGroupsBody');
                if (!data || data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">No archived groups</td></tr>';
                    return;
                }
                
                tbody.innerHTML = data.map(group => `
                    <tr>
                        <td>${group.group_id}</td>
                        <td>${new Date(group.deleted_at).toLocaleDateString()}</td>
                        <td>
                            <button class="btn btn-sm btn-secondary" onclick="restoreGroup(${group.id})">
                                <i class="bi bi-arrow-clockwise"></i> Restore
                            </button>
                        </td>
                    </tr>
                `).join('');
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('archivedGroupsBody').innerHTML = '<tr><td colspan="3" class="text-center text-danger">Error loading data</td></tr>';
            });
    }

    function restoreGroup(groupId) {
        showConfirmation('Are you sure you want to restore this group?', function() {
            fetch(`/groups/${groupId}/restore`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('successMessage').textContent = 'Group restored successfully!';
                    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                    document.getElementById('successModal').addEventListener('hidden.bs.modal', () => {
                        location.reload();
                    }, { once: true });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while restoring the group.');
            });
        });
    }

    document.getElementById('archiveGroupModal').addEventListener('show.bs.modal', loadArchivedGroups);
</script>
@endsection
