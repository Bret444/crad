@extends('layouts.coordinator')

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
                        <option value="{{ Auth::user()->department }}" selected>{{ Auth::user()->department }}</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Cluster</label>
                    <select name="cluster" id="clusterFilter" class="form-select">
                        <option value="">All</option>
                        <option value="4101">Cluster 4101</option>
                        <option value="4102">Cluster 4102</option>
                        <option value="4103">Cluster 4103</option>
                        <option value="4104">Cluster 4104</option>
                        <option value="4105">Cluster 4105</option>
                        <option value="4106">Cluster 4106</option>
                        <option value="4107">Cluster 4107</option>
                        <option value="4108">Cluster 4108</option>
                        <option value="4109">Cluster 4109</option>
                        <option value="4110">Cluster 4110</option>
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
                        @foreach($groups as $group)
                            <option value="{{ $group->group_number }}" 
                                data-cluster="{{ $group->cluster_or_section }}" 
                                data-department="{{ $group->department }}">
                                Group {{ $group->group_number }} - {{ $group->cluster_or_section }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Requirements Containers -->
    <div id="requirementsContainer" style="display: none;"></div>

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
                    <tbody id="historyTableBody">
                        <tr>
                            <td colspan="9" class="text-center text-muted">No history logs available.</td>
                        </tr>
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
.border-left-primary { border-left: 4px solid #007bff !important; }
.border-left-info { border-left: 4px solid #17a2b8 !important; }
.border-left-success { border-left: 4px solid #28a745 !important; }
.border-left-warning { border-left: 4px solid #ffc107 !important; }
.border-left-danger { border-left: 4px solid #dc3545 !important; }
.card { transition: transform 0.2s ease-in-out; }
.card:hover { transform: translateY(-2px); }
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
    
    applyFiltersBtn.addEventListener('click', function() {
        groupSelect.innerHTML = '<option value="">Select a Group</option>';
        for (let i = 1; i <= 10; i++) {
            groupSelect.innerHTML += `<option value="${i}">Group ${i}</option>`;
        }
        requirementsContainer.style.display = 'none';
    });
    
    clearFiltersBtn.addEventListener('click', function() {
        departmentFilter.value = '';
        clusterFilter.value = '';
        groupSelect.value = '';
        requirementsContainer.style.display = 'none';
    });
    
    groupSelect.addEventListener('change', function() {
        if (this.value) {
            fetch(`/coordinator/submissions/group/${this.value}`)
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.submissions.length > 0) {
                        updateRequirementsContainer(data.submissions);
                        requirementsContainer.style.display = 'block';
                    } else {
                        requirementsContainer.innerHTML = '<div class="alert alert-info">No submissions found for this group.</div>';
                        requirementsContainer.style.display = 'block';
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    requirementsContainer.innerHTML = '<div class="alert alert-danger">Error loading submissions.</div>';
                    requirementsContainer.style.display = 'block';
                });
        }
    });
    
    function updateRequirementsContainer(submissions) {
        let preOral = '', finalDef = '';
        submissions.forEach(s => {
            const status = s.status === 'Approved' ? 'bg-success' : s.status === 'Rejected' ? 'bg-danger' : 'bg-warning';
            const border = s.status === 'Approved' ? 'border-left-success' : s.status === 'Rejected' ? 'border-left-danger' : 'border-left-warning';
            const card = `
                <div class="col-md-6">
                    <div class="card ${border} h-100">
                        <div class="card-body">
                            <h6>${s.documents}</h6>
                            <p class="text-muted mb-1">${s.defense_type}</p>
                            <small><strong>Student:</strong> ${s.user?.name || 'Unknown'}</small><br>
                            <small><strong>Dept:</strong> ${s.department || 'N/A'}</small><br>
                            <small><strong>Cluster:</strong> ${s.cluster || 'N/A'}</small>
                            <div class="d-flex gap-2 mt-3">
                                <button class="btn btn-sm btn-outline-info view-btn" data-sid="${btoa(s.id)}"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-outline-success approve-btn" data-sid="${btoa(s.id)}"><i class="fas fa-check"></i></button>
                                <button class="btn btn-sm btn-outline-danger reject-btn" data-sid="${btoa(s.id)}"><i class="fas fa-times"></i></button>
                                <button class="btn btn-sm btn-outline-secondary feedback-btn" data-sid="${btoa(s.id)}"><i class="fas fa-comment-dots"></i></button>
                            </div>
                            <span class="badge ${status} mt-2">${s.status}</span>
                        </div>
                    </div>
                </div>
            `;
            if (s.defense_type === 'Pre-Oral') preOral += card;
            else if (s.defense_type === 'Final Defense') finalDef += card;
        });
        requirementsContainer.innerHTML = `
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white"><h5 class="mb-0">Pre-Oral Defense</h5></div>
                <div class="card-body"><div class="row g-4">${preOral || '<div class="col-12 text-center text-muted">No submissions.</div>'}</div></div>
            </div>
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-success text-white"><h5 class="mb-0">Final Defense</h5></div>
                <div class="card-body"><div class="row g-4">${finalDef || '<div class="col-12 text-center text-muted">No submissions.</div>'}</div></div>
            </div>
        `;
    }
    
    document.addEventListener('click', function(e) {
        let t = e.target.closest('button[data-sid]');
        if (!t) return;
        const id = atob(t.getAttribute('data-sid'));
        currentSubmissionId = id;
        
        if (t.classList.contains('view-btn')) {
            document.getElementById('downloadBtn').href = `/coordinator/submissions/${id}/download`;
            new bootstrap.Modal(document.getElementById('viewModal')).show();
        }
        if (t.classList.contains('approve-btn')) new bootstrap.Modal(document.getElementById('approveModal')).show();
        if (t.classList.contains('reject-btn')) new bootstrap.Modal(document.getElementById('rejectModal')).show();
        if (t.classList.contains('feedback-btn')) new bootstrap.Modal(document.getElementById('feedbackModal')).show();
    });
    
    document.getElementById('confirmApprove').addEventListener('click', function() {
        fetch(`/coordinator/submissions/${currentSubmissionId}/update-status`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
            body: JSON.stringify({status: 'Approved'})
        }).then(r => r.json()).then(() => {
            new bootstrap.Modal(document.getElementById('successModal')).show();
            groupSelect.dispatchEvent(new Event('change'));
        });
        bootstrap.Modal.getInstance(document.getElementById('approveModal')).hide();
    });
    
    document.getElementById('confirmReject').addEventListener('click', function() {
        const reason = document.getElementById('rejectReason').value;
        if (!reason.trim()) return alert('Provide reason');
        fetch(`/coordinator/submissions/${currentSubmissionId}/update-status`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
            body: JSON.stringify({status: 'Rejected', feedback: reason})
        }).then(r => r.json()).then(() => {
            new bootstrap.Modal(document.getElementById('successModal')).show();
            groupSelect.dispatchEvent(new Event('change'));
        });
        bootstrap.Modal.getInstance(document.getElementById('rejectModal')).hide();
    });
    
    document.getElementById('saveFeedback').addEventListener('click', function() {
        const feedback = document.getElementById('feedbackText').value;
        if (!feedback.trim()) return alert('Enter feedback');
        fetch(`/coordinator/submissions/${currentSubmissionId}/update-status`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
            body: JSON.stringify({feedback: feedback})
        }).then(r => r.json()).then(() => {
            new bootstrap.Modal(document.getElementById('successModal')).show();
            groupSelect.dispatchEvent(new Event('change'));
        });
        bootstrap.Modal.getInstance(document.getElementById('feedbackModal')).hide();
    });
});
</script>
@endsection
