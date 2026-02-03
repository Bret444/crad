@extends('layouts.app')

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container my-5">
    <h1 class="text-center mb-4 text-primary fw-bold">Requirements Tracking</h1>

    <!-- Filters -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form id="filterForm" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">Department</label>
                    <select name="department" id="departmentFilter" class="form-select">
                        <option value="">All</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}">
                                {{ $dept }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Cluster</label>
                    <select name="cluster" id="clusterFilter" class="form-select">
                        <option value="">All</option>
                        @foreach($clusters as $cl)
                            <option value="{{ $cl }}">
                                Cluster {{ $cl }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 text-end">
                    <button type="button" id="applyFilters" class="btn btn-primary">Apply Filters</button>
                    <button type="button" id="clearFilters" class="btn btn-secondary">Clear</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Group Selection -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">Select Group</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <select id="groupSelect" class="form-select">
                        <option value="">Select a Group</option>
                        @foreach($groups as $grp)
                            <option value="{{ $grp }}">
                                Group {{ $grp }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Requirements Containers -->
    <div id="requirementsContainer" style="display: none;">
        <!-- Content will be loaded dynamically -->
    </div>

    <!-- History Logs -->
    <div class="card mt-5 shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-history"></i> History Logs</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Documents</th>
                            <th>Department</th>
                            <th>Cluster</th>
                            <th>Group</th>
                            <th>Status</th>
                            <th>Feedback</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($historyLogs as $log)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $log->user->name ?? 'Unknown' }}</td>
                                <td>{{ $log->documents }}</td>
                                <td>{{ $log->department }}</td>
                                <td>{{ $log->cluster }}</td>
                                <td>{{ $log->group_no }}</td>
                                <td>
                                    <span class="badge 
                                        {{ $log->status == 'Approved' ? 'bg-success' : ($log->status == 'Rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                        {{ $log->status }}
                                    </span>
                                </td>
                                <td>{{ $log->feedback ?? 'N/A' }}</td>
                                <td>{{ $log->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">No history logs available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalTitle">View Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-file-alt" style="font-size: 3rem; color: #007bff;"></i>
                    <p class="mt-3">Document preview would appear here</p>
                    <p class="text-muted">File: <span id="fileName">sample-document.pdf</span></p>
                    <a href="#" id="downloadBtn" class="btn btn-primary mt-3" target="_blank">
                        <i class="fas fa-download"></i> Download to View
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Approve Document</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to approve <strong id="approveDocName"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmApprove">Approve</button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Reject Document</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Reject <strong id="rejectDocName"></strong></p>
                <div class="mb-3">
                    <label class="form-label">Reason for rejection:</label>
                    <textarea class="form-control" id="rejectReason" rows="3" placeholder="Enter reason for rejection..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmReject">Reject</button>
            </div>
        </div>
    </div>
</div>

<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add Feedback</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Feedback for <strong id="feedbackDocName"></strong></p>
                <div class="mb-3">
                    <label class="form-label">Your feedback:</label>
                    <textarea class="form-control" id="feedbackText" rows="4" placeholder="Enter your feedback..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveFeedback">Save Feedback</button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                <h5 class="mt-3" id="successTitle">Success!</h5>
                <p class="mb-0" id="successMessage">Operation completed successfully!</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 4px solid #007bff !important;
}
.border-left-info {
    border-left: 4px solid #17a2b8 !important;
}
.border-left-success {
    border-left: 4px solid #28a745 !important;
}
.border-left-warning {
    border-left: 4px solid #ffc107 !important;
}
.border-left-danger {
    border-left: 4px solid #dc3545 !important;
}
.card {
    transition: transform 0.2s ease-in-out;
}
.card:hover {
    transform: translateY(-2px);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const groupSelect = document.getElementById('groupSelect');
    const requirementsContainer = document.getElementById('requirementsContainer');
    const departmentFilter = document.getElementById('departmentFilter');
    const clusterFilter = document.getElementById('clusterFilter');
    const applyFiltersBtn = document.getElementById('applyFilters');
    const clearFiltersBtn = document.getElementById('clearFilters');
    let currentSubmissionId = null;
    let allGroups = @json($groups);
    
    // Apply filters
    applyFiltersBtn.addEventListener('click', function() {
        const dept = departmentFilter.value;
        const cluster = clusterFilter.value;
        
        // Filter groups based on selections
        groupSelect.innerHTML = '<option value="">Select a Group</option>';
        
        // In a real scenario, you'd filter groups by department/cluster
        // For now, just show all groups
        allGroups.forEach(grp => {
            const option = document.createElement('option');
            option.value = grp;
            option.textContent = 'Group ' + grp;
            groupSelect.appendChild(option);
        });
        
        groupSelect.value = '';
        requirementsContainer.style.display = 'none';
    });
    
    // Clear filters
    clearFiltersBtn.addEventListener('click', function() {
        departmentFilter.value = '';
        clusterFilter.value = '';
        groupSelect.value = '';
        requirementsContainer.style.display = 'none';
    });
    
    groupSelect.addEventListener('change', function() {
        const selectedGroup = this.value;
        if (selectedGroup) {
            // Fetch submissions for selected group
            fetch(`/admin/submissions/group/${selectedGroup}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        updateRequirementsContainer(data.submissions);
                        requirementsContainer.style.display = 'block';
                    } else {
                        requirementsContainer.innerHTML = '<div class="alert alert-info text-center"><i class="fas fa-info-circle"></i> No submissions found for this group.</div>';
                        requirementsContainer.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    requirementsContainer.innerHTML = '<div class="alert alert-danger text-center"><i class="fas fa-exclamation-triangle"></i> Error loading submissions.</div>';
                    requirementsContainer.style.display = 'block';
                });
        } else {
            requirementsContainer.style.display = 'none';
        }
    });
    
    function updateRequirementsContainer(submissions) {
        let preOralHtml = '';
        let finalDefenseHtml = '';
        
        console.log('Updating requirements container with submissions:', submissions);
        
        submissions.forEach(submission => {
            const statusClass = submission.status === 'Approved' ? 'bg-success' : 
                               submission.status === 'Rejected' ? 'bg-danger' : 'bg-warning text-dark';
            const borderClass = submission.status === 'Approved' ? 'border-left-success' : 
                               submission.status === 'Rejected' ? 'border-left-danger' : 'border-left-warning';
            
            const studentName = submission.user ? submission.user.name : 'Unknown Student';
            const department = submission.department || 'N/A';
            const cluster = submission.cluster || 'N/A';
            
            const cardHtml = `
                <div class="col-md-6">
                    <div class="card ${borderClass} h-100">
                        <div class="card-body">
                            <h6 class="card-title">${submission.documents}</h6>
                            <p class="card-text text-muted mb-1">${submission.defense_type} submission</p>
                            <small class="text-muted d-block mb-1"><strong>Student:</strong> ${studentName}</small>
                            <small class="text-muted d-block mb-1"><strong>Department:</strong> ${department}</small>
                            <small class="text-muted d-block mb-3"><strong>Cluster:</strong> ${cluster}</small>
                            <div class="d-flex gap-2 mt-3">
                                <button class="btn btn-sm btn-outline-info view-btn" title="View" data-sid="${btoa(submission.id)}"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-outline-success approve-btn" title="Approve" data-sid="${btoa(submission.id)}"><i class="fas fa-check"></i></button>
                                <button class="btn btn-sm btn-outline-danger reject-btn" title="Reject" data-sid="${btoa(submission.id)}"><i class="fas fa-times"></i></button>
                                <button class="btn btn-sm btn-outline-secondary feedback-btn" title="Feedback" data-sid="${btoa(submission.id)}"><i class="fas fa-comment-dots"></i></button>
                            </div>
                            <span class="badge ${statusClass} mt-2" id="status-${submission.id}">${submission.status}</span>
                        </div>
                    </div>
                </div>
            `;
            
            if (submission.defense_type === 'Pre-Oral') {
                preOralHtml += cardHtml;
            } else if (submission.defense_type === 'Final Defense') {
                finalDefenseHtml += cardHtml;
            }
        });
        
        requirementsContainer.innerHTML = `
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-clipboard-list"></i> Pre-Oral Defense Requirements</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        ${preOralHtml || '<div class="col-12 text-center text-muted">No Pre-Oral submissions found.</div>'}
                    </div>
                </div>
            </div>
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> Final Defense Requirements</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        ${finalDefenseHtml || '<div class="col-12 text-center text-muted">No Final Defense submissions found.</div>'}
                    </div>
                </div>
            </div>
        `;
    }
    
    // Add event listeners for action buttons
    document.addEventListener('click', function(e) {
        // Handle both button and icon clicks
        let target = e.target;
        if (target.tagName === 'I') {
            target = target.parentElement;
        }
        if (!target.hasAttribute('data-sid')) {
            target = target.closest('button[data-sid]');
        }
        if (!target) return;
        
        const encodedId = target.getAttribute('data-sid');
        if (!encodedId) return;
        
        const submissionId = atob(encodedId);
        
        if (target.classList.contains('view-btn')) {
            currentSubmissionId = submissionId;
            document.getElementById('viewModalTitle').textContent = 'View Document';
            document.getElementById('fileName').textContent = 'document.pdf';
            document.getElementById('downloadBtn').href = `/admin/submissions/${submissionId}/download`;
            new bootstrap.Modal(document.getElementById('viewModal')).show();
        }
        
        if (target.classList.contains('approve-btn')) {
            currentSubmissionId = submissionId;
            document.getElementById('approveDocName').textContent = 'this document';
            new bootstrap.Modal(document.getElementById('approveModal')).show();
        }
        
        if (target.classList.contains('reject-btn')) {
            currentSubmissionId = submissionId;
            document.getElementById('rejectDocName').textContent = 'this document';
            document.getElementById('rejectReason').value = '';
            new bootstrap.Modal(document.getElementById('rejectModal')).show();
        }
        
        if (target.classList.contains('feedback-btn')) {
            currentSubmissionId = submissionId;
            document.getElementById('feedbackDocName').textContent = 'this document';
            document.getElementById('feedbackText').value = '';
            new bootstrap.Modal(document.getElementById('feedbackModal')).show();
        }
    });
    
    // Confirm approve
    document.getElementById('confirmApprove').addEventListener('click', function() {
        if (!currentSubmissionId) {
            alert('No submission selected');
            return;
        }
        
        console.log('Approving submission ID:', currentSubmissionId);
        
        fetch(`/admin/submissions/${currentSubmissionId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: 'Approved' })
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Response is not JSON');
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                document.getElementById('successTitle').textContent = 'Approved!';
                document.getElementById('successMessage').textContent = 'Document approved successfully!';
                new bootstrap.Modal(document.getElementById('successModal')).show();
                // Refresh the group data immediately
                const selectedGroup = groupSelect.value;
                if (selectedGroup) {
                    fetch(`/admin/submissions/group/${selectedGroup}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(refreshData => {
                            if (refreshData.success) {
                                updateRequirementsContainer(refreshData.submissions);
                            }
                        })
                        .catch(error => console.error('Error refreshing data:', error));
                }
            } else {
                alert('Error approving document: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error approving document: ' + error.message);
        });
        
        bootstrap.Modal.getInstance(document.getElementById('approveModal')).hide();
    });
    
    // Confirm reject
    document.getElementById('confirmReject').addEventListener('click', function() {
        if (!currentSubmissionId) return;
        
        const reason = document.getElementById('rejectReason').value;
        if (!reason.trim()) {
            alert('Please provide a reason for rejection.');
            return;
        }
        
        fetch(`/admin/submissions/${currentSubmissionId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: 'Rejected', feedback: reason })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Response is not JSON');
            }
            return response.json();
        })
        .then(data => {
            console.log('Reject response data:', data);
            if (data.success) {
                document.getElementById('successTitle').textContent = 'Rejected!';
                document.getElementById('successMessage').textContent = 'Document rejected successfully!';
                new bootstrap.Modal(document.getElementById('successModal')).show();
                // Refresh the group data immediately
                const selectedGroup = groupSelect.value;
                if (selectedGroup) {
                    fetch(`/admin/submissions/group/${selectedGroup}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(refreshData => {
                            if (refreshData.success) {
                                updateRequirementsContainer(refreshData.submissions);
                            }
                        })
                        .catch(error => console.error('Error refreshing data:', error));
                }
            } else {
                alert('Error rejecting document: ' + (data.message || data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error rejecting document: ' + error.message);
        });
        
        bootstrap.Modal.getInstance(document.getElementById('rejectModal')).hide();
    });
    
    // Save feedback
    document.getElementById('saveFeedback').addEventListener('click', function() {
        if (!currentSubmissionId) return;
        
        const feedback = document.getElementById('feedbackText').value;
        if (!feedback.trim()) {
            alert('Please enter your feedback.');
            return;
        }
        
        fetch(`/admin/submissions/${currentSubmissionId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ feedback: feedback })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Response is not JSON');
            }
            return response.json();
        })
        .then(data => {
            console.log('Feedback response data:', data);
            if (data.success) {
                document.getElementById('successTitle').textContent = 'Feedback Saved!';
                document.getElementById('successMessage').textContent = 'Feedback saved successfully!';
                new bootstrap.Modal(document.getElementById('successModal')).show();
                // Refresh the group data immediately
                const selectedGroup = groupSelect.value;
                if (selectedGroup) {
                    fetch(`/admin/submissions/group/${selectedGroup}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(refreshData => {
                            if (refreshData.success) {
                                updateRequirementsContainer(refreshData.submissions);
                            }
                        })
                        .catch(error => console.error('Error refreshing data:', error));
                }
            } else {
                alert('Error saving feedback: ' + (data.message || data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error saving feedback: ' + error.message);
        });
        
        bootstrap.Modal.getInstance(document.getElementById('feedbackModal')).hide();
    });
});
</script>

@endsection