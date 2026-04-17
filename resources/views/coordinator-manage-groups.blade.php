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
            <button class="btn btn-info ms-2" data-bs-toggle="modal" data-bs-target="#studentListModal">
                <i class="bi bi-people me-1"></i> Student List
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
                <div class="mb-3">
                    <label class="form-label">Filter by Cluster/Section:</label>
                    <select id="clusterFilter" class="form-select" style="width: 200px; display: inline-block;">
                        <option value="">All Clusters</option>
                        @php
                            $clusters = [];
                            foreach($groups as $group) {
                                if($coordinatorDepartment === 'BSIT') {
                                    $cluster = 4100 + ceil($group->group_number / 10);
                                } else {
                                    $cluster = 'Section ' . ceil($group->group_number / 10);
                                }
                                if(!in_array($cluster, $clusters)) {
                                    $clusters[] = $cluster;
                                }
                            }
                            sort($clusters);
                        @endphp
                        @foreach($clusters as $cluster)
                            <option value="{{ $cluster }}">{{ $cluster }}</option>
                        @endforeach
                    </select>
                </div>
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
                                @php
                                    if($coordinatorDepartment === 'BSIT') {
                                        $groupCluster = 4100 + ceil($group->group_number / 10);
                                    } else {
                                        $groupCluster = 'Section ' . ceil($group->group_number / 10);
                                    }
                                @endphp
                                <tr data-cluster="{{ $groupCluster }}">
                                    <td><strong>{{ $group->group_id }}</strong></td>
                                    <td><span class="badge bg-primary">{{ $group->group_number }}</span></td>
                                    <td>
                                        @if($coordinatorDepartment === 'BSIT')
                                            <span class="badge bg-info">{{ 4100 + ceil($group->group_number / 10) }}</span>
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
                                            @if($group->member6_name)
                                                <li><small>{{ $group->member6_name }} ({{ $group->member6_student_id }})</small></li>
                                            @endif
                                            @if($group->member7_name)
                                                <li><small>{{ $group->member7_name }} ({{ $group->member7_student_id }})</small></li>
                                            @endif
                                            @if($group->member8_name)
                                                <li><small>{{ $group->member8_name }} ({{ $group->member8_student_id }})</small></li>
                                            @endif
                                            @if($group->member9_name)
                                                <li><small>{{ $group->member9_name }} ({{ $group->member9_student_id }})</small></li>
                                            @endif
                                            @if($group->member10_name)
                                                <li><small>{{ $group->member10_name }} ({{ $group->member10_student_id }})</small></li>
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
                            <option value="6">Member 6</option>
                            <option value="7">Member 7</option>
                            <option value="8">Member 8</option>
                            <option value="9">Member 9</option>
                            <option value="10">Member 10</option>
                        </select>
                        <small class="text-muted">Select which member is the group leader</small>
                    </div>

                    <h6 class="mb-3">Group Members (Up to 10)</h6>
                    
                    @for($i = 1; $i <= 10; $i++)
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
                                <option value="6" {{ $group->leader_member == 6 ? 'selected' : '' }}>Member 6</option>
                                <option value="7" {{ $group->leader_member == 7 ? 'selected' : '' }}>Member 7</option>
                                <option value="8" {{ $group->leader_member == 8 ? 'selected' : '' }}>Member 8</option>
                                <option value="9" {{ $group->leader_member == 9 ? 'selected' : '' }}>Member 9</option>
                                <option value="10" {{ $group->leader_member == 10 ? 'selected' : '' }}>Member 10</option>
                            </select>
                            <small class="text-muted">Select which member is the group leader</small>
                        </div>

                        <h6 class="mb-3">Group Members</h6>
                        
                        @for($i = 1; $i <= 10; $i++)
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

<!-- Student List Modal -->
<div class="modal fade" id="studentListModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Student List</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <input type="text" id="studentSearch" class="form-control" placeholder="Search students..." style="width: 300px;">
                    </div>
                    <div>
                        <button class="btn btn-success me-2" onclick="addSelectedToGroup()" id="addToGroupBtn" style="display:none;">
                            <i class="bi bi-plus-circle"></i> Add Selected to Group
                        </button>
                        <button class="btn btn-primary" onclick="fetchStudents()">
                            <i class="bi bi-arrow-repeat"></i> Fetch Students
                        </button>
                    </div>
                </div>
                <div id="studentListContent">
                    <p class="text-center text-muted">Click "Fetch Students" to load data</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

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

    // Cluster filter functionality
    document.getElementById('clusterFilter').addEventListener('change', function() {
        const selectedCluster = this.value;
        const rows = document.querySelectorAll('#groupTableBody tr');
        
        rows.forEach(row => {
            if (selectedCluster === '' || row.dataset.cluster === selectedCluster) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Student API Integration
    function fetchStudents() {
        const content = document.getElementById('studentListContent');
        content.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading students...</p></div>';
        
        Promise.all([
            fetch('/api/students/fetch').then(r => r.json()),
            fetch('/groups/assigned-students').then(r => r.json())
        ])
        .then(([studentData, assignedData]) => {
            if (studentData.success && studentData.students) {
                displayStudents(studentData.students, assignedData.assigned || []);
            } else {
                content.innerHTML = '<div class="alert alert-danger">Failed to load students: ' + (studentData.message || 'Unknown error') + '</div>';
            }
        })
        .catch(error => {
            content.innerHTML = '<div class="alert alert-danger">Error: ' + error.message + '</div>';
        });
    }

    function displayStudents(students, assignedStudents = []) {
        const content = document.getElementById('studentListContent');
        
        // Handle nested data structure
        const studentList = students.data || students;
        
        if (!studentList || studentList.length === 0) {
            content.innerHTML = '<p class="text-center text-muted">No students found</p>';
            return;
        }
        
        // Filter unassigned students
        const unassignedStudents = studentList.filter(student => {
            const studentId = student.StudentID || student.student_id || 'N/A';
            return !assignedStudents.includes(studentId);
        });
        
        let html = `
            <div class="alert alert-info d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-info-circle"></i> Total Students: ${studentList.length} | 
                    <span class="text-success">Unassigned: ${unassignedStudents.length}</span> | 
                    <span class="text-secondary">Assigned: ${assignedStudents.length}</span>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="showAssignedToggle" onchange="toggleAssignedStudents()">
                    <label class="form-check-label" for="showAssignedToggle">
                        Show Assigned Students
                    </label>
                </div>
            </div>
            <div class="mb-3">
                <button class="btn btn-sm btn-primary" onclick="selectAllUnassigned()">
                    <i class="bi bi-check-square"></i> Select All Unassigned
                </button>
                <button class="btn btn-sm btn-secondary" onclick="clearSelection()">
                    <i class="bi bi-x-square"></i> Clear Selection
                </button>
                <span class="ms-3 badge bg-info" id="selectedCount">0 selected</span>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="studentTable">
                    <thead>
                        <tr>
                            <th width="50">Select</th>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        studentList.forEach(student => {
            const studentId = student.StudentID || student.student_id || 'N/A';
            const fullName = `${student.FirstName || student.firstname || ''} ${student.MiddleName || student.middlename || ''} ${student.LastName || student.lastname || ''}`;
            const course = student.CurrentCourse || student.course || 'N/A';
            const isAssigned = assignedStudents.includes(studentId);
            
            html += `
                <tr class="${isAssigned ? 'table-secondary assigned-row' : 'unassigned-row'}" style="${isAssigned ? 'display: none;' : ''}">
                    <td>
                        ${isAssigned ? '' : `<input type="checkbox" 
                               class="form-check-input student-checkbox unassigned-checkbox" 
                               data-id="${studentId}" 
                               data-name="${fullName.trim()}" 
                               onchange="handleCheckboxChange(this)">`}
                    </td>
                    <td>${studentId}</td>
                    <td>${fullName.trim()}</td>
                    <td>${course}</td>
                    <td>
                        ${isAssigned ? '<span class="badge bg-secondary"><i class="bi bi-check-circle"></i> Assigned</span>' : '<span class="badge bg-success"><i class="bi bi-circle"></i> Available</span>'}
                    </td>
                </tr>
            `;
        });
        
        html += `
                    </tbody>
                </table>
            </div>
        `;
        
        content.innerHTML = html;
        setupStudentSearch();
        updateSelectedCount();
    }

    function setupStudentSearch() {
        const searchInput = document.getElementById('studentSearch');
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#studentTable tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }

    function handleCheckboxChange(checkbox) {
        const checked = document.querySelectorAll('.unassigned-checkbox:checked').length;
        
        if (checked > 10) {
            checkbox.checked = false;
            alert('Maximum 10 students per group');
            return;
        }
        
        // Disable unchecked checkboxes if 10 are selected
        const allCheckboxes = document.querySelectorAll('.unassigned-checkbox');
        allCheckboxes.forEach(cb => {
            if (!cb.checked && checked === 10) {
                cb.disabled = true;
                cb.parentElement.parentElement.classList.add('table-light');
            } else if (checked < 10) {
                cb.disabled = false;
                cb.parentElement.parentElement.classList.remove('table-light');
            }
        });
        
        updateAddButton();
        updateSelectedCount();
    }

    function updateSelectedCount() {
        const countBadge = document.getElementById('selectedCount');
        if (countBadge) {
            const checked = document.querySelectorAll('.unassigned-checkbox:checked').length;
            countBadge.textContent = `${checked} selected (max 10)`;
            countBadge.className = checked > 0 ? 'ms-3 badge bg-primary' : 'ms-3 badge bg-info';
        }
    }

    function selectAllUnassigned() {
        const unassignedCheckboxes = document.querySelectorAll('.unassigned-checkbox:not(:disabled)');
        
        // Select first 10 unassigned students
        let count = 0;
        unassignedCheckboxes.forEach(cb => {
            if (count < 10) {
                cb.checked = true;
                count++;
            }
        });
        
        if (unassignedCheckboxes.length > 0) {
            handleCheckboxChange(unassignedCheckboxes[0]);
        }
    }

    function clearSelection() {
        const allCheckboxes = document.querySelectorAll('.student-checkbox');
        allCheckboxes.forEach(cb => {
            cb.checked = false;
            cb.disabled = false;
            cb.parentElement.parentElement.classList.remove('table-light');
        });
        updateAddButton();
        updateSelectedCount();
    }

    function toggleAssignedStudents() {
        const showAssigned = document.getElementById('showAssignedToggle').checked;
        const assignedRows = document.querySelectorAll('.assigned-row');
        
        assignedRows.forEach(row => {
            row.style.display = showAssigned ? '' : 'none';
        });
    }

    function updateAddButton() {
        const checked = document.querySelectorAll('.unassigned-checkbox:checked').length;
        const btn = document.getElementById('addToGroupBtn');
        if (btn) {
            btn.style.display = checked > 0 && checked <= 10 ? 'inline-block' : 'none';
            btn.innerHTML = `<i class="bi bi-plus-circle"></i> Add ${checked} Selected to Group`;
        }
    }

    function addSelectedToGroup() {
        const selected = Array.from(document.querySelectorAll('.unassigned-checkbox:checked'));
        if (selected.length === 0 || selected.length > 10) {
            alert('Please select 1-10 unassigned students');
            return;
        }
        
        // Close student list modal
        bootstrap.Modal.getInstance(document.getElementById('studentListModal')).hide();
        
        // Open add group modal and populate
        const addModal = new bootstrap.Modal(document.getElementById('addGroupModal'));
        addModal.show();
        
        // Clear all member fields first
        for (let i = 1; i <= 10; i++) {
            document.querySelector(`input[name="member${i}_student_id"]`).value = '';
            document.querySelector(`input[name="member${i}_name"]`).value = '';
        }
        
        // Populate member fields with selected students
        selected.forEach((checkbox, index) => {
            const memberNum = index + 1;
            document.querySelector(`input[name="member${memberNum}_student_id"]`).value = checkbox.dataset.id;
            document.querySelector(`input[name="member${memberNum}_name"]`).value = checkbox.dataset.name;
        });
        
        // Show success message
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success alert-dismissible fade show';
        alertDiv.innerHTML = `
            <i class="bi bi-check-circle"></i> ${selected.length} student(s) added to the form. Please fill in remaining details and submit.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.querySelector('#addGroupModal .modal-body').prepend(alertDiv);
    }
</script>
@endsection
