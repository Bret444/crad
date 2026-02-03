@extends('layouts.coordinator')

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .section-occupied {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        opacity: 0.7;
    }
    .section-available {
        transition: all 0.2s ease;
    }
    .section-available:hover {
        background-color: #e7f3ff;
        border-radius: 0.375rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Manage Adviser</h2>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0 text-success fw-bold">Adviser Management</h5>
        <div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addAdviserModal">
                <i class="bi bi-person-plus-fill me-1"></i> Add Adviser
            </button>
            <button class="btn btn-secondary ms-2" data-bs-toggle="modal" data-bs-target="#archiveAdviserModal">
                <i class="bi bi-archive me-1"></i> Inactive
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            @if(isset($advisers) && $advisers->count() > 0)
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Department</th>
                            <th>Name</th>
                            <th>Expertise</th>
                            <th>Sections</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="adviserTableBody">
                        @foreach($advisers as $adviser)
                            <tr data-department="{{ $adviser->department }}">
                                <td>{{ $adviser->department }}</td>
                                <td>{{ $adviser->name }}</td>
                                <td>{{ $adviser->expertise }}</td>
                                <td>
                                    @php
                                        $adviserSections = is_array($adviser->sections) ? $adviser->sections : (is_string($adviser->sections) ? json_decode($adviser->sections, true) : [$adviser->sections]);
                                        if (!is_array($adviserSections)) {
                                            $adviserSections = [];
                                        }
                                        // Group sections by cluster
                                        $groupedByCluster = [];
                                        foreach($adviserSections as $group) {
                                            if (is_numeric($group) && $group > 0) {
                                                $cluster = 4101 + floor(($group - 1) / 10);
                                                if (!isset($groupedByCluster[$cluster])) {
                                                    $groupedByCluster[$cluster] = [];
                                                }
                                                $groupedByCluster[$cluster][] = $group;
                                            }
                                        }
                                        ksort($groupedByCluster);
                                    @endphp
                                    @foreach($groupedByCluster as $cluster => $groups)
                                        <div>
                                            <span class="badge bg-primary me-1 section-badge" style="cursor:pointer;" data-adviser-id="{{ $adviser->id }}" data-cluster="{{ $cluster }}">
                                                {{ $cluster }} ({{ count($groups) }}) <i class="bi bi-chevron-down" id="icon_{{ $adviser->id }}_{{ $cluster }}"></i>
                                            </span>
                                        </div>
                                        <div id="groups_{{ $adviser->id }}_{{ $cluster }}" style="display:none;" class="ms-4">
                                            @foreach($groups as $group)
                                                <div><span class="badge bg-secondary me-1">G{{ $group }}</span></div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-success me-1" data-bs-toggle="modal"
                                        data-bs-target="#manageAdvisoryModal{{ $adviser->id }}" title="Manage Advisory">
                                        <i class="bi bi-list-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal"
                                        data-bs-target="#editAdviserModal{{ $adviser->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-secondary" onclick="deleteAdviser({{ $adviser->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-center text-muted">No advisers found. Add one to get started.</p>
            @endif
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationTitle">Are you sure?</h5>
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
<div class="modal fade" id="successModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-success">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="successTitle">Success</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="bi bi-check-circle" style="font-size: 3rem; color: #34a853;"></i>
                    <p id="successMessage" class="mt-3"></p>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Adviser Modal -->
<div class="modal fade" id="addAdviserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('advisers.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Add New Adviser</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="department" class="form-label">Department *</label>
                        <input type="text" name="department" class="form-control" value="{{ Auth::user()->department }}" readonly required>
                    </div>
                    <div class="mb-3">
                        <label for="cluster_select" class="form-label">Cluster *</label>
                        <div class="d-flex gap-2">
                            <select id="cluster_select" class="form-select" required>
                                <option value="">Select Cluster</option>
                                @foreach(['4101', '4102', '4103', '4104', '4105', '4106', '4107', '4108', '4109', '4110'] as $cluster)
                                    <option value="{{ $cluster }}">{{ $cluster }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-primary" id="addMoreClustersBtn" style="white-space: nowrap;">
                                <i class="bi bi-plus-circle"></i> Add More
                            </button>
                        </div>
                    </div>
                    <div id="additional_clusters_container"></div>
                    <div class="mb-3" id="groups_container" style="display:none;">
                        <label class="form-label">Select Group *</label>
                        <div class="row" id="groups_list"></div>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="E.g., Prof. Maria Santos" required />
                    </div>
                    <div class="mb-3">
                        <label for="expertise" class="form-label">Expertise *</label>
                        <select name="expertise" class="form-select" required onchange="toggleAdviserOthersInput(this)">
                            <option value="">Select Expertise</option>
                            <option value="Instructor">Instructor</option>
                            <option value="Assistant Professor">Assistant Professor</option>
                            <option value="Associate Professor">Associate Professor</option>
                            <option value="Professor">Professor</option>
                            <option value="Doctoral">Doctoral</option>
                            <option value="Industry Expert">Industry Expert</option>
                            <option value="Research Specialist">Research Specialist</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                    <div class="mb-3" id="adviserOthersInputDiv" style="display: none;">
                        <label for="adviserOthersInput" class="form-label">Specify Other Expertise</label>
                        <input type="text" id="adviserOthersInput" name="others_expertise" class="form-control" placeholder="Enter specific expertise">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Adviser</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Adviser Modals -->
@if(isset($advisers))
    @foreach($advisers as $adviser)
        <div class="modal fade" id="editAdviserModal{{ $adviser->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="editAdviserForm{{ $adviser->id }}" action="{{ route('advisers.update', $adviser->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Adviser Info</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Department *</label>
                                <input type="text" name="department" class="form-control" value="{{ $adviser->department }}" readonly required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Name *</label>
                                <input type="text" name="name" class="form-control" value="{{ $adviser->name }}" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Expertise *</label>
                                <select name="expertise" class="form-select" required>
                                    <option value="Instructor" {{ $adviser->expertise == 'Instructor' ? 'selected' : '' }}>Instructor</option>
                                    <option value="Assistant Professor" {{ $adviser->expertise == 'Assistant Professor' ? 'selected' : '' }}>Assistant Professor</option>
                                    <option value="Associate Professor" {{ $adviser->expertise == 'Associate Professor' ? 'selected' : '' }}>Associate Professor</option>
                                    <option value="Professor" {{ $adviser->expertise == 'Professor' ? 'selected' : '' }}>Professor</option>
                                    <option value="Doctoral" {{ $adviser->expertise == 'Doctoral' ? 'selected' : '' }}>Doctoral</option>
                                    <option value="Industry Expert" {{ $adviser->expertise == 'Industry Expert' ? 'selected' : '' }}>Industry Expert</option>
                                    <option value="Research Specialist" {{ $adviser->expertise == 'Research Specialist' ? 'selected' : '' }}>Research Specialist</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success"><i class="bi bi-check-circle me-1"></i>Update Info</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endif

<!-- Manage Advisory Modals -->
@if(isset($advisers))
    @foreach($advisers as $adviser)
        <div class="modal fade" id="manageAdvisoryModal{{ $adviser->id }}" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <form id="manageAdvisoryForm{{ $adviser->id }}" action="{{ route('advisers.manageAdvisory', $adviser->id) }}" method="POST">
                        @csrf
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title"><i class="bi bi-list-check me-2"></i>Manage Advisory for {{ $adviser->name }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <strong>Currently Assigned:</strong> 
                                @php
                                    $currentSections = is_array($adviser->sections) ? $adviser->sections : [];
                                    $currentClusters = [];
                                    foreach($currentSections as $g) {
                                        if (is_numeric($g) && $g > 0) {
                                            $c = 4101 + floor(($g - 1) / 10);
                                            if (!isset($currentClusters[$c])) $currentClusters[$c] = [];
                                            $currentClusters[$c][] = $g;
                                        }
                                    }
                                @endphp
                                @if(count($currentClusters) > 0)
                                    @foreach($currentClusters as $c => $gs)
                                        <span class="badge bg-primary">{{ $c }}: G{{ implode(', G', $gs) }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">No groups assigned yet</span>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Select Groups from All Clusters</label>
                                <div class="border rounded p-3" style="background-color: #f8f9fa; max-height: 400px; overflow-y: auto;">
                                    <div id="manage_advisory_all_groups_{{ $adviser->id }}"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success"><i class="bi bi-check-circle me-1"></i>Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endif

<!-- Archive Adviser Modal -->
<div class="modal fade" id="archiveAdviserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white d-flex justify-content-between align-items-center">
                <h5 class="modal-title">Inactive Advisers</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="min-height: 300px; max-height: 500px; overflow-y: auto;">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Expertise</th>
                                <th>Sections</th>
                                <th>Inactive Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="archivedAdvisersBody">
                            <tr><td colspan="6" class="text-center text-muted">Loading...</td></tr>
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

<script>
let confirmationCallback = null;

function toggleGroups(adviserId, cluster) {
    const groupsDiv = document.getElementById(`groups_${adviserId}_${cluster}`);
    const icon = document.getElementById(`icon_${adviserId}_${cluster}`);
    if (groupsDiv.style.display === 'none') {
        groupsDiv.style.display = 'inline';
        icon.className = 'bi bi-chevron-up';
    } else {
        groupsDiv.style.display = 'none';
        icon.className = 'bi bi-chevron-down';
    }
}

function showConfirmation(message, callback, buttonText = 'Confirm', buttonClass = 'btn-danger') {
    const confirmationModal = document.getElementById('confirmationModal');
    const modal = new bootstrap.Modal(confirmationModal);
    document.getElementById('confirmationMessage').textContent = message;
    const confirmBtn = document.getElementById('confirmationBtn');
    confirmBtn.textContent = buttonText;
    confirmBtn.className = `btn ${buttonClass}`;
    confirmationCallback = callback;
    modal.show();
}

document.getElementById('confirmationBtn').addEventListener('click', function() {
    if (confirmationCallback) {
        confirmationCallback();
        bootstrap.Modal.getInstance(document.getElementById('confirmationModal')).hide();
        confirmationCallback = null;
    }
});

function toggleAdviserOthersInput(select) {
    const othersDiv = document.getElementById('adviserOthersInputDiv');
    const othersInput = document.getElementById('adviserOthersInput');
    
    if (select.value === 'Others') {
        othersDiv.style.display = 'block';
        othersInput.required = true;
    } else {
        othersDiv.style.display = 'none';
        othersInput.required = false;
        othersInput.value = '';
    }
}

function updateAdviserSections() {
    const modal = document.getElementById('addAdviserModal');
    const dept = modal.querySelector('input[name="department"]').value;
    const checkboxes = modal.querySelectorAll('.section-checkbox');
    const assignmentsData = @json(isset($assignments) ? $assignments : []);
    
    checkboxes.forEach(checkbox => {
        const section = checkbox.dataset.section;
        const indicator = checkbox.parentElement.querySelector('.occupied-indicator');
        const label = checkbox.parentElement.querySelector('label');
        const formCheck = checkbox.parentElement.parentElement;
        
        const isOccupied = assignmentsData.some(assignment => 
            assignment.department === dept && assignment.section === section
        );
        
        if (isOccupied) {
            checkbox.disabled = true;
            checkbox.checked = false;
            label.classList.add('text-muted', 'text-decoration-line-through');
            formCheck.classList.add('section-occupied', 'p-2');
            formCheck.classList.remove('section-available');
            indicator.innerHTML = '<span class="badge bg-danger ms-2"><i class="bi bi-x-circle me-1"></i>Taken</span>';
        } else {
            checkbox.disabled = false;
            label.classList.remove('text-muted', 'text-decoration-line-through');
            formCheck.classList.remove('section-occupied', 'p-2');
            formCheck.classList.add('section-available', 'p-1');
            indicator.innerHTML = '<span class="badge bg-success ms-2"><i class="bi bi-check-circle me-1"></i>Available</span>';
        }
    });
}

function deleteAdviser(adviserId) {
    showConfirmation(
        'Are you sure you want to archive this adviser? This action can be undone by restoring it.',
        function() {
            fetch(`/advisers/${adviserId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('successMessage').textContent = 'Adviser has been successfully archived!';
                    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                    document.getElementById('successModal').addEventListener('hidden.bs.modal', () => {
                        location.reload();
                    }, { once: true });
                } else {
                    alert('Error archiving adviser: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while archiving the adviser.');
            });
        },
        'Archive Adviser'
    );
}

function loadArchivedAdvisers() {
    fetch('/advisers/archived')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('archivedAdvisersBody');
            if (!tbody) return;
            
            if (!data || data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No inactive advisers</td></tr>';
                return;
            }
            
            tbody.innerHTML = data.map(adviser => {
                return `
                <tr>
                    <td>${adviser.name || ''}</td>
                    <td>${adviser.department || ''}</td>
                    <td>${adviser.expertise || ''}</td>
                    <td>${adviser.sections ? (Array.isArray(adviser.sections) ? adviser.sections.join(', ') : adviser.sections) : 'N/A'}</td>
                    <td>${adviser.deleted_at ? new Date(adviser.deleted_at).toLocaleDateString() : ''}</td>
                    <td>
                        <button class="btn btn-sm btn-secondary" type="button" onclick="restoreAdviser(${adviser.id})">
                            <i class="bi bi-arrow-clockwise"></i> Restore
                        </button>
                    </td>
                </tr>
                `;
            }).join('');
        })
        .catch(error => {
            console.error('Error loading archived advisers:', error);
            const tbody = document.getElementById('archivedAdvisersBody');
            if (tbody) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Error loading data</td></tr>';
            }
        });
}

function restoreAdviser(adviserId) {
    showConfirmation(
        'Are you sure you want to restore this adviser?',
        function() {
            fetch(`/advisers/${adviserId}/restore`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('successMessage').textContent = 'Adviser has been successfully restored!';
                    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                    document.getElementById('successModal').addEventListener('hidden.bs.modal', () => {
                        loadArchivedAdvisers();
                        location.reload();
                    }, { once: true });
                } else {
                    alert('Error restoring adviser: ' + data.message);
                }
            });
        },
        'Restore Adviser',
        'btn-secondary'
    );
}

document.addEventListener('DOMContentLoaded', function() {
    // Event delegation for section badges
    document.addEventListener('click', function(e) {
        if (e.target.closest('.section-badge')) {
            const badge = e.target.closest('.section-badge');
            const adviserId = badge.dataset.adviserId;
            const cluster = badge.dataset.cluster;
            const groupsSpan = document.getElementById(`groups_${adviserId}_${cluster}`);
            const icon = document.getElementById(`icon_${adviserId}_${cluster}`);
            
            if (groupsSpan && icon) {
                if (groupsSpan.style.display === 'none') {
                    groupsSpan.style.display = 'block';
                    icon.className = 'bi bi-chevron-up';
                } else {
                    groupsSpan.style.display = 'none';
                    icon.className = 'bi bi-chevron-down';
                }
            }
        }
    });
    
    const clusterSelect = document.getElementById('cluster_select');
    const groupsContainer = document.getElementById('groups_container');
    const groupsList = document.getElementById('groups_list');
    let additionalClusterCount = 0;
    
    document.getElementById('addMoreClustersBtn').addEventListener('click', function() {
        additionalClusterCount++;
        const container = document.getElementById('additional_clusters_container');
        const newClusterHtml = `
            <div class="mb-3 additional-cluster" id="additional_cluster_${additionalClusterCount}">
                <label class="form-label">Additional Cluster</label>
                <div class="d-flex gap-2">
                    <select class="form-select additional-cluster-select" data-cluster-id="${additionalClusterCount}">
                        <option value="">Select Cluster</option>
                        @foreach(['4101', '4102', '4103', '4104', '4105', '4106', '4107', '4108', '4109', '4110'] as $cluster)
                            <option value="{{ $cluster }}">{{ $cluster }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-outline-danger" onclick="removeAdditionalCluster(${additionalClusterCount})">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                <div class="additional-groups-container" id="additional_groups_container_${additionalClusterCount}" style="display:none;">
                    <div class="row mt-2" id="additional_groups_list_${additionalClusterCount}"></div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', newClusterHtml);
        
        // Add event listener to new cluster select
        const newSelect = document.querySelector(`[data-cluster-id="${additionalClusterCount}"]`);
        newSelect.addEventListener('change', function() {
            showAdditionalGroups(additionalClusterCount, this.value);
        });
    });
    
    window.removeAdditionalCluster = function(id) {
        document.getElementById(`additional_cluster_${id}`).remove();
    };
    
    window.showAdditionalGroups = function(clusterId, cluster) {
        const container = document.getElementById(`additional_groups_container_${clusterId}`);
        const groupsList = document.getElementById(`additional_groups_list_${clusterId}`);
        
        if (!cluster) {
            container.style.display = 'none';
            return;
        }
        
        const clusterNum = parseInt(cluster);
        const offset = (clusterNum - 4101) * 10;
        const dept = document.querySelector('input[name="department"]').value;
        const advisersData = @json($advisers);
        const takenGroups = [];
        
        advisersData.forEach(adviser => {
            if (adviser.department === dept && adviser.sections) {
                const sections = Array.isArray(adviser.sections) ? adviser.sections : [adviser.sections];
                takenGroups.push(...sections.map(s => parseInt(s)));
            }
        });
        
        groupsList.innerHTML = '';
        for (let i = 1; i <= 10; i++) {
            const groupNum = offset + i;
            const isTaken = takenGroups.includes(groupNum);
            groupsList.innerHTML += `
                <div class="col-md-4 col-sm-6 mb-2">
                    <div class="form-check ${isTaken ? 'section-occupied p-2' : ''}">
                        <input class="form-check-input" type="checkbox" name="group_number[]" value="${groupNum}" id="additionalGroup${clusterId}_${groupNum}" ${isTaken ? 'disabled' : ''}>
                        <label class="form-check-label ${isTaken ? 'text-muted text-decoration-line-through' : ''}" for="additionalGroup${clusterId}_${groupNum}">
                            Group ${groupNum} ${isTaken ? '<span class="badge bg-danger ms-1">Taken</span>' : ''}
                        </label>
                    </div>
                </div>
            `;
        }
        container.style.display = 'block';
    };
    
    clusterSelect.addEventListener('change', function() {
        const cluster = this.value;
        if (!cluster) {
            groupsContainer.style.display = 'none';
            return;
        }
        
        const clusterNum = parseInt(cluster);
        const offset = (clusterNum - 4101) * 10;
        const dept = document.querySelector('input[name="department"]').value;
        const advisersData = @json($advisers);
        const takenGroups = [];
        
        // Find groups already assigned to advisers in this department
        advisersData.forEach(adviser => {
            if (adviser.department === dept && adviser.sections) {
                const sections = Array.isArray(adviser.sections) ? adviser.sections : [adviser.sections];
                takenGroups.push(...sections.map(s => parseInt(s)));
            }
        });
        
        groupsList.innerHTML = '';
        
        for (let i = 1; i <= 10; i++) {
            const groupNum = offset + i;
            const isTaken = takenGroups.includes(groupNum);
            groupsList.innerHTML += `
                <div class="col-md-4 col-sm-6 mb-2">
                    <div class="form-check ${isTaken ? 'section-occupied p-2' : ''}">
                        <input class="form-check-input" type="checkbox" name="group_number[]" value="${groupNum}" id="group${groupNum}" ${isTaken ? 'disabled' : ''}>
                        <label class="form-check-label ${isTaken ? 'text-muted text-decoration-line-through' : ''}" for="group${groupNum}">
                            Group ${groupNum} ${isTaken ? '<span class="badge bg-danger ms-1">Taken</span>' : ''}
                        </label>
                    </div>
                </div>
            `;
        }
        groupsContainer.style.display = 'block';
    });
    
    const adviserModal = document.getElementById('archiveAdviserModal');
    if (adviserModal) {
        adviserModal.addEventListener('show.bs.modal', function() {
            loadArchivedAdvisers();
        });
    }

    document.getElementById('addAdviserModal').addEventListener('show.bs.modal', () => {
        const modal = document.getElementById('addAdviserModal');
        document.getElementById('cluster_select').value = '';
        document.getElementById('groups_container').style.display = 'none';
        document.getElementById('groups_list').innerHTML = '';
        document.getElementById('additional_clusters_container').innerHTML = '';
        additionalClusterCount = 0;
    });

    @if(isset($advisers))
        @foreach($advisers as $adviser)
            const form{{ $adviser->id }} = document.getElementById('editAdviserForm{{ $adviser->id }}');
            if (form{{ $adviser->id }}) {
                form{{ $adviser->id }}.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const modalElement = document.getElementById('editAdviserModal{{ $adviser->id }}');
                    const formAction = this.action;
                    
                    showConfirmation(
                        'Are you sure you want to update this adviser information?',
                        function() {
                            fetch(formAction, {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => {
                                if (response.ok) {
                                    const modal = bootstrap.Modal.getInstance(modalElement);
                                    if (modal) modal.hide();
                                    
                                    document.getElementById('successMessage').textContent = 'Adviser information has been successfully updated!';
                                    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                                    successModal.show();
                                    
                                    document.getElementById('successModal').addEventListener('hidden.bs.modal', () => {
                                        location.reload();
                                    }, { once: true });
                                } else {
                                    alert('Error updating adviser. Please try again.');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('An error occurred while updating the adviser.');
                            });
                        },
                        'Update Adviser',
                        'btn-primary'
                    );
                });
            }
        @endforeach
    @endif
    
    // Manage Advisory modal handlers
    @if(isset($advisers))
        @foreach($advisers as $adviser)
            const manageAdvisoryAllGroups{{ $adviser->id }} = document.getElementById('manage_advisory_all_groups_{{ $adviser->id }}');
            const manageAdvisoryForm{{ $adviser->id }} = document.getElementById('manageAdvisoryForm{{ $adviser->id }}');
            
            if (manageAdvisoryAllGroups{{ $adviser->id }}) {
                const dept = '{{ $adviser->department }}';
                const advisersData = @json($advisers);
                const currentAdviserId = {{ $adviser->id }};
                const currentAdviserSections = @json($adviser->sections ?? []);
                const takenGroups = [];
                
                advisersData.forEach(adviser => {
                    if (adviser.id !== currentAdviserId && adviser.department === dept && adviser.sections) {
                        const sections = Array.isArray(adviser.sections) ? adviser.sections : [adviser.sections];
                        takenGroups.push(...sections.map(s => parseInt(s)));
                    }
                });
                
                let html = '';
                for (let clusterNum = 4101; clusterNum <= 4110; clusterNum++) {
                    const offset = (clusterNum - 4101) * 10;
                    html += `<div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Cluster ${clusterNum}</strong>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-success me-1 check-cluster" data-cluster="${clusterNum}">
                                    <i class="bi bi-check-square"></i> Check All
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger uncheck-cluster" data-cluster="${clusterNum}">
                                    <i class="bi bi-square"></i> Uncheck All
                                </button>
                            </div>
                        </div>
                        <div class="row g-2 mt-1">`;
                    
                    for (let i = 1; i <= 10; i++) {
                        const groupNum = offset + i;
                        const isTaken = takenGroups.includes(groupNum);
                        const isCurrentlyAssigned = currentAdviserSections.includes(groupNum);
                        html += `
                            <div class="col-md-3 col-sm-4">
                                <div class="form-check ${isTaken ? 'section-occupied p-2' : ''}">
                                    <input class="form-check-input cluster-${clusterNum}-checkbox" type="checkbox" name="group_number[]" value="${groupNum}" id="manageAdvisoryGroup{{ $adviser->id }}_${groupNum}" ${isTaken ? 'disabled' : ''} ${isCurrentlyAssigned ? 'checked' : ''}>
                                    <label class="form-check-label ${isTaken ? 'text-muted text-decoration-line-through' : ''}" for="manageAdvisoryGroup{{ $adviser->id }}_${groupNum}">
                                        G${groupNum} ${isTaken ? '<span class="badge bg-danger ms-1">Taken</span>' : isCurrentlyAssigned ? '<span class="badge bg-success ms-1">Assigned</span>' : ''}
                                    </label>
                                </div>
                            </div>
                        `;
                    }
                    html += '</div></div>';
                }
                manageAdvisoryAllGroups{{ $adviser->id }}.innerHTML = html;
                
                // Add event listeners for Check All buttons
                document.querySelectorAll('#manage_advisory_all_groups_{{ $adviser->id }} .check-cluster').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const cluster = this.dataset.cluster;
                        document.querySelectorAll(`.cluster-${cluster}-checkbox:not(:disabled)`).forEach(cb => cb.checked = true);
                    });
                });
                
                // Add event listeners for Uncheck All buttons
                document.querySelectorAll('#manage_advisory_all_groups_{{ $adviser->id }} .uncheck-cluster').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const cluster = this.dataset.cluster;
                        document.querySelectorAll(`.cluster-${cluster}-checkbox:not(:disabled)`).forEach(cb => cb.checked = false);
                    });
                });
            }
            
            if (manageAdvisoryForm{{ $adviser->id }}) {
                manageAdvisoryForm{{ $adviser->id }}.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const modalElement = document.getElementById('manageAdvisoryModal{{ $adviser->id }}');
                    
                    fetch(this.action, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const modal = bootstrap.Modal.getInstance(modalElement);
                            if (modal) modal.hide();
                            
                            document.getElementById('successMessage').textContent = 'Advisory groups updated successfully!';
                            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                            successModal.show();
                            
                            document.getElementById('successModal').addEventListener('hidden.bs.modal', () => {
                                location.reload();
                            }, { once: true });
                        } else {
                            alert('Error: ' + (data.message || 'Failed to update advisory'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while updating advisory.');
                    });
                });
            }
        @endforeach
    @endif
});
</script>
@endsection
