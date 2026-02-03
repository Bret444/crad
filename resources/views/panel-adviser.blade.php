@extends('layouts.app')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<style>
    /* --- DESIGN TOKENS --- */
    :root {
        --primary-blue: #1a73e8;
        --dark-blue-button: #003366;
        --light-bg: #f5f7fa;
        --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        --dark-text: #202124;
        --group-stripe: #f7f7f7;
        --status-green: #34a853;
        --status-yellow: #fbbc05;
        --status-red: #ea4335;
        --status-light-green: #e6f4ea;
        --status-light-red: #fce8e6;
    }

    /* --- GENERAL STYLES & FONT --- */
    body {
        background-color: var(--light-bg);
        font-family: 'Roboto', sans-serif;
        color: var(--dark-text);
    }

    #main-container-panel-adviser {
        max-width: 1400px;
        margin: 0 auto;
    }

    /* --- UI 0: TYPE SELECTION SCREEN --- */
    #type-selection-view {
        box-shadow: var(--card-shadow);
        min-height: 500px;
    }
    #type-selection-view.hidden {
        display: none !important;
    }
    .adviser-type-button {
        background-color: var(--dark-blue-button);
        color: white;
        padding: 1.5rem 3rem;
        border-radius: 2.5rem;
        font-size: 1.2rem;
        font-weight: 500;
        border: none;
        box-shadow: 0 4px 10px rgba(0, 51, 102, 0.4);
        transition: all 0.2s;
        min-width: 250px;
    }
    .adviser-type-button:hover {
        background-color: #00509e;
        box-shadow: 0 6px 15px rgba(0, 51, 102, 0.6);
        transform: translateY(-2px);
    }
    .type-button-container {
        gap: 2rem;
    }

    /* --- UI 1: CONTENT VIEW --- */
    #adviser-panel-content {
        box-shadow: var(--card-shadow);
    }
    #adviser-panel-content.hidden {
        display: none !important;
    }
    .selected-type-display {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--dark-blue-button);
        text-transform: uppercase;
        white-space: nowrap;
    }

    /* --- UI 2: CONTENT STYLES --- */
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

    @media (max-width: 991.98px) {
        #adviser-panel-content .d-grid {
            gap: 1rem;
        }
        .selected-type-display {
            text-align: center;
        }
    }

    /* --- MODAL Z-INDEX HANDLING --- */
    .modal {
        z-index: 1055 !important;
    }
    .modal-backdrop {
        z-index: 1050 !important;
    }
    #confirmationModal {
        z-index: 2000 !important;
    }
    #confirmationModal .modal-dialog {
        z-index: 2001 !important;
    }
    #successModal {
        z-index: 2000 !important;
    }
    #successModal .modal-dialog {
        z-index: 2001 !important;
    }
    
    /* When confirmation/success modal is shown, ensure it's on top */
    #confirmationModal.show ~ .modal-backdrop,
    #successModal.show ~ .modal-backdrop {
        z-index: 1999 !important;
    }

    /* --- INACTIVE MODALS STYLING --- */
    #archiveAdviserModal .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    #archivePanelModal .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .restore-adviser-btn,
    .restore-panel-btn {
        position: relative;
        z-index: 10;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .restore-adviser-btn:hover,
    .restore-panel-btn:hover {
        background-color: #5a6268 !important;
        transform: translateY(-1px);
    }

    .restore-adviser-btn:active,
    .restore-panel-btn:active {
        transform: translateY(0);
    }
</style>
@endsection

@section('content')
<div class="container-fluid" id="main-container-panel-adviser">
    
    <!-- Type Selection View -->
    <div id="type-selection-view" class="text-center p-5 bg-white rounded-4 d-flex flex-column align-items-center justify-content-center mb-4">
        <h1 class="fw-bold text-dark-blue mb-4">ADVISER & PANEL ASSIGNMENT</h1>
        <h4 class="text-muted">Select what you want to manage:</h4>
        <div class="type-button-container d-flex justify-content-center flex-wrap mt-5">
            <button class="adviser-type-button" data-adviser-type="ADVISER OVERVIEW">Adviser Overview</button>
            <button class="adviser-type-button" data-adviser-type="MANAGE PANELS">Manage Panels</button>
            <button class="adviser-type-button" data-adviser-type="ASSIGNMENT">Assignment</button>
        </div>
    </div>
    
    <!-- Content View -->
    <div id="adviser-panel-content" class="bg-white p-4 rounded-3 mb-4 hidden">
        <div class="d-flex align-items-center mb-4">
            <button class="btn btn-outline-secondary me-3" id="backToSelectionButton">
                <i class="bi bi-arrow-left"></i> Back
            </button>
            <h1 class="selected-type-display mb-0" id="adviser-type-display"></h1>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Content goes here -->
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="adviser-content" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-4 pt-3">
                    <h2 class="h5 mb-0 text-success fw-bold"></h2>
                </div>

                <div class="card">
                    <div class="card-header">
                        <label for="adviserFilter" class="form-label me-2">Filter by Department:</label>
                        <select id="adviserFilter" class="form-select w-auto" onchange="filterAdvisers()">
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
                        @if($advisers->count() > 0)
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Department</th>
                                        <th>Name</th>
                                        <th>Expertise</th>
                                        <th>Sections</th>
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
                                                    $adviserSections = is_array($adviser->sections) ? $adviser->sections : [$adviser->sections];
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
                                                @endphp
                                                @foreach($groupedByCluster as $cluster => $groups)
                                                    <div>
                                                        <span class="badge bg-primary me-1 section-badge" style="cursor:pointer;" data-adviser-id="{{ $adviser->id }}" data-cluster="{{ $cluster }}">
                                                            {{ $cluster }} <i class="bi bi-chevron-down" id="icon_{{ $adviser->id }}_{{ $cluster }}"></i>
                                                        </span>
                                                    </div>
                                                    <div id="groups_{{ $adviser->id }}_{{ $cluster }}" style="display:none;" class="ms-4">
                                                        @foreach($groups as $group)
                                                            <div><span class="badge bg-secondary me-1">G{{ $group }}</span></div>
                                                        @endforeach
                                                    </div>
                                                @endforeach
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

            <div class="tab-pane fade" id="panel-content" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-4 pt-3">
                    <h2 class="h5 mb-0 text-primary fw-bold">Panel Management</h2>
                    <div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPanelModal">
                            <i class="bi bi-person-plus-fill me-1"></i> Add Panel Member
                        </button>
                        <button class="btn btn-secondary ms-2" data-bs-toggle="modal" data-bs-target="#archivePanelModal">
                            <i class="bi bi-archive me-1"></i> Inactive
                        </button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <label for="panelFilter" class="form-label me-2">Filter by Department:</label>
                        <select id="panelFilter" class="form-select w-auto" onchange="filterPanels()">
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
                        @if($panels->count() > 0)
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Department</th>
                                        <th>Name</th>
                                        <th>Expertise</th>
                                        <th>Role</th>
                                        <th>Contact Number</th>
                                        <th>Availability</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="panelTableBody">
                                    @foreach($panels as $panel)
                                        <tr data-department="{{ $panel->department }}">
                                            <td>{{ $panel->department }}</td>
                                            <td>{{ $panel->name }}</td>
                                            <td>{{ $panel->expertise }}</td>
                                            <td>{{ $panel->role ?? 'N/A' }}</td>
                                            <td>{{ $panel->contact_number ?? 'N/A' }}</td>
                                            <td>
                                                @if(is_array($panel->availability) && count($panel->availability) > 0)
                                                    <ul class="list-unstyled mb-0">
                                                        @foreach(array_slice($panel->availability, 0, 2) as $slot)
                                                            <li>
                                                                <small>{{ $slot['date'] }} ({{ $slot['time'] }})</small>
                                                            </li>
                                                        @endforeach
                                                        @if(count($panel->availability) > 2)
                                                            <li><small class="text-muted">+{{ count($panel->availability) - 2 }} more</small></li>
                                                        @endif
                                                    </ul>
                                                @else
                                                    <small class="text-muted">No availability</small>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal"
                                                    data-bs-target="#editPanelModal{{ $panel->id }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-secondary" onclick="deletePanel({{ $panel->id }})">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-center text-muted">No panel members found. Add one to get started.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="assignment-content" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-4 pt-3">
                    <h2 class="h5 mb-0 text-warning fw-bold">Assignment</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAssignmentModal">
                        <i class="bi bi-plus-circle-fill me-1"></i> Add Assignment
                    </button>
                </div>

                <div class="card">
                    <div class="card-header">
                        <label for="assignmentFilter" class="form-label me-2">Filter by Department:</label>
                        <select id="assignmentFilter" class="form-select w-auto d-inline-block" style="width: auto;" onchange="updateAdviserFilterOptions()">
                            <option value="">All Departments</option>
                            <option value="BSIT">BSIT</option>
                            <option value="CRIM">CRIM</option>
                            <option value="EDUC">EDUC</option>
                            <option value="BSBA">BSBA</option>
                            <option value="Psychology">Psychology</option>
                            <option value="BSHM">BSHM</option>
                            <option value="BSTM">BSTM</option>
                        </select>
                        <label for="adviserFilterAssignment" class="form-label ms-3 me-2">Filter by Adviser:</label>
                        <select id="adviserFilterAssignment" class="form-select w-auto d-inline-block" style="width: auto;" onchange="filterAssignments()">
                            <option value="">All Advisers</option>
                            @foreach($advisers as $adviser)
                                <option value="{{ $adviser->name }}" data-department="{{ $adviser->department }}">{{ $adviser->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Department</th>
                                        <th>Section</th>
                                        <th>Adviser</th>
                                        <th>Expertise</th>
                                        <th>Panel Members</th>
                                        <th>Chairperson</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="assignmentTableBody">
                                    @forelse($assignments as $assignment)
                                        <tr>
                                            <td>{{ $assignment->department }}</td>
                                            <td>
                                                @php
                                                    // Get the actual groups from the adviser's sections
                                                    $assignmentGroups = [];
                                                    if ($assignment->adviser && $assignment->adviser->sections) {
                                                        $adviserSections = is_array($assignment->adviser->sections) ? $assignment->adviser->sections : [$assignment->adviser->sections];
                                                        // Filter only groups that belong to this cluster
                                                        $clusterNum = (int)$assignment->section;
                                                        foreach($adviserSections as $group) {
                                                            if (is_numeric($group) && $group > 0) {
                                                                $groupCluster = 4101 + floor(($group - 1) / 10);
                                                                if ($groupCluster == $clusterNum) {
                                                                    $assignmentGroups[] = $group;
                                                                }
                                                            }
                                                        }
                                                    }
                                                    
                                                    $groupedByCluster = [];
                                                    if (!empty($assignmentGroups)) {
                                                        foreach($assignmentGroups as $group) {
                                                            $cluster = 4101 + floor(($group - 1) / 10);
                                                            if (!isset($groupedByCluster[$cluster])) {
                                                                $groupedByCluster[$cluster] = [];
                                                            }
                                                            $groupedByCluster[$cluster][] = $group;
                                                        }
                                                    } else {
                                                        $groupedByCluster[$assignment->section] = [];
                                                    }
                                                @endphp
                                                @foreach($groupedByCluster as $cluster => $groups)
                                                    <div>
                                                        <span class="badge bg-primary me-1 section-badge" style="cursor:pointer;" data-assignment-id="{{ $assignment->id }}" data-cluster="{{ $cluster }}">
                                                            {{ $cluster }} @if(count($groups) > 0)<i class="bi bi-chevron-down" id="icon_assignment_{{ $assignment->id }}_{{ $cluster }}"></i>@endif
                                                        </span>
                                                    </div>
                                                    @if(count($groups) > 0)
                                                        <div id="groups_assignment_{{ $assignment->id }}_{{ $cluster }}" style="display:none;" class="ms-4">
                                                            @foreach($groups as $group)
                                                                <div><span class="badge bg-secondary me-1">G{{ $group }}</span></div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>{{ $assignment->adviser ? $assignment->adviser->name : 'No Adviser' }}</td>
                                            <td>{{ $assignment->adviser ? $assignment->adviser->expertise : 'N/A' }}</td>
                                            <td>
                                                @if($assignment->assignmentPanels && $assignment->assignmentPanels->count() > 0)
                                                    @php
                                                        $members = $assignment->assignmentPanels->where('role', '!=', 'Chairperson');
                                                    @endphp
                                                    @if($members->count() > 0)
                                                        @foreach($members as $panel)
                                                            <span class="badge bg-secondary me-1">{{ $panel->name }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">No members</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">No members</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($assignment->assignmentPanels && $assignment->assignmentPanels->count() > 0)
                                                    @php
                                                        $chairperson = $assignment->assignmentPanels->where('role', 'Chairperson')->first();
                                                    @endphp
                                                    @if($chairperson)
                                                        {{ $chairperson->name }}
                                                    @else
                                                        <span class="text-muted">No chairperson</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">No chairperson</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-success me-1" onclick="scheduleDefense('{{ $assignment->department }}', '{{ $assignment->section }}', {{ $assignment->id }})" title="Schedule Defense">
                                                    <i class="bi bi-calendar-plus"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning me-1" onclick="evaluateDefense('{{ $assignment->department }}', '{{ $assignment->section }}', {{ $assignment->id }})" title="Evaluate Defense">
                                                    <i class="bi bi-clipboard-check"></i>
                                                </button>
                                                <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editAssignmentModal{{ $assignment->id }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" onclick="deleteAssignment({{ $assignment->id }})">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-3 text-muted">
                                                No assignments found. Click "Add Assignment" to create your first assignment.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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
                            <select name="department" class="form-select" required onchange="updateAdviserSections()">
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
                            <label class="form-label">Sections *</label>
                            <div class="row">
                                @foreach(['4101', '4102', '4103', '4104', '4105', '4106', '4107', '4108', '4109', '4110'] as $section)
                                    <div class="col-md-4 col-sm-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input section-checkbox" type="checkbox" name="sections[]"
                                                value="{{ $section }}" id="section{{ $section }}" data-section="{{ $section }}">
                                            <label class="form-check-label" for="section{{ $section }}">
                                                {{ $section }} <span class="occupied-indicator"></span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <small class="text-info"><i class="bi bi-info-circle me-1"></i>Select a department first to see section availability. Occupied sections cannot be selected.</small>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name *</label>
                            <input type="text" name="name" class="form-control" placeholder="E.g., Prof. Maria Santos"
                                required />
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

    <!-- Add Panel Modal -->
    <div class="modal fade" id="addPanelModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form action="{{ route('panels.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Add New Panel Member</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="department" class="form-label">Department *</label>
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
                                    <label for="name" class="form-label">Name *</label>
                                    <input type="text" name="name" class="form-control" placeholder="E.g., Dr. Juan Dela Cruz" required />
                                </div>
                                <div class="mb-3">
                                    <label for="expertise" class="form-label">Expertise *</label>
                                    <select name="expertise" class="form-select" required onchange="toggleOthersInput(this)">
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
                                <div class="mb-3" id="othersInputDiv" style="display: none;">
                                    <label for="othersInput" class="form-label">Specify Other Expertise</label>
                                    <input type="text" id="othersInput" name="others_expertise" class="form-control" placeholder="Enter specific expertise">
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role *</label>
                                    <select name="role" class="form-select" required>
                                        <option value="">Select Role</option>
                                        <option value="Chairperson">Chairperson</option>
                                        <option value="Member">Member</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="contact_number" class="form-label">Contact Number</label>
                                    <input type="text" name="contact_number" class="form-control" placeholder="e.g., +63 912 345 6789" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Availability Summary</label>
                                    <textarea id="availabilitySummary" class="form-control" rows="3" readonly></textarea>
                                    <input type="hidden" name="availability" id="availabilityInput">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-3">Add Availability Slots *</h6>
                                <div class="mb-3">
                                    <label for="slotDate" class="form-label">Date</label>
                                    <input type="date" id="slotDate" class="form-control" />
                                </div>
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <label for="startTime" class="form-label">Start Time</label>
                                        <input type="time" id="startTime" class="form-control" />
                                    </div>
                                    <div class="col-6">
                                        <label for="endTime" class="form-label">End Time</label>
                                        <input type="time" id="endTime" class="form-control" />
                                    </div>
                                </div>
                                <button type="button" class="btn btn-info w-100 text-white fw-bold mb-3" onclick="addSlot()">
                                    <i class="bi bi-plus-circle-fill me-1"></i> Add Slot
                                </button>
                                <h6><i class="bi bi-clock-history me-1"></i> Added Slots:</h6>
                                <ul id="slotsList" class="list-group list-group-flush border rounded p-2">
                                    <li class="text-muted small">No slots added yet.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Panel Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Edit Adviser Modals -->
    @foreach($advisers as $adviser)
        <div class="modal fade" id="editAdviserModal{{ $adviser->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="editAdviserForm{{ $adviser->id }}" action="{{ route('advisers.update', $adviser->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Adviser</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Department *</label>
                                        <select name="department" class="form-select form-select-sm" required>
                                            <option value="BSIT" {{ $adviser->department == 'BSIT' ? 'selected' : '' }}>BSIT</option>
                                            <option value="CRIM" {{ $adviser->department == 'CRIM' ? 'selected' : '' }}>CRIM</option>
                                            <option value="EDUC" {{ $adviser->department == 'EDUC' ? 'selected' : '' }}>EDUC</option>
                                            <option value="BSBA" {{ $adviser->department == 'BSBA' ? 'selected' : '' }}>BSBA</option>
                                            <option value="Psychology" {{ $adviser->department == 'Psychology' ? 'selected' : '' }}>Psychology</option>
                                            <option value="BSHM" {{ $adviser->department == 'BSHM' ? 'selected' : '' }}>BSHM</option>
                                            <option value="BSTM" {{ $adviser->department == 'BSTM' ? 'selected' : '' }}>BSTM</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Name *</label>
                                        <input type="text" name="name" class="form-control form-control-sm" value="{{ $adviser->name }}" required />
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Expertise *</label>
                                <select name="expertise" class="form-select form-select-sm" required>
                                    <option value="Instructor" {{ $adviser->expertise == 'Instructor' ? 'selected' : '' }}>Instructor</option>
                                    <option value="Assistant Professor" {{ $adviser->expertise == 'Assistant Professor' ? 'selected' : '' }}>Assistant Professor</option>
                                    <option value="Associate Professor" {{ $adviser->expertise == 'Associate Professor' ? 'selected' : '' }}>Associate Professor</option>
                                    <option value="Professor" {{ $adviser->expertise == 'Professor' ? 'selected' : '' }}>Professor</option>
                                    <option value="Doctoral" {{ $adviser->expertise == 'Doctoral' ? 'selected' : '' }}>Doctoral</option>
                                    <option value="Industry Expert" {{ $adviser->expertise == 'Industry Expert' ? 'selected' : '' }}>Industry Expert</option>
                                    <option value="Research Specialist" {{ $adviser->expertise == 'Research Specialist' ? 'selected' : '' }}>Research Specialist</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Sections *</label>
                                <div class="border rounded p-3" style="background-color: #f8f9fa; max-height: 250px; overflow-y: auto;">
                                    <div class="row g-2">
                                        @foreach(['4101', '4102', '4103', '4104', '4105', '4106', '4107', '4108', '4109', '4110'] as $section)
                                            <div class="col-6">
                                                @php
                                                    $isOccupiedByOther = $assignments->where('department', $adviser->department)
                                                                                  ->where('section', $section)
                                                                                  ->where('adviser_id', '!=', $adviser->id)
                                                                                  ->first();
                                                    $isCurrentlyAssigned = is_array($adviser->sections) && in_array($section, $adviser->sections);
                                                @endphp
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="sections[]"
                                                        value="{{ $section }}" id="editSection{{ $adviser->id }}{{ $section }}" 
                                                        {{ $isCurrentlyAssigned ? 'checked' : '' }}
                                                        {{ $isOccupiedByOther ? 'disabled' : '' }}>
                                                    <label class="form-check-label {{ $isOccupiedByOther ? 'text-muted text-decoration-line-through' : '' }}" for="editSection{{ $adviser->id }}{{ $section }}">
                                                        <small>{{ $section }}
                                                        @if($isOccupiedByOther)
                                                            <span class="badge bg-danger ms-1">Taken</span>
                                                        @endif
                                                        </small>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <small class="text-info d-block mt-2"><i class="bi bi-info-circle me-1"></i>Sections with "Taken" are assigned to other advisers.</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-circle me-1"></i>Update Adviser</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Edit Panel Modals -->
    @foreach($panels as $panel)
        <div class="modal fade" id="editPanelModal{{ $panel->id }}" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <form id="editPanelForm{{ $panel->id }}" action="{{ route('panels.update', $panel->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Edit Panel Member</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Department *</label>
                                        <select name="department" class="form-select" required>
                                            <option value="BSIT" {{ $panel->department == 'BSIT' ? 'selected' : '' }}>BSIT</option>
                                            <option value="CRIM" {{ $panel->department == 'CRIM' ? 'selected' : '' }}>CRIM</option>
                                            <option value="EDUC" {{ $panel->department == 'EDUC' ? 'selected' : '' }}>EDUC</option>
                                            <option value="BSBA" {{ $panel->department == 'BSBA' ? 'selected' : '' }}>BSBA</option>
                                            <option value="Psychology" {{ $panel->department == 'Psychology' ? 'selected' : '' }}>Psychology</option>
                                            <option value="BSHM" {{ $panel->department == 'BSHM' ? 'selected' : '' }}>BSHM</option>
                                            <option value="BSTM" {{ $panel->department == 'BSTM' ? 'selected' : '' }}>BSTM</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Name *</label>
                                        <input type="text" name="name" class="form-control" value="{{ $panel->name }}" required />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Expertise *</label>
                                        <select name="expertise" class="form-select" required onchange="toggleEditOthersInput(this, {{ $panel->id }})">
                                            <option value="Instructor" {{ $panel->expertise == 'Instructor' ? 'selected' : '' }}>Instructor</option>
                                            <option value="Assistant Professor" {{ $panel->expertise == 'Assistant Professor' ? 'selected' : '' }}>Assistant Professor</option>
                                            <option value="Associate Professor" {{ $panel->expertise == 'Associate Professor' ? 'selected' : '' }}>Associate Professor</option>
                                            <option value="Professor" {{ $panel->expertise == 'Professor' ? 'selected' : '' }}>Professor</option>
                                            <option value="Doctoral" {{ $panel->expertise == 'Doctoral' ? 'selected' : '' }}>Doctoral</option>
                                            <option value="Industry Expert" {{ $panel->expertise == 'Industry Expert' ? 'selected' : '' }}>Industry Expert</option>
                                            <option value="Research Specialist" {{ $panel->expertise == 'Research Specialist' ? 'selected' : '' }}>Research Specialist</option>
                                            <option value="Others" {{ !in_array($panel->expertise, ['Instructor', 'Assistant Professor', 'Associate Professor', 'Professor', 'Doctoral', 'Industry Expert', 'Research Specialist']) ? 'selected' : '' }}>Others</option>
                                        </select>
                                    </div>
                                    <div class="mb-3" id="editOthersInputDiv{{ $panel->id }}" style="display: {{ !in_array($panel->expertise, ['Instructor', 'Assistant Professor', 'Associate Professor', 'Professor', 'Doctoral', 'Industry Expert', 'Research Specialist']) ? 'block' : 'none' }};">
                                        <label for="editOthersInput{{ $panel->id }}" class="form-label">Specify Other Expertise</label>
                                        <input type="text" id="editOthersInput{{ $panel->id }}" name="others_expertise" class="form-control" placeholder="Enter specific expertise" value="{{ !in_array($panel->expertise, ['Instructor', 'Assistant Professor', 'Associate Professor', 'Professor', 'Doctoral', 'Industry Expert', 'Research Specialist']) ? $panel->expertise : '' }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Role *</label>
                                        <select name="role" class="form-select" required>
                                            <option value="Chairperson" {{ $panel->role == 'Chairperson' ? 'selected' : '' }}>Chairperson</option>
                                            <option value="Member" {{ $panel->role == 'Member' ? 'selected' : '' }}>Member</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Contact Number</label>
                                        <input type="text" name="contact_number" class="form-control" value="{{ $panel->contact_number ?? '' }}" placeholder="e.g., +63 912 345 6789" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Availability Summary</label>
                                        <textarea id="editAvailabilitySummary{{ $panel->id }}" class="form-control" rows="3" readonly></textarea>
                                        <input type="hidden" name="availability" id="editAvailabilityInput{{ $panel->id }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-3">Add Availability Slots</h6>
                                    <div class="mb-3">
                                        <label for="editSlotDate{{ $panel->id }}" class="form-label">Date</label>
                                        <input type="date" id="editSlotDate{{ $panel->id }}" class="form-control" />
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <label for="editStartTime{{ $panel->id }}" class="form-label">Start Time</label>
                                            <input type="time" id="editStartTime{{ $panel->id }}" class="form-control" />
                                        </div>
                                        <div class="col-6">
                                            <label for="editEndTime{{ $panel->id }}" class="form-label">End Time</label>
                                            <input type="time" id="editEndTime{{ $panel->id }}" class="form-control" />
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-info w-100 text-white fw-bold mb-3" onclick="addEditSlot({{ $panel->id }})">
                                        <i class="bi bi-plus-circle-fill me-1"></i> Add Slot
                                    </button>
                                    <h6><i class="bi bi-clock-history me-1"></i> Current Slots:</h6>
                                    <ul id="editSlotsList{{ $panel->id }}" class="list-group list-group-flush border rounded p-2">
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Panel Member</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

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

    <!-- Archive Panel Modal -->
    <div class="modal fade" id="archivePanelModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-secondary text-white d-flex justify-content-between align-items-center">
                    <h5 class="modal-title">Inactive Panel Members</h5>
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
                                    <th>Inactive Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="archivedPanelsBody">
                                <tr><td colspan="5" class="text-center text-muted">Loading...</td></tr>
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

    <!-- Add Assignment Modal -->
    <div class="modal fade" id="addAssignmentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="addAssignmentForm">
                    @csrf
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title">Add New Assignment</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Department *</label>
                                <select name="department" class="form-select" required onchange="filterModalMembers()">
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
                            <div class="col-md-6">
                                <label class="form-label">Section/Cluster *</label>
                                <select name="section" class="form-select" required onchange="filterModalMembers()">
                                    <option value="">Select Section</option>
                                    @foreach(['4101', '4102', '4103', '4104', '4105', '4106', '4107', '4108', '4109', '4110'] as $section)
                                        <option value="{{ $section }}">{{ $section }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Adviser *</label>
                            <select name="adviser_id" class="form-select" required onchange="updateAvailableGroups()">
                                <option value="">Select Adviser</option>
                                @foreach($advisers as $adviser)
                                    <option value="{{ $adviser->id }}" data-department="{{ $adviser->department }}" data-sections="{{ is_array($adviser->sections) ? implode(',', $adviser->sections) : $adviser->sections }}">
                                        {{ $adviser->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3" id="groupSelectionDiv" style="display:none;">
                            <label class="form-label">Select Groups *</label>
                            <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;" id="groupCheckboxContainer">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Chairperson *</label>
                            <select name="chairperson_id" class="form-select" required>
                                <option value="">Select Chairperson</option>
                                @foreach($panels as $panel)
                                    @if($panel->role === 'Chairperson')
                                        <option value="{{ $panel->id }}" data-department="{{ $panel->department }}" class="modal-chairperson-option">
                                            {{ $panel->name }} ({{ $panel->department }} - {{ $panel->expertise }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Panel Members *</label>
                            <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                @foreach($panels as $panel)
                                    <div class="form-check modal-panel-member" data-department="{{ $panel->department }}">
                                        <input class="form-check-input" type="checkbox" name="panel_ids[]" value="{{ $panel->id }}" id="modalPanel{{ $panel->id }}">
                                        <label class="form-check-label" for="modalPanel{{ $panel->id }}">
                                            {{ $panel->name }} ({{ $panel->department }} - {{ $panel->expertise }})
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning" id="createAssignmentBtn">Create Assignment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Assignment Modals -->
    @foreach($assignments as $assignment)
        <div class="modal fade" id="editAssignmentModal{{ $assignment->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="editAssignmentForm{{ $assignment->id }}" action="{{ route('assignments.update', $assignment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header bg-warning text-white">
                            <h5 class="modal-title">Edit Assignment</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Department *</label>
                                    <select name="department" class="form-select" required>
                                        <option value="BSIT" {{ $assignment->department == 'BSIT' ? 'selected' : '' }}>BSIT</option>
                                        <option value="CRIM" {{ $assignment->department == 'CRIM' ? 'selected' : '' }}>CRIM</option>
                                        <option value="EDUC" {{ $assignment->department == 'EDUC' ? 'selected' : '' }}>EDUC</option>
                                        <option value="BSBA" {{ $assignment->department == 'BSBA' ? 'selected' : '' }}>BSBA</option>
                                        <option value="Psychology" {{ $assignment->department == 'Psychology' ? 'selected' : '' }}>Psychology</option>
                                        <option value="BSHM" {{ $assignment->department == 'BSHM' ? 'selected' : '' }}>BSHM</option>
                                        <option value="BSTM" {{ $assignment->department == 'BSTM' ? 'selected' : '' }}>BSTM</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Section/Cluster *</label>
                                    <select name="section" class="form-select" required>
                                        @foreach(['4101', '4102', '4103', '4104', '4105', '4106', '4107', '4108', '4109', '4110'] as $section)
                                            <option value="{{ $section }}" {{ $assignment->section == $section ? 'selected' : '' }}>{{ $section }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Adviser (Fixed)</label>
                                <input type="text" class="form-control" value="{{ $assignment->adviser ? $assignment->adviser->name : 'No Adviser' }} ({{ $assignment->department }})" readonly style="background-color: #e9ecef;">
                                <input type="hidden" name="adviser_id" value="{{ $assignment->adviser_id }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Chairperson *</label>
                                <select name="chairperson_id" class="form-select" required>
                                    <option value="">Select Chairperson</option>
                                    @foreach($panels as $panel)
                                        @if($panel->role === 'Chairperson')
                                            @php
                                                $isChairperson = $assignment->assignmentPanels->where('role', 'Chairperson')->where('panel_id', $panel->id)->first();
                                            @endphp
                                            <option value="{{ $panel->id }}" {{ $isChairperson ? 'selected' : '' }}>
                                                {{ $panel->name }} ({{ $panel->department }} - {{ $panel->expertise }})
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Panel Members *</label>
                                <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                    @foreach($panels as $panel)
                                        @if($panel->role !== 'Chairperson')
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="panel_ids[]" value="{{ $panel->id }}" id="editPanel{{ $assignment->id }}_{{ $panel->id }}" 
                                                    {{ $assignment->assignmentPanels->contains('panel_id', $panel->id) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="editPanel{{ $assignment->id }}_{{ $panel->id }}">
                                                    {{ $panel->name }} ({{ $panel->department }} - {{ $panel->expertise }})
                                                </label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning">Update Assignment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Success Modal for Assignment Creation -->
    <div class="modal fade" id="successModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header bg-success text-white border-0">
                    <h5 class="modal-title" id="successModalTitle">Success!</h5>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                    </div>
                    <p class="fs-5 mb-0" id="successModalMessage">Assignment created successfully!</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal" onclick="location.reload();">
                        <i class="bi bi-arrow-clockwise me-2"></i> Refresh & Continue
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    // Event delegation for section badges
    document.addEventListener('click', function(e) {
        if (e.target.closest('.section-badge')) {
            const badge = e.target.closest('.section-badge');
            const adviserId = badge.dataset.adviserId;
            const assignmentId = badge.dataset.assignmentId;
            const cluster = badge.dataset.cluster;
            
            let groupsSpan, icon;
            if (adviserId) {
                groupsSpan = document.getElementById(`groups_${adviserId}_${cluster}`);
                icon = document.getElementById(`icon_${adviserId}_${cluster}`);
            } else if (assignmentId) {
                groupsSpan = document.getElementById(`groups_assignment_${assignmentId}_${cluster}`);
                icon = document.getElementById(`icon_assignment_${assignmentId}_${cluster}`);
            }
            
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

    // ------------------
    // Type Selection View Logic
    // ------------------
    const typeSelectionView = document.getElementById('type-selection-view');
    const adviserPanelContent = document.getElementById('adviser-panel-content');
    const typeDisplayElement = document.getElementById('adviser-type-display');
    const backButton = document.getElementById('backToSelectionButton');
    const typeButtons = document.querySelectorAll('.adviser-type-button');

    // Type button click handlers
    typeButtons.forEach(button => {
        button.addEventListener('click', () => {
            const selectedType = button.dataset.adviserType;
            localStorage.setItem('selectedAdviserType', selectedType);
            showAdviserPanelContent(selectedType);
        });
    });

    // Back button handler
    if (backButton) {
        backButton.addEventListener('click', () => {
            localStorage.removeItem('selectedAdviserType');
            typeSelectionView.classList.remove('hidden');
            adviserPanelContent.classList.add('hidden');
            
            // Reset filters
            const adviserFilter = document.getElementById('adviserFilter');
            const panelFilter = document.getElementById('panelFilter');
            const assignmentFilter = document.getElementById('assignmentFilter');
            if (adviserFilter) adviserFilter.value = '';
            if (panelFilter) panelFilter.value = '';
            if (assignmentFilter) assignmentFilter.value = '';
            
            // Hide all tab panes
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('show', 'active');
                pane.classList.add('fade');
            });
        });
    }

    // Restore view on page load
    const savedType = localStorage.getItem('selectedAdviserType');
    if (savedType) {
        showAdviserPanelContent(savedType);
    }

    function showAdviserPanelContent(type) {
        typeSelectionView.classList.add('hidden');
        adviserPanelContent.classList.remove('hidden');
        typeDisplayElement.textContent = type;
        
        // Map type to tab ID
        const tabMap = {
            'ADVISER OVERVIEW': 'adviser-content',
            'MANAGE PANELS': 'panel-content',
            'ASSIGNMENT': 'assignment-content'
        };
        
        const tabId = tabMap[type];
        if (tabId) {
            // Show the selected tab
            const tabPane = document.getElementById(tabId);
            if (tabPane) {
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.classList.remove('show', 'active');
                    pane.classList.add('fade');
                });
                tabPane.classList.add('show', 'active');
                
                // Reset and show all rows when showing adviser content
                if (tabId === 'adviser-content') {
                    const adviserFilter = document.getElementById('adviserFilter');
                    if (adviserFilter) adviserFilter.value = '';
                    document.querySelectorAll('#adviserTableBody tr').forEach(row => {
                        row.style.display = '';
                    });
                }
            }
        }
    }

    // ------------------
    // Confirmation Modal Helper
    // ------------------
    let confirmationCallback = null;

    function showConfirmation(message, callback, buttonText = 'Confirm', buttonClass = 'btn-danger') {
        const confirmationModal = document.getElementById('confirmationModal');
        const modal = new bootstrap.Modal(confirmationModal);
        document.getElementById('confirmationMessage').textContent = message;
        const confirmBtn = document.getElementById('confirmationBtn');
        confirmBtn.textContent = buttonText;
        
        // Update button class
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

    // ------------------
    // Original Functions
    // ------------------
    function togglePanelDropdown() {
    const menu = document.getElementById('panelDropdownMenu');
    menu.style.display = (menu.style.display === 'none' || menu.style.display === '') ? 'block' : 'none';
}

function toggleOthersInput(select) {
    const othersDiv = document.getElementById('othersInputDiv');
    const othersInput = document.getElementById('othersInput');
    
    if (select.value === 'Others') {
        othersDiv.style.display = 'block';
        othersInput.required = true;
    } else {
        othersDiv.style.display = 'none';
        othersInput.required = false;
        othersInput.value = '';
    }
}

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

function toggleEditOthersInput(select, panelId) {
    const othersDiv = document.getElementById(`editOthersInputDiv${panelId}`);
    const othersInput = document.getElementById(`editOthersInput${panelId}`);
    
    if (select.value === 'Others') {
        othersDiv.style.display = 'block';
        othersInput.required = true;
    } else {
        othersDiv.style.display = 'none';
        othersInput.required = false;
        othersInput.value = '';
    }
}
const advisersData = @json($advisers);  // Array of advisers
const panelsData = @json($panels); 

let availabilitySlots = [];          
let editAvailabilitySlots = {};






// ------------------
// Adviser & Panel Filtering
// ------------------
function filterAdvisers() {
    const filter = document.getElementById('adviserFilter').value.toLowerCase();
    document.querySelectorAll('#adviserTableBody tr').forEach(row => {
        row.style.display = (!filter || row.dataset.department.toLowerCase() === filter) ? '' : 'none';
    });
}

function filterPanels() {
    const filter = document.getElementById('panelFilter').value.toLowerCase();
    document.querySelectorAll('#panelTableBody tr').forEach(row => {
        row.style.display = (!filter || row.dataset.department.toLowerCase() === filter) ? '' : 'none';
    });
}

function filterAssignments() {
    const deptFilter = document.getElementById('assignmentFilter').value.toLowerCase();
    const adviserFilter = document.getElementById('adviserFilterAssignment').value.toLowerCase();
    document.querySelectorAll('#assignmentTableBody tr').forEach(row => {
        const departmentCell = row.cells[0];
        const adviserCell = row.cells[2];
        if (departmentCell && adviserCell) {
            const department = departmentCell.textContent.toLowerCase();
            const adviser = adviserCell.textContent.toLowerCase();
            const deptMatch = !deptFilter || department === deptFilter;
            const adviserMatch = !adviserFilter || adviser === adviserFilter;
            row.style.display = (deptMatch && adviserMatch) ? '' : 'none';
        }
    });
}

function updateAdviserFilterOptions() {
    const deptFilter = document.getElementById('assignmentFilter').value;
    const adviserSelect = document.getElementById('adviserFilterAssignment');
    
    adviserSelect.value = '';
    
    const options = adviserSelect.querySelectorAll('option[data-department]');
    options.forEach(option => {
        option.style.display = (!deptFilter || option.dataset.department === deptFilter) ? 'block' : 'none';
    });
    
    filterAssignments();
}

function filterModalMembers() {
    const modal = document.getElementById('addAssignmentModal');
    const dept = modal.querySelector('select[name="department"]').value;
    const section = modal.querySelector('select[name="section"]').value;
    const adviserSelect = modal.querySelector('select[name="adviser_id"]');

    adviserSelect.value = '';
    document.getElementById('groupSelectionDiv').style.display = 'none';

    // Fetch fresh adviser data and auto-select based on department and section
    if (dept && section) {
        fetch('/api/advisers')
            .then(response => response.json())
            .then(advisers => {
                // Rebuild adviser dropdown with fresh data
                adviserSelect.innerHTML = '<option value="">Select Adviser</option>';
                advisers.forEach(adviser => {
                    if (adviser.department === dept) {
                        const option = document.createElement('option');
                        option.value = adviser.id;
                        option.textContent = adviser.name;
                        option.dataset.department = adviser.department;
                        option.dataset.sections = Array.isArray(adviser.sections) ? adviser.sections.join(',') : adviser.sections;
                        adviserSelect.appendChild(option);
                    }
                });
                
                const sectionNum = parseInt(section);
                const matchingAdviser = advisers.find(adviser => {
                    if (adviser.department !== dept) return false;
                    if (!adviser.sections) return false;
                    
                    const sections = Array.isArray(adviser.sections) ? adviser.sections : [adviser.sections];
                    
                    return sections.some(s => {
                        const groupNum = parseInt(s);
                        if (groupNum >= 4101 && groupNum <= 4110) {
                            return groupNum === sectionNum;
                        }
                        if (groupNum >= 1 && groupNum <= 100) {
                            const cluster = 4101 + Math.floor((groupNum - 1) / 10);
                            return cluster === sectionNum;
                        }
                        return false;
                    });
                });
                
                if (matchingAdviser) {
                    adviserSelect.value = matchingAdviser.id;
                    updateAvailableGroups();
                }
            })
            .catch(error => console.error('Error fetching advisers:', error));
    } else {
        // Filter adviser options by department
        const adviserOptions = adviserSelect.querySelectorAll('option[data-department]');
        adviserOptions.forEach(option => {
            const optionDept = option.dataset.department;
            option.style.display = (dept && optionDept === dept) || !dept ? 'block' : 'none';
        });
    }

    // Filter chairperson dropdown by department
    const chairpersonSelect = modal.querySelector('select[name="chairperson_id"]');
    const chairpersonOptions = chairpersonSelect.querySelectorAll('.modal-chairperson-option');
    
    chairpersonOptions.forEach(option => {
        const optionDept = option.dataset.department;
        option.style.display = (dept && optionDept === dept) ? 'block' : 'none';
    });

    // Filter panel members by department
    const panelMembers = modal.querySelectorAll('.modal-panel-member');
    panelMembers.forEach(member => {
        member.style.display = (dept && member.dataset.department === dept) ? 'block' : 'none';
        member.querySelector('input').checked = false;
    });
}

function updateAvailableGroups() {
    const adviserSelect = document.querySelector('#addAssignmentModal select[name="adviser_id"]');
    const selectedOption = adviserSelect.options[adviserSelect.selectedIndex];
    const groupSelectionDiv = document.getElementById('groupSelectionDiv');
    const groupCheckboxContainer = document.getElementById('groupCheckboxContainer');
    
    if (!selectedOption || !selectedOption.value) {
        groupSelectionDiv.style.display = 'none';
        return;
    }
    
    const sectionsStr = selectedOption.dataset.sections;
    if (!sectionsStr) {
        groupSelectionDiv.style.display = 'none';
        return;
    }
    
    const sections = sectionsStr.split(',').map(s => parseInt(s.trim())).filter(s => s >= 1 && s <= 100);
    
    if (sections.length === 0) {
        groupSelectionDiv.style.display = 'none';
        return;
    }
    
    groupCheckboxContainer.innerHTML = sections.map(group => `
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="group_numbers[]" value="${group}" id="group${group}" checked>
            <label class="form-check-label" for="group${group}">Group ${group}</label>
        </div>
    `).join('');
    
    groupSelectionDiv.style.display = 'block';
}

// Update section status display for panels
function updateSectionStatus(department) {
    const display = document.getElementById('sectionStatusDisplay');
    if (!display) return;
    
    if (!department) {
        display.innerHTML = '<div class="col-12 text-center text-muted"><i class="bi bi-info-circle me-1"></i>Select a department to view section status</div>';
        return;
    }
    
    const assignmentsData = @json($assignments->groupBy('department'));
    const sections = ['4101', '4102', '4103', '4104', '4105', '4106', '4107', '4108', '4109', '4110'];
    
    let html = '';
    sections.forEach(section => {
        const assignment = assignmentsData[department] && assignmentsData[department].find(a => a.section === section);
        if (assignment) {
            html += `<div class="col-md-6 col-sm-12 mb-1">
                        <span class="badge bg-danger me-1">${section} <i class="bi bi-x-circle ms-1"></i></span>
                        <small class="text-muted">${assignment.adviser ? assignment.adviser.name : 'Unknown'}</small>
                     </div>`;
        } else {
            html += `<div class="col-md-6 col-sm-12 mb-1">
                        <span class="badge bg-success me-1">${section} <i class="bi bi-check-circle ms-1"></i></span>
                        <small class="text-muted">Available</small>
                     </div>`;
        }
    });
    
    display.innerHTML = html;
}

// Update adviser sections based on department
function updateAdviserSections() {
    const modal = document.getElementById('addAdviserModal');
    const dept = modal.querySelector('select[name="department"]').value;
    const checkboxes = modal.querySelectorAll('.section-checkbox');
    const advisersData = @json($advisers->groupBy('department'));
    
    checkboxes.forEach(checkbox => {
        const section = checkbox.dataset.section;
        const indicator = checkbox.parentElement.querySelector('.occupied-indicator');
        const label = checkbox.parentElement.querySelector('label');
        const formCheck = checkbox.parentElement.parentElement;
        
        // Check if this section is already taken by another adviser in this department
        let isOccupied = false;
        if (dept && advisersData[dept]) {
            isOccupied = advisersData[dept].some(adviser => {
                const adviserSections = Array.isArray(adviser.sections) ? adviser.sections : [adviser.sections];
                return adviserSections.includes(section);
            });
        }
        
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



// ------------------
// Add Panel Availability Slots
// ------------------
function addSlot() {
    const date = document.getElementById('slotDate').value;
    const startTime = document.getElementById('startTime').value;
    const endTime = document.getElementById('endTime').value;

    if (!date || !startTime || !endTime) { alert('Please fill all fields'); return; }
    if (new Date(`${date}T${startTime}`) >= new Date(`${date}T${endTime}`)) { alert('End time must be after start time'); return; }

    // Prevent duplicates
    if (!availabilitySlots.some(s => s.date === date && s.time === `${startTime} - ${endTime}`)) {
        availabilitySlots.push({ date, time: `${startTime} - ${endTime}` });
    }

    renderSlots();
}

function removeSlot(index) {
    availabilitySlots.splice(index, 1);
    renderSlots();
}

function renderSlots() {
    const list = document.getElementById('slotsList');
    const summary = document.getElementById('availabilitySummary');
    const input = document.getElementById('availabilityInput');

    list.innerHTML = '';
    if (!availabilitySlots.length) {
        list.innerHTML = '<li class="text-muted small">No slots added yet.</li>';
        summary.value = 'No availability slots added';
        input.value = JSON.stringify([]);
        return;
    }

    availabilitySlots.forEach((slot, i) => {
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center';
        li.innerHTML = `<span>${slot.date} (${slot.time})</span>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeSlot(${i})">
                            <i class="bi bi-trash"></i>
                        </button>`;
        list.appendChild(li);
    });

    summary.value = availabilitySlots.map(s => `${s.date} (${s.time})`).join('\n');
    input.value = JSON.stringify(availabilitySlots);

    document.getElementById('slotDate').value = '';
    document.getElementById('startTime').value = '';
    document.getElementById('endTime').value = '';
}

// ------------------
// Edit Panel Slots
// ------------------
function addEditSlot(panelId) {
    const date = document.getElementById(`editSlotDate${panelId}`).value;
    const startTime = document.getElementById(`editStartTime${panelId}`).value;
    const endTime = document.getElementById(`editEndTime${panelId}`).value;

    if (!date || !startTime || !endTime) { alert('Please fill all fields'); return; }
    if (new Date(`${date}T${startTime}`) >= new Date(`${date}T${endTime}`)) { alert('End time must be after start time'); return; }

    if (!editAvailabilitySlots[panelId]) editAvailabilitySlots[panelId] = [];
    if (!editAvailabilitySlots[panelId].some(s => s.date === date && s.time === `${startTime} - ${endTime}`)) {
        editAvailabilitySlots[panelId].push({ date, time: `${startTime} - ${endTime}` });
    }
    renderEditSlots(panelId);
}

function removeEditSlot(panelId, index) {
    editAvailabilitySlots[panelId].splice(index, 1);
    renderEditSlots(panelId);
}

function renderEditSlots(panelId) {
    const list = document.getElementById(`editSlotsList${panelId}`);
    const summary = document.getElementById(`editAvailabilitySummary${panelId}`);
    const input = document.getElementById(`editAvailabilityInput${panelId}`);

    const slots = editAvailabilitySlots[panelId] || [];

    list.innerHTML = '';
    if (!slots.length) {
        list.innerHTML = '<li class="text-muted small">No slots added yet.</li>';
        summary.value = 'No availability slots added';
        input.value = JSON.stringify([]);
        return;
    }

    slots.forEach((slot, i) => {
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center';
        li.innerHTML = `<span>${slot.date} (${slot.time})</span>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeEditSlot(${panelId}, ${i})">
                            <i class="bi bi-trash"></i>
                        </button>`;
        list.appendChild(li);
    });

    summary.value = slots.map(s => `${s.date} (${s.time})`).join('\n');
    input.value = JSON.stringify(slots);

    document.getElementById(`editSlotDate${panelId}`).value = '';
    document.getElementById(`editStartTime${panelId}`).value = '';
    document.getElementById(`editEndTime${panelId}`).value = '';
}

// ------------------
// Initialize Modals
// ------------------
document.getElementById('addAssignmentModal').addEventListener('show.bs.modal', () => {
    const form = document.getElementById('addAssignmentForm');
    form.reset();
    
    // Fetch fresh advisers list
    fetch('/api/advisers')
        .then(response => response.json())
        .then(advisers => {
            const adviserSelect = form.querySelector('select[name="adviser_id"]');
            adviserSelect.innerHTML = '<option value="">Select Adviser</option>';
            advisers.forEach(adviser => {
                const sections = Array.isArray(adviser.sections) ? adviser.sections.join(', ') : adviser.sections;
                adviserSelect.innerHTML += `<option value="${adviser.id}" data-department="${adviser.department}" data-sections="${Array.isArray(adviser.sections) ? adviser.sections.join(',') : adviser.sections}">${adviser.name} (${adviser.department} - ${sections})</option>`;
            });
            filterModalMembers();
        })
        .catch(error => {
            console.error('Error fetching advisers:', error);
            filterModalMembers();
        });
});

document.getElementById('addPanelModal').addEventListener('show.bs.modal', () => {
    availabilitySlots = [];
    renderSlots();
});

document.getElementById('addAdviserModal').addEventListener('show.bs.modal', () => {
    // Reset form and update sections
    const modal = document.getElementById('addAdviserModal');
    modal.querySelector('select[name="department"]').value = '';
    const checkboxes = modal.querySelectorAll('.section-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
        checkbox.disabled = false;
        const formCheck = checkbox.parentElement.parentElement;
        const label = checkbox.parentElement.querySelector('label');
        const indicator = checkbox.parentElement.querySelector('.occupied-indicator');
        
        label.classList.remove('text-muted', 'text-decoration-line-through');
        formCheck.classList.remove('section-occupied', 'section-available', 'p-1', 'p-2');
        indicator.innerHTML = '';
    });
});

@foreach($panels as $panel)
document.getElementById('editPanelModal{{ $panel->id }}').addEventListener('show.bs.modal', () => {
    if (!editAvailabilitySlots[{{ $panel->id }}]) {
        editAvailabilitySlots[{{ $panel->id }}] = @json($panel->availability ?? []);
    }
    renderEditSlots({{ $panel->id }});
});
@endforeach

// ------------------
// Finalize Assignment Summary
// ------------------




// ------------------
// Add Assignment Form Handler
// ------------------
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addAssignmentForm');
    const submitBtn = document.getElementById('createAssignmentBtn');

    // Add event listener to department dropdown
    document.addEventListener('change', function(e) {
        if (e.target.name === 'department' && e.target.closest('#addAssignmentModal')) {
            filterModalMembers();
        }
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Creating...';

        fetch("{{ route('assignments.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Create Assignment';

            console.log('Server response:', data);
            
            if (data.success) {
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                document.getElementById('successModalTitle').textContent = 'Assignment Created Successfully!';
                document.getElementById('successModalMessage').textContent = 'Your assignment has been created successfully.';
                successModal.show();
                
                bootstrap.Modal.getInstance(document.getElementById('addAssignmentModal')).hide();
                
                // Reload the page to show updated assignment
                document.getElementById('successModal').addEventListener('hidden.bs.modal', () => {
                    window.location.href = window.location.href.split('?')[0] + '?t=' + Date.now();
                }, { once: true });
                
                form.reset();
                filterModalMembers();
            } else {
                alert('⚠️ Error: ' + (data.message || data.error || 'Please try again.'));
                console.error('Assignment creation failed:', data);
            }
        })
        .catch(error => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Create Assignment';
            console.error('Error:', error);
            alert('❌ Failed to create assignment. Error: ' + error.message);
        });
    });
});

// ------------------
// Archive Assignment Function
// ------------------
function deleteAssignment(assignmentId) {
    showConfirmation(
        'Are you sure you want to archive this assignment? This action can be undone by restoring it.',
        function() {
            fetch(`/assignments/${assignmentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('successMessage').textContent = 'Assignment has been successfully archived!';
                    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                    document.getElementById('successModal').addEventListener('hidden.bs.modal', () => {
                        location.reload();
                    }, { once: true });
                } else {
                    alert('Error archiving assignment: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while archiving the assignment.');
            });
        },
        'Archive Assignment'
    );
}



// Archive Functions
function loadArchivedAdvisers() {
    console.log('loadArchivedAdvisers called');
    fetch('/advisers/archived')
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data);
            const tbody = document.getElementById('archivedAdvisersBody');
            if (!tbody) {
                console.error('archivedAdvisersBody element not found');
                return;
            }
            
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
                        <button class="btn btn-sm btn-secondary" type="button" onclick="window.restoreAdviser(${adviser.id})">
                            <i class="bi bi-arrow-clockwise"></i> Restore
                        </button>
                    </td>
                </tr>
                `;
            }).join('');
            
            console.log('Table updated');
        })
        .catch(error => {
            console.error('Error loading archived advisers:', error);
            const tbody = document.getElementById('archivedAdvisersBody');
            if (tbody) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Error loading data: ' + error.message + '</td></tr>';
            }
        });
}

function loadArchivedPanels() {
    console.log('loadArchivedPanels called');
    fetch('/panels/archived')
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data);
            const tbody = document.getElementById('archivedPanelsBody');
            if (!tbody) {
                console.error('archivedPanelsBody element not found');
                return;
            }
            
            if (!data || data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No inactive panels</td></tr>';
                return;
            }
            
            tbody.innerHTML = data.map(panel => {
                return `
                <tr>
                    <td>${panel.name || ''}</td>
                    <td>${panel.department || ''}</td>
                    <td>${panel.expertise || ''}</td>
                    <td>${panel.deleted_at ? new Date(panel.deleted_at).toLocaleDateString() : ''}</td>
                    <td>
                        <button class="btn btn-sm btn-secondary" type="button" onclick="window.restorePanel(${panel.id})">
                            <i class="bi bi-arrow-clockwise"></i> Restore
                        </button>
                    </td>
                </tr>
                `;
            }).join('');
            
            console.log('Table updated');
        })
        .catch(error => {
            console.error('Error loading archived panels:', error);
            const tbody = document.getElementById('archivedPanelsBody');
            if (tbody) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error loading data: ' + error.message + '</td></tr>';
            }
        });
}

function restoreAdviser(adviserId) {
    showConfirmation(
        'Are you sure you want to restore this adviser?',
        function() {
            localStorage.setItem('selectedAdviserType', 'ADVISER OVERVIEW');
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

function restorePanel(panelId) {
    showConfirmation(
        'Are you sure you want to restore this panel member?',
        function() {
            localStorage.setItem('selectedAdviserType', 'MANAGE PANELS');
            fetch(`/panels/${panelId}/restore`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('successMessage').textContent = 'Panel member has been successfully restored!';
                    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                    document.getElementById('successModal').addEventListener('hidden.bs.modal', () => {
                        loadArchivedPanels();
                        location.reload();
                    }, { once: true });
                } else {
                    alert('Error restoring panel member: ' + data.message);
                }
            });
        },
        'Restore Panel Member',
        'btn-secondary'
    );
}

// Load archived data when modals are opened
document.addEventListener('DOMContentLoaded', function() {
    const adviserModal = document.getElementById('archiveAdviserModal');
    const panelModal = document.getElementById('archivePanelModal');
    
    if (adviserModal) {
        adviserModal.addEventListener('show.bs.modal', function() {
            console.log('Adviser modal showing...');
            loadArchivedAdvisers();
        });
    }
    
    if (panelModal) {
        panelModal.addEventListener('show.bs.modal', function() {
            console.log('Panel modal showing...');
            loadArchivedPanels();
        });
    }
    
    // Restore active tab after page reload
    const activeTab = localStorage.getItem('activeTab');
    if (activeTab) {
        const tabButton = document.querySelector(`#${activeTab}`);
        if (tabButton) {
            const tabContent = document.querySelector(tabButton.getAttribute('data-bs-target'));
            if (tabContent) {
                // Remove active classes from all tabs
                document.querySelectorAll('.nav-link').forEach(tab => tab.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('show', 'active'));
                
                // Add active classes to selected tab
                tabButton.classList.add('active');
                tabContent.classList.add('show', 'active');
            }
        }
    }
    
    // Save active tab when clicked
    document.querySelectorAll('.nav-link').forEach(tab => {
        tab.addEventListener('click', function() {
            localStorage.setItem('activeTab', this.id);
        });
    });
});

// Archive Functions
function deleteAdviser(adviserId) {
    showConfirmation(
        'Are you sure you want to archive this adviser? This action can be undone by restoring it.',
        function() {
            localStorage.setItem('selectedAdviserType', 'ADVISER OVERVIEW');
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

function deletePanel(panelId) {
    showConfirmation(
        'Are you sure you want to archive this panel member? This action can be undone by restoring it.',
        function() {
            localStorage.setItem('selectedAdviserType', 'MANAGE PANELS');
            fetch(`/panels/${panelId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('successMessage').textContent = 'Panel member has been successfully archived!';
                    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                    document.getElementById('successModal').addEventListener('hidden.bs.modal', () => {
                        location.reload();
                    }, { once: true });
                } else {
                    alert('Error archiving panel member: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while archiving the panel member.');
            });
        },
        'Archive Panel Member'
    );
}

// Permanent Delete Functions
function permanentDeleteAdviser(adviserId) {
    showConfirmation(
        '⚠️ WARNING: This will permanently delete this adviser from the database. This action CANNOT be undone. Are you sure?',
        function() {
            fetch(`/advisers/${adviserId}/force-delete`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadArchivedAdvisers();
                } else {
                    alert('Error permanently deleting adviser: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while permanently deleting the adviser.');
            });
        },
        'Permanently Delete'
    );
}

function permanentDeletePanel(panelId) {
    showConfirmation(
        '⚠️ WARNING: This will permanently delete this panel member from the database. This action CANNOT be undone. Are you sure?',
        function() {
            fetch(`/panels/${panelId}/force-delete`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadArchivedPanels();
                } else {
                    alert('Error permanently deleting panel member: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while permanently deleting the panel member.');
            });
        },
        'Permanently Delete'
    );
}

// Schedule Defense Function
function scheduleDefense(department, section, assignmentId) {
    // Redirect to def-sched with assignment data as URL parameters
    const url = new URL('/def-sched', window.location.origin);
    url.searchParams.set('department', department);
    url.searchParams.set('cluster', section);
    url.searchParams.set('assignment_id', assignmentId);
    
    window.location.href = url.toString();
}

// Evaluate Defense Function
function evaluateDefense(department, section, assignmentId) {
    // Redirect to def-eval with assignment data as URL parameters
    const url = new URL('/def-eval', window.location.origin);
    url.searchParams.set('department', department);
    url.searchParams.set('cluster', section);
    url.searchParams.set('assignment_id', assignmentId);
    
    window.location.href = url.toString();
}

// Show success modal if update was successful
document.addEventListener('DOMContentLoaded', function() {
    @foreach($advisers as $adviser)
        const form{{ $adviser->id }} = document.getElementById('editAdviserForm{{ $adviser->id }}');
        if (form{{ $adviser->id }}) {
            form{{ $adviser->id }}.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const modalElement = document.getElementById('editAdviserModal{{ $adviser->id }}');
                const formAction = this.action;
                
                // Show confirmation modal
                showConfirmation(
                    'Are you sure you want to update this adviser information?',
                    function() {
                        fetch(formAction, {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => {
                            if (response.ok) {
                                // Close the edit modal
                                const modal = bootstrap.Modal.getInstance(modalElement);
                                if (modal) {
                                    modal.hide();
                                }
                                
                                // Show success modal
                                document.getElementById('successMessage').textContent = 'Adviser information has been successfully updated!';
                                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                                successModal.show();
                                
                                // Reload page after modal is dismissed
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

    @foreach($panels as $panel)
        const panelForm{{ $panel->id }} = document.getElementById('editPanelForm{{ $panel->id }}');
        if (panelForm{{ $panel->id }}) {
            panelForm{{ $panel->id }}.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const modalElement = document.getElementById('editPanelModal{{ $panel->id }}');
                const formAction = this.action;
                
                // Show confirmation modal
                showConfirmation(
                    'Are you sure you want to update this panel member information?',
                    function() {
                        fetch(formAction, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => {
                            if (response.redirected || response.url.includes('panel-adviser')) {
                                const modal = bootstrap.Modal.getInstance(modalElement);
                                if (modal) modal.hide();
                                document.getElementById('successMessage').textContent = 'Panel member information has been successfully updated!';
                                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                                successModal.show();
                                document.getElementById('successModal').addEventListener('hidden.bs.modal', () => {
                                    location.reload();
                                }, { once: true });
                                return;
                            }
                            if (!response.ok) throw new Error('Update failed');
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while updating the panel member.');
                        });
                    },
                    'Update Panel Member',
                    'btn-primary'
                );
            });
        }
    @endforeach
});
</script>
@endsection