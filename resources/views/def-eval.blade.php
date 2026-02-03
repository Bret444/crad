@extends('layouts.app')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<style>
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

    body {
        background-color: var(--light-bg);
        font-family: 'Roboto', sans-serif;
        color: var(--dark-text);
    }
    
    #main-container-bs {
        max-width: 1400px; 
        margin: 0 auto;
    }

    #type-selection-view {
        box-shadow: var(--card-shadow);
        min-height: 500px;
    }
    #type-selection-view.hidden {
        display: none !important;
    }
    .defense-type-button {
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
    .defense-type-button:hover {
        background-color: #00509e;
        box-shadow: 0 6px 15px rgba(0, 51, 102, 0.6);
        transform: translateY(-2px);
    }
    .type-button-container {
        gap: 2rem;
    }

    #filter-bar {
        box-shadow: var(--card-shadow);
        min-height: 400px;
        display: block;
    }
    #filter-bar.hidden {
        display: none !important;
    }
    .selected-type-display {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--dark-blue-button);
        text-transform: uppercase;
        white-space: nowrap;
    }
    .btn-schedule-fixed, .btn-custom-merged {
        background-color: var(--primary-blue) !important;
        color: white !important;
        font-weight: 500;
    }

    .table-container {
        box-shadow: var(--card-shadow);
    }
    .table-custom {
        border-collapse: separate; 
        border-spacing: 0;
        table-layout: fixed;
        width: 100%;
    }
    .table-custom th, .table-custom td { 
        border: none;
        border-bottom: 1px solid #f0f4f8;
        padding: 0.8rem 0.5rem; 
        font-size: 0.85rem;
        text-align: center;
        box-sizing: border-box;
    }
    
    .table-custom tbody tr td:nth-child(1) { width: 10% !important; }
    .table-custom tbody tr td:nth-child(2) { width: 10% !important; }
    .table-custom tbody tr td:nth-child(3) { width: 40% !important; }
    .table-custom tbody tr td:nth-child(4) { width: 20% !important; }
    .table-custom tbody tr td:nth-child(5) { width: 20% !important; }
    .table-custom thead th { 
        background-color: var(--dark-blue-button);
        color: white; 
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .merged-cell {
        vertical-align: middle !important;
        text-align: center;
        background-color: #fcfcfc; 
    }
    .cluster-value, .panel-details-table {
        background-color: #fcfcfc;
    }
    .panel-details-table {
        font-size: 0.85rem; 
        text-align: center;
        line-height: 1.6;
        border-right: 1px solid #f0f4f8; 
        border-left: 1px solid #f0f4f8;
        vertical-align: middle;
    }
    .panel-details-table strong { 
        color: var(--dark-text); 
        font-weight: 500; 
        font-style: normal;
    }

    .group-number {
        cursor: pointer;
        color: var(--primary-blue);
        font-weight: 500;
    }
    
    .group-number:hover {
        background-color: #e7f1ff;
        font-weight: 600;
    }
    
    #schedule-table-body tr:nth-child(n+7):nth-child(-n+11) {
        background-color: var(--group-stripe) !important;
    }
    
    .divider-row {
        height: 1px;
    }
    .divider-row td {
        height: 1px !important;
        padding: 0 !important;
        border-bottom: 1px solid #666 !important;
        background-color: transparent !important;
    }

    .evaluation-cell { 
        padding: 0.5rem !important; 
        text-align: center; 
        vertical-align: middle;
        width: 18%;
    }
    
    .status-column {
        padding: 0.5rem !important;
        text-align: center;
        vertical-align: middle;
        width: 15%;
    }
    
    .status-pending { color: #6c757d; background-color: #f8f9fa; }
    .status-ongoing { color: #0d6efd; background-color: #e7f1ff; }
    .status-defended { color: var(--status-green); background-color: var(--status-light-green); }
    .status-redefense { color: #fd7e14; background-color: #fff3cd; }
    .status-passed { color: var(--status-green); background-color: var(--status-light-green); }
    .status-failed { color: var(--status-red); background-color: var(--status-light-red); }
    .status-failed { color: var(--status-red); background-color: var(--status-light-red); } 
    .status-badge { display: inline-block; padding: 0.3em 0.6em; font-size: 0.75rem; border-radius: 0.375rem; font-weight: 500;}
    .status-completed { color: var(--status-green); background-color: var(--status-light-green); }

    .btn-eval { font-size: 0.8rem; padding: 0.4rem 0.8rem; border-radius: 0.375rem; font-weight: 500; border: none; margin: 0.2rem; }
    .btn-eval-passed { background-color: var(--status-light-green); color: var(--status-green); }
    .btn-eval-redefense { background-color: #fff3cd; color: #fd7e14; }
    .btn-eval-failed { background-color: var(--status-light-red); color: var(--status-red); }
    .btn-eval-feedback { background-color: #e7f1ff; color: var(--primary-blue); }
    .btn-eval:hover { opacity: 0.8; }

    .alert-modal .modal-header {
        border-bottom: none;
        padding: 1.5rem 1.5rem 0;
    }
    .alert-modal .modal-body {
        padding: 1rem 1.5rem 1.5rem;
        text-align: center;
    }
    .alert-modal .modal-footer {
        border-top: none;
        padding: 0 1.5rem 1.5rem;
        justify-content: center;
    }
    .alert-modal .alert-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
    .alert-modal .alert-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    .alert-modal .alert-text {
        color: #6c757d;
        margin-bottom: 0;
    }
    .alert-modal.error .alert-icon { color: var(--status-red); }
    .alert-modal.success .alert-icon { color: var(--status-green); }
    .alert-modal.warning .alert-icon { color: var(--status-yellow); }
    .alert-modal.info .alert-icon { color: var(--primary-blue); }
    .alert-modal.question .alert-icon { color: var(--primary-blue); }

    @media (min-width: 992px) {
        #filter-bar .d-grid {
            max-width: 600px;
            margin: 0 auto;
        }
    }
    @media (max-width: 991.98px) {
        #filter-bar .d-grid {
            gap: 1rem;
        }
        .selected-type-display {
            text-align: center;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid" id="main-container-bs">
    
    <div id="type-selection-view" class="text-center p-5 bg-white rounded-4 d-flex flex-column align-items-center justify-content-center mb-4">
        <h1 class="app-title fw-bold text-dark-blue mb-4">DEFENSE EVALUATION</h1>
        <h4 class="text-muted">Select the type of defense to evaluate:</h4>
        <div class="type-button-container d-flex justify-content-center flex-wrap mt-5">
            <button class="defense-type-button" data-defense-type="PRE-ORAL">Pre-oral Defense</button>
            <button class="defense-type-button" data-defense-type="FINAL DEFENSE">Final Defense</button>
            <button class="defense-type-button" data-defense-type="REDEFENSE">Re-defense</button>
        </div>
    </div>
    
    <div id="filter-bar" class="bg-white p-4 rounded-3 mb-4 hidden">
        <div class="d-flex align-items-center mb-4">
            <button class="btn btn-outline-secondary me-3" id="backToFilterButton">
                <i class="bi bi-arrow-left"></i> Back
            </button>
            <h1 class="selected-type-display mb-0" id="defense-type-display"></h1>
        </div>
        
        <div class="d-grid gap-3">
            <div class="filter-group">
                <label for="dept-select" class="form-label small fw-bold">Department</label>
                <select id="dept-select" class="form-select">
                    <option selected disabled value="">Select Department</option>
                    <option value="BSIT">BSIT</option>
                    <option value="CRIM">CRIM</option>
                    <option value="EDUC">EDUC</option>
                    <option value="BSBA">BSBA</option>
                    <option value="Psychology">Psychology</option>
                    <option value="BSHM">BSHM</option>
                    <option value="BSTM">BSTM</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="cluster-select" class="form-label small fw-bold">Cluster</label>
                <select id="cluster-select" class="form-select">
                    <option selected disabled value="">Select Cluster</option>
                    <option value="4101">4101</option>
                    <option value="4102">4102</option>
                    <option value="4103">4103</option>
                    <option value="4104">4104</option>
                    <option value="4105">4105</option>
                    <option value="4106">4106</option>
                    <option value="4107">4107</option>
                    <option value="4108">4108</option>
                    <option value="4109">4109</option>
                    <option value="4110">4110</option>
                </select>
            </div>

            <div class="filter-group" id="redefense-type-group" style="display: none;">
                <label for="redefense-type-select" class="form-label small fw-bold">Defense Type</label>
                <select id="redefense-type-select" class="form-select">
                    <option selected disabled value="">Select Defense Type</option>
                    <option value="PRE-ORAL">Pre-oral Defense</option>
                    <option value="FINAL DEFENSE">Final Defense</option>
                </select>
            </div>

            <button class="btn btn-custom-merged py-2" id="enterButton">
                <i class="bi bi-arrow-right-circle"></i> View Evaluation
            </button>
        </div>
    </div>

    <div id="schedule-view" style="display: none;">
        <div class="d-flex align-items-center mb-3">
            <button class="btn btn-outline-secondary" id="backToFilterFromEval">
                <i class="bi bi-arrow-left"></i> Back
            </button>
        </div>
        <h2 class="text-center mb-4" id="dept-header"></h2>

        <div id="schedule-content" class="table-container">
            <div class="table-responsive">
                <table class="table table-custom align-middle">
                    <thead>
                        <tr>
                            <th>Cluster</th>
                            <th>Group</th>
                            <th>Panels & Adviser</th>
                            <th>Status</th>
                            <th>Defense Evaluation</th>
                        </tr>
                    </thead>
                    <tbody id="schedule-table-body">
                        @php
                            $adviser = 'No Adviser';
                            $chairName = 'No Chairperson';
                            $memberNames = 'No Members';
                        @endphp
                        
                        @for($groupNumber = 1; $groupNumber <= 5; $groupNumber++)
                            @php
                                $groupId = 'A' . $groupNumber;
                            @endphp
                            <tr data-group-id="{{ $groupId }}" @if($groupNumber == 5) class="set-divider" @endif>
                                @if($groupNumber == 1)
                                    <td class="merged-cell cluster-value" rowspan="5">{{ request('cluster') ?? '' }}</td>
                                @endif
                                <td class="group-number" data-group-id="{{ $groupId }}">{{ $groupNumber }}</td> 
                                
                                @if($groupNumber == 1)
                                    <td class="panel-details-table" rowspan="5"
                                        data-adviser="{{ $adviser }}" 
                                        data-chair="{{ $chairName }}" 
                                        data-members="{{ $memberNames }}" 
                                        data-panel-group="A">
                                        <div>
                                            <strong>Adviser:</strong> {{ $adviser }} <br>
                                            <strong>Chairperson:</strong> {{ $chairName }} <br>
                                            Members: {{ $memberNames }}
                                        </div>
                                    </td>
                                @endif

                                <td class="status-column" id="status-{{ $groupId }}">
                                    <span class="status-badge status-pending">Pending</span>
                                </td>

                                <td class="evaluation-cell">
                                    <div class="d-flex flex-column gap-1">
                                        <button class="btn btn-eval btn-eval-passed" onclick="handleEvaluation('{{ $groupId }}', 'Passed')">Passed</button>
                                        <button class="btn btn-eval btn-eval-redefense" onclick="handleEvaluation('{{ $groupId }}', 'Redefense')">Redefense</button>
                                        <button class="btn btn-eval btn-eval-failed" onclick="handleEvaluation('{{ $groupId }}', 'Failed')">Failed</button>
                                        <button class="btn btn-eval btn-eval-feedback" onclick="handleFeedback('{{ $groupId }}')">Feedback</button>
                                    </div>
                                </td>
                            </tr>
                        @endfor
                        
                        <tr class="divider-row">
                            <td colspan="5" style="height: 1px; padding: 0; border-bottom: 1px solid #666; background-color: transparent;"></td>
                        </tr>
                        
                        @for($groupNumber = 1; $groupNumber <= 5; $groupNumber++)
                            @php
                                $groupId = 'B' . $groupNumber;
                            @endphp
                            <tr data-group-id="{{ $groupId }}" @if($groupNumber == 1) style="border-top: 3px solid #333;" @endif>
                                @if($groupNumber == 1)
                                    <td class="merged-cell cluster-value" rowspan="5">{{ request('cluster') ?? '' }}</td>
                                @endif
                                <td class="group-number" data-group-id="{{ $groupId }}">{{ $groupNumber }}</td> 
                                
                                @if($groupNumber == 1)
                                    <td class="panel-details-table" rowspan="5"
                                        data-adviser="{{ $adviser }}" 
                                        data-chair="{{ $chairName }}" 
                                        data-members="{{ $memberNames }}" 
                                        data-panel-group="B">
                                        <div>
                                            <strong>Adviser:</strong> {{ $adviser }} <br>
                                            <strong>Chairperson:</strong> {{ $chairName }} <br>
                                            Members: {{ $memberNames }}
                                        </div>
                                    </td>
                                @endif

                                <td class="status-column" id="status-{{ $groupId }}">
                                    <span class="status-badge status-pending">Pending</span>
                                </td>

                                <td class="evaluation-cell">
                                    <div class="d-flex flex-column gap-1">
                                        <button class="btn btn-eval btn-eval-passed" onclick="handleEvaluation('{{ $groupId }}', 'Passed')">Passed</button>
                                        <button class="btn btn-eval btn-eval-redefense" onclick="handleEvaluation('{{ $groupId }}', 'Redefense')">Redefense</button>
                                        <button class="btn btn-eval btn-eval-failed" onclick="handleEvaluation('{{ $groupId }}', 'Failed')">Failed</button>
                                        <button class="btn btn-eval btn-eval-feedback" onclick="handleFeedback('{{ $groupId }}')">Feedback</button>
                                    </div>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
        
        <div id="empty-state" class="text-center p-5 bg-white rounded-3 shadow-sm" style="display: none;">
            <i class="bi bi-arrow-up-circle-fill" style="font-size: 2.5rem; color: var(--primary-blue);"></i>
            <h4 class="mt-3">Awaiting Selection</h4>
            <p>Please use the filters above to select the Department and Cluster to load the corresponding evaluation.</p>
        </div>
    </div>
</div>

<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add Feedback</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Feedback for <strong id="feedbackGroupId"></strong></p>
                <div class="mb-3">
                    <label for="feedbackText" class="form-label">Your feedback:</label>
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

<!-- Custom Alert Modals -->
<div class="modal fade" id="alertModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 alert-modal">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <i class="alert-icon"></i>
                <h4 class="alert-title"></h4>
                <p class="alert-text"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 alert-modal question">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <i class="bi bi-question-circle-fill alert-icon"></i>
                <h4 class="alert-title"></h4>
                <p class="alert-text"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Group Info Modal -->
<div class="modal fade" id="groupInfoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Group Information</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Department:</strong> <span id="modalDepartment"></span></p>
                <p><strong>Cluster:</strong> <span id="modalCluster"></span></p>
                <p><strong>Group:</strong> <span id="modalGroup"></span></p>
                <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                <hr>
                <p><strong>Adviser:</strong> <span id="modalAdviser"></span></p>
                <p><strong>Chairperson:</strong> <span id="modalChairperson"></span></p>
                <p><strong>Members:</strong> <span id="modalMembers"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentDefenseType = '';
    let groupStatuses = {};

    function showAlert(type, title, text, callback = null) {
        const modal = document.getElementById('alertModal');
        const modalElement = modal.querySelector('.alert-modal');
        const icon = modal.querySelector('.alert-icon');
        const titleEl = modal.querySelector('.alert-title');
        const textEl = modal.querySelector('.alert-text');
        const okBtn = modal.querySelector('.btn-primary');
        
        modalElement.className = 'modal-content rounded-3 alert-modal ' + type;
        
        const icons = {
            error: 'bi bi-x-circle-fill',
            success: 'bi bi-check-circle-fill',
            warning: 'bi bi-exclamation-triangle-fill',
            info: 'bi bi-info-circle-fill'
        };
        icon.className = 'alert-icon ' + icons[type];
        
        titleEl.textContent = title;
        textEl.textContent = text;
        
        if (callback) {
            okBtn.onclick = () => {
                bootstrap.Modal.getInstance(modal).hide();
                callback();
            };
        } else {
            okBtn.onclick = null;
        }
        
        new bootstrap.Modal(modal).show();
    }
    
    function showConfirm(title, text, confirmText = 'Confirm', cancelText = 'Cancel') {
        return new Promise((resolve) => {
            const modal = document.getElementById('confirmModal');
            const titleEl = modal.querySelector('.alert-title');
            const textEl = modal.querySelector('.alert-text');
            const confirmBtn = modal.querySelector('#confirmBtn');
            const cancelBtn = modal.querySelector('.btn-secondary');
            
            titleEl.textContent = title;
            textEl.textContent = text;
            confirmBtn.textContent = confirmText;
            cancelBtn.textContent = cancelText;
            
            confirmBtn.onclick = () => {
                bootstrap.Modal.getInstance(modal).hide();
                resolve(true);
            };
            
            cancelBtn.onclick = () => {
                bootstrap.Modal.getInstance(modal).hide();
                resolve(false);
            };
            
            modal.addEventListener('hidden.bs.modal', () => resolve(false), { once: true });
            
            new bootstrap.Modal(modal).show();
        });
    }

    function initializeGroupStatus(groupId) {
        if (!groupStatuses[groupId]) {
            groupStatuses[groupId] = {
                preOralResult: null,
                finalDefenseResult: null,
                preOralRedefenseResult: null,
                finalRedefenseResult: null
            };
        }
    }

    function canEvaluateFinalDefense(groupId) {
        const status = groupStatuses[groupId];
        if (!status) return false;
        
        return (status.preOralResult === 'Passed') || 
               (status.preOralResult === 'Redefense' && status.preOralRedefenseResult === 'Passed');
    }

    function validateEvaluation(groupId, defenseType) {
        initializeGroupStatus(groupId);
        
        if (defenseType === 'FINAL') {
            if (!canEvaluateFinalDefense(groupId)) {
                const status = groupStatuses[groupId];
                if (!status.preOralResult) {
                    showAlert('error', 'Cannot Evaluate Final Defense', 'Group has not been evaluated in Pre-oral Defense yet.');
                    return false;
                }
                if (status.preOralResult === 'Redefense' && !status.preOralRedefenseResult) {
                    showAlert('error', 'Cannot Evaluate Final Defense', 'Group needs to complete Pre-oral Re-defense first.');
                    return false;
                }
                if (status.preOralResult === 'Failed') {
                    showAlert('error', 'Cannot Evaluate Final Defense', 'Group failed Pre-oral Defense.');
                    return false;
                }
                if (status.preOralResult === 'Redefense' && status.preOralRedefenseResult === 'Failed') {
                    showAlert('error', 'Cannot Evaluate Final Defense', 'Group failed Pre-oral Re-defense.');
                    return false;
                }
                return false;
            }
        }
        
        return true;
    }

    function updateGroupStatus(groupId, defenseType, statusResult) {
        const statusCell = document.getElementById(`status-${groupId}`);
        if (statusCell) {
            let statusText = 'Pending';
            let statusClass = 'status-pending';
            
            if (statusResult === 'Passed') {
                statusText = 'Defended';
                statusClass = 'status-defended';
            } else if (statusResult === 'Redefense') {
                statusText = 'Re-defense';
                statusClass = 'status-redefense';
            } else if (statusResult === 'Failed') {
                if (defenseType === 'REDEFENSE') {
                    statusText = 'Failed';
                    statusClass = 'status-failed';
                } else {
                    statusText = 'Re-defense';
                    statusClass = 'status-redefense';
                }
            }
            
            statusCell.innerHTML = `<span class="status-badge ${statusClass}">${statusText}</span>`;
        }
    }

    window.handleEvaluation = async function(groupId, result) {
        const defenseTypeDisplay = document.getElementById('defense-type-display');
        const currentDefenseType = defenseTypeDisplay ? defenseTypeDisplay.textContent.split(' ')[0] : 'UNKNOWN';
        
        // Check if evaluation button is disabled (defense time not reached)
        const evalButton = document.querySelector(`[data-group-id="${groupId}"] .btn-eval[onclick*="${result}"]`);
        if (evalButton && evalButton.disabled) {
            showAlert('warning', 'Evaluation Not Available', 'Defense evaluation is only available after the scheduled defense time has passed.');
            return;
        }
        
        // Validate if evaluation is allowed
        if (!validateEvaluation(groupId, currentDefenseType)) {
            return;
        }
        
        const confirmMessage = `Are you sure you want to mark Group ${groupId} as "${result.toUpperCase()}"? This action cannot be reversed.`;
        
        const confirmed = await showConfirm('Confirm Evaluation', confirmMessage, 'Yes, confirm!');
        
        if (confirmed) {
            initializeGroupStatus(groupId);
            
            const redefenseType = document.getElementById('redefense-type-select').value;
            
            // Save evaluation to database
            const dept = document.getElementById('dept-select').value;
            const cluster = document.getElementById('cluster-select').value;
            
            const evaluationData = {
                department: dept,
                cluster: cluster,
                group_id: groupId,
                defense_type: currentDefenseType,
                redefense_type: currentDefenseType === 'REDEFENSE' ? redefenseType : null,
                result: result
            };
            
            fetch('/api/evaluations', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(evaluationData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update local group status
                    if (currentDefenseType === 'PRE-ORAL') {
                        groupStatuses[groupId].preOralResult = result;
                    } else if (currentDefenseType === 'FINAL') {
                        groupStatuses[groupId].finalDefenseResult = result;
                    } else if (currentDefenseType === 'REDEFENSE') {
                        if (redefenseType === 'PRE-ORAL') {
                            groupStatuses[groupId].preOralRedefenseResult = result;
                        } else if (redefenseType === 'FINAL DEFENSE') {
                            groupStatuses[groupId].finalRedefenseResult = result;
                        }
                    }
                    
                    // Update status display
                    updateGroupStatus(groupId, currentDefenseType, result);
                    
                    // Disable all evaluation buttons permanently
                    const evalButtons = document.querySelectorAll(`[data-group-id="${groupId}"] .btn-eval`);
                    evalButtons.forEach(btn => {
                        btn.disabled = true;
                        btn.style.opacity = '0.3';
                        btn.style.cursor = 'not-allowed';
                    });
                    
                    // Show selected result
                    const evalCell = document.querySelector(`[data-group-id="${groupId}"] .evaluation-cell`);
                    evalCell.innerHTML = `<span class="status-badge status-${result.toLowerCase()}">${result}</span>`;
                    
                    // Update defense schedule status
                    updateDefenseScheduleStatus(groupId, result, dept, cluster, currentDefenseType);
                    
                    showAlert('success', 'Evaluation Recorded', `Group ${groupId} has been marked as ${result}.`);
                    
                    // If result is Redefense, fetch data and redirect to redefense scheduling
                    if (result === 'Redefense') {
                        fetchRedefenseData(groupId, dept, cluster, currentDefenseType);
                    }
                    
                    // If result is Failed, create redefense schedule entry
                    if (result === 'Failed') {
                        createRedefenseSchedule(groupId, dept, cluster, currentDefenseType);
                    }
                    
                    // ONLY if pre-oral is passed, create final defense schedule entry
                    if (currentDefenseType === 'PRE-ORAL' && result === 'Passed') {
                        createFinalDefenseSchedule(groupId, dept, cluster);
                    }
                    
                    // If redefense is passed, create next defense schedule
                    if (currentDefenseType === 'REDEFENSE' && result === 'Passed') {
                        const redefenseType = document.getElementById('redefense-type-select').value;
                        if (redefenseType === 'PRE-ORAL') {
                            // Pre-oral redefense passed, create final defense schedule
                            createFinalDefenseSchedule(groupId, dept, cluster);
                        }
                        // If final defense redefense passed, no further action needed
                    }
                } else {
                    showAlert('error', 'Save Failed', 'Failed to save evaluation result.');
                }
            })
            .catch(error => {
                console.error('Error saving evaluation:', error);
                showAlert('error', 'Save Failed', 'Failed to save evaluation result.');
            });
        }
    }

    window.handleFeedback = function(groupId) {
        document.getElementById('feedbackGroupId').textContent = `Group ${groupId}`;
        document.getElementById('feedbackText').value = '';
        document.getElementById('saveFeedback').setAttribute('data-group-id', groupId);
        new bootstrap.Modal(document.getElementById('feedbackModal')).show();
    }

    function fetchRedefenseData(groupId, dept, cluster, defenseType) {
        // Fetch assignment data for the group
        fetch(`/api/assignments?department=${dept}&section=${cluster}`)
        .then(response => response.json())
        .then(assignments => {
            const assignment = assignments.find(a => a.department === dept && a.section === cluster);
            const assignmentId = assignment ? assignment.id : 1;
            
            // Create redefense schedule entry
            const scheduleData = {
                department: dept,
                section: cluster,
                group_id: groupId,
                defense_type: 'REDEFENSE',
                original_defense_type: defenseType,
                assignment_id: assignmentId,
                status: 'Pending',
                panel_data: assignment ? {
                    adviser: assignment.adviser || 'No Adviser',
                    chairperson: assignment.panels?.find(p => p.role === 'Chairperson')?.name || 'No Chairperson',
                    members: assignment.panels?.filter(p => p.role === 'Member').map(p => p.name).join(', ') || 'No Members'
                } : {
                    adviser: 'No Adviser',
                    chairperson: 'No Chairperson',
                    members: 'No Members'
                }
            };
            
            return fetch('/defense-schedules', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(scheduleData)
            });
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(`Redefense schedule created for group ${groupId}`);
                
                // Redirect to redefense scheduling with pre-populated data
                const redefenseUrl = `/def-sched?department=${dept}&cluster=${cluster}&assignment_id=${data.assignment_id || 1}&defense_type=REDEFENSE&original_type=${defenseType}`;
                
                showAlert('info', 'Redirecting to Redefense Scheduling', 
                    `Group ${groupId} requires redefense. You will be redirected to set the redefense schedule.`, 
                    () => {
                        window.location.href = redefenseUrl;
                    }
                );
            } else {
                showAlert('error', 'Failed to Create Redefense Schedule', 'Unable to create redefense schedule entry.');
            }
        })
        .catch(error => {
            console.error('Error in redefense flow:', error);
            showAlert('error', 'Redefense Setup Failed', 'Unable to setup redefense scheduling.');
        });
    }
    
    function createRedefenseSchedule(groupId, dept, cluster, defenseType) {
        const scheduleData = {
            department: dept,
            section: cluster,
            group_id: groupId,
            defense_type: 'REDEFENSE',
            original_defense_type: defenseType,
            assignment_id: 1,
            status: 'Pending',
            panel_data: {
                adviser: 'No Adviser',
                chairperson: 'No Chairperson',
                members: 'No Members'
            }
        };
        
        fetch('/defense-schedules', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(scheduleData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(`Redefense schedule created for group ${groupId}`);
            }
        })
        .catch(error => {
            console.error('Error creating redefense schedule:', error);
        });
    }
    
    function createFinalDefenseSchedule(groupId, dept, cluster) {
        const scheduleData = {
            department: dept,
            section: cluster,
            group_id: groupId,
            defense_type: 'FINAL DEFENSE',
            assignment_id: 1,
            status: 'Pending',
            panel_data: {
                adviser: 'No Adviser',
                chairperson: 'No Chairperson',
                members: 'No Members'
            }
        };
        
        fetch('/defense-schedules', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(scheduleData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(`Final defense schedule created for group ${groupId}`);
            }
        })
        .catch(error => {
            console.error('Error creating final defense schedule:', error);
        });
    }
    
    function updateDefenseScheduleStatus(groupId, result, dept, cluster, defenseType) {
        const statusMap = {
            'Passed': 'Passed',
            'Redefense': 'Re-defense', 
            'Failed': 'Failed'
        };
        
        fetch('/defense-schedules/update-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                department: dept,
                section: cluster,
                group_id: groupId,
                defense_type: defenseType,
                status: statusMap[result]
            })
        })
        .catch(error => {
            console.error('Error updating defense schedule status:', error);
        });
    }

    function loadScheduleStatus() {
        const dept = document.getElementById('dept-select').value;
        const cluster = document.getElementById('cluster-select').value;
        
        if (!dept || !cluster || !currentDefenseType) return;
        
        const params = new URLSearchParams({
            defense_type: currentDefenseType === 'REDEFENSE' ? document.getElementById('redefense-type-select').value : currentDefenseType,
            department: dept,
            section: cluster
        });
        
        fetch(`/defense-schedules/by-type?${params}`)
        .then(response => response.json())
        .then(schedules => {
            schedules.forEach(schedule => {
                const statusCell = document.getElementById(`status-${schedule.group_id}`);
                const evalButtons = document.querySelectorAll(`[data-group-id="${schedule.group_id}"] .btn-eval`);
                const evalCell = document.querySelector(`[data-group-id="${schedule.group_id}"] .evaluation-cell`);
                
                if (statusCell) {
                    // Check if already evaluated
                    if (schedule.status === 'Passed' || schedule.status === 'Re-defense' || schedule.status === 'Failed') {
                        statusCell.innerHTML = `<span class="status-badge status-${schedule.status.toLowerCase().replace('-', '')}">${schedule.status}</span>`;
                        evalButtons.forEach(btn => {
                            btn.disabled = true;
                            btn.style.opacity = '0.3';
                            btn.style.cursor = 'not-allowed';
                        });
                        evalCell.innerHTML = `<span class="status-badge status-${schedule.status.toLowerCase().replace('-', '')}">${schedule.status}</span>`;
                    } else {
                        const scheduleDate = new Date(schedule.defense_date);
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);
                        scheduleDate.setHours(0, 0, 0, 0);
                        
                        if (today >= scheduleDate) {
                            statusCell.innerHTML = '<span class="status-badge status-ongoing">Ready for Evaluation</span>';
                            evalButtons.forEach(btn => {
                                btn.disabled = false;
                                btn.style.opacity = '1';
                            });
                        } else {
                            statusCell.innerHTML = '<span class="status-badge status-ongoing">Scheduled</span>';
                            evalButtons.forEach(btn => {
                                btn.disabled = true;
                                btn.style.opacity = '0.5';
                            });
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error loading schedule status:', error);
        });
    }

    function updateScheduleView() {
        const dept = document.getElementById('dept-select').value;
        const cluster = document.getElementById('cluster-select').value;
        const defenseType = currentDefenseType;
        const redefenseType = document.getElementById('redefense-type-select').value;

        let canShowSchedule = dept && cluster && defenseType;
        
        if (defenseType === 'REDEFENSE') {
            canShowSchedule = canShowSchedule && redefenseType;
        }

        if (canShowSchedule) {
            const displayType = defenseType === 'REDEFENSE' ? `${defenseType} (${redefenseType})` : defenseType;
            document.getElementById('dept-header').textContent = `${displayType} EVALUATION: DEPT. OF ${dept} (Cluster ${cluster})`;
            
            filterAssignments(dept, cluster);
        } else {
            const requiredFields = defenseType === 'REDEFENSE' ? 'Department, Cluster, and Defense Type' : 'Department and Cluster';
            document.getElementById('dept-header').textContent = `Select ${requiredFields} to view the ${defenseType} evaluation.`;
            document.getElementById('schedule-content').style.display = 'none';
            document.getElementById('empty-state').style.display = 'block';
        }
    }

    function filterAssignments(dept, cluster) {
        // For REDEFENSE, only show groups that need redefense
        if (currentDefenseType === 'REDEFENSE') {
            const redefenseType = document.getElementById('redefense-type-select').value;
            console.log('Fetching redefense groups for:', { dept, cluster, redefenseType });
            
            getRedefenseGroups(redefenseType).then(redefenseGroups => {
                console.log('Retrieved redefense groups:', redefenseGroups);
                
                // Temporary fallback for testing - add some test groups if none found
                if (redefenseGroups.length === 0) {
                    console.log('No redefense groups from database, using test data');
                    redefenseGroups = ['A1', 'B3']; // Test groups for demonstration
                }
                
                if (redefenseGroups.length > 0) {
                    console.log('Showing table with redefense groups');
                    document.getElementById('schedule-content').style.display = 'block';
                    document.getElementById('empty-state').style.display = 'none';
                    updateTableWithRedefenseGroups(redefenseGroups, dept, cluster);
                } else {
                        console.log('Still no redefense groups after fallback, showing empty state');
                    document.getElementById('schedule-content').style.display = 'none';
                    document.getElementById('empty-state').style.display = 'block';
                }
            }).catch(error => {
                console.error('Error in filterAssignments for redefense:', error);
                // Fallback to test data on error
                console.log('Using fallback test data due to error');
                const testGroups = ['A2', 'B1'];
                document.getElementById('schedule-content').style.display = 'block';
                document.getElementById('empty-state').style.display = 'none';
                updateTableWithRedefenseGroups(testGroups, dept, cluster);
            });
            return;
        }
        
        // Regular assignment fetching for PRE-ORAL and FINAL DEFENSE
        fetch('/api/assignments')
            .then(response => response.json())
            .then(assignments => {
                const filteredAssignments = assignments.filter(assignment => 
                    assignment.department === dept && assignment.section === cluster
                );
                
                if (filteredAssignments.length > 0) {
                    document.getElementById('schedule-content').style.display = 'block';
                    document.getElementById('empty-state').style.display = 'none';
                    updateTableWithAssignments(filteredAssignments, dept, cluster);
                } else {
                    document.getElementById('schedule-content').style.display = 'none';
                    document.getElementById('empty-state').style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error fetching assignments:', error);
                document.getElementById('schedule-content').style.display = 'none';
                document.getElementById('empty-state').style.display = 'block';
            });
    }
    
    function getRedefenseGroups(defenseType) {
        const dept = document.getElementById('dept-select').value;
        const cluster = document.getElementById('cluster-select').value;
        
        return fetch(`/api/group-status?department=${dept}&section=${cluster}&defense_type=${defenseType}&result=Redefense`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Redefense groups data:', data);
                return data.groups ? data.groups.map(group => group.group_id) : [];
            })
            .catch(error => {
                console.error('Error fetching redefense groups:', error);
                return [];
            });
    }
    
    function updateTableWithRedefenseGroups(redefenseGroups, dept, cluster) {
        console.log('Updating table with redefense groups:', redefenseGroups);
        const tbody = document.getElementById('schedule-table-body');
        tbody.innerHTML = '';
        
        // Show only groups that need redefense
        redefenseGroups.forEach((groupId, index) => {
            const set = groupId.charAt(0);
            const group = groupId.substring(1);
            
            const row = `
                <tr data-group-id="${groupId}">
                    <td class="cluster-value">${cluster}</td>
                    <td>${group}</td>
                    <td class="panel-details-table">
                        <div>
                            <strong>Adviser:</strong> No Adviser <br>
                            <strong>Chairperson:</strong> No Chairperson <br>
                            Members: No Members
                        </div>
                    </td>
                    <td class="status-column" id="status-${groupId}">
                        <span class="status-badge status-redefense">Re-defense</span>
                    </td>
                    <td class="evaluation-cell">
                        <div class="d-flex flex-column gap-1">
                            <button class="btn btn-eval btn-eval-passed" onclick="handleEvaluation('${groupId}', 'Passed')">Passed</button>
                            <button class="btn btn-eval btn-eval-redefense" onclick="handleEvaluation('${groupId}', 'Redefense')">Redefense</button>
                            <button class="btn btn-eval btn-eval-failed" onclick="handleEvaluation('${groupId}', 'Failed')">Failed</button>
                            <button class="btn btn-eval btn-eval-feedback" onclick="handleFeedback('${groupId}')">Feedback</button>
                        </div>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
        console.log('Table updated with', redefenseGroups.length, 'redefense groups');
    }
    
    function updateTableWithAssignments(assignments, dept, cluster) {
        document.querySelectorAll('.cluster-value').forEach(cell => {
            cell.textContent = cluster;
        });
        
        const groupOffset = (parseInt(cluster) - 4101) * 10;
        
        ['A', 'B'].forEach(panelGroup => {
            const panelCell = document.querySelector(`[data-panel-group="${panelGroup}"]`);
            if (!panelCell) return;
            
            const groupNumbers = panelGroup === 'A' ? 
                [1, 2, 3, 4, 5].map(g => g + groupOffset) : 
                [6, 7, 8, 9, 10].map(g => g + groupOffset);
            
            let assignment = null;
            for (let i = 0; i < assignments.length; i++) {
                const a = assignments[i];
                const groupNumbers = Array.isArray(a.group_number) ? a.group_number : [];
                for (let j = 0; j < groupNumbers.length; j++) {
                    if (groupNumbers.includes(groupNumbers[j])) {
                        assignment = a;
                        break;
                    }
                }
                if (assignment) break;
            }
            
            if (!assignment) assignment = assignments[0];
            
            let adviser = 'No Adviser';
            let chairName = 'No Chairperson';
            let memberNames = 'No Members';
            
            if (assignment) {
                const panels = assignment.panels || [];
                const chairperson = panels.find(p => p.role === 'Chairperson');
                const members = panels.filter(p => p.role === 'Member' || (p.role && p.role !== 'Chairperson'));
                adviser = assignment.adviser || 'No Adviser';
                chairName = chairperson ? chairperson.name : 'No Chairperson';
                memberNames = members.length > 0 ? members.map(m => m.name).join(', ') : 'No Members';
            }
            
            panelCell.innerHTML = `
                <div>
                    <strong>Adviser:</strong> ${adviser} <br>
                    <strong>Chairperson:</strong> ${chairName} <br>
                    Members: ${memberNames}
                </div>
            `;
            panelCell.setAttribute('data-adviser', adviser);
            panelCell.setAttribute('data-chair', chairName);
            panelCell.setAttribute('data-members', memberNames);
        });
    }

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('group-number')) {
            const groupId = e.target.getAttribute('data-group-id');
            const row = e.target.closest('tr');
            const panelGroup = groupId.charAt(0);
            const groupNumber = e.target.textContent.trim();
            const dept = document.getElementById('dept-select').value;
            const cluster = document.querySelectorAll('.cluster-value')[0]?.textContent || '';
            const statusCell = document.getElementById(`status-${groupId}`);
            const status = statusCell ? statusCell.textContent.trim() : 'Pending';
            
            const panelCell = document.querySelector(`[data-panel-group="${panelGroup}"]`);
            const adviser = panelCell?.getAttribute('data-adviser') || 'No Adviser';
            const chair = panelCell?.getAttribute('data-chair') || 'No Chairperson';
            const members = panelCell?.getAttribute('data-members') || 'No Members';
            
            document.getElementById('modalDepartment').textContent = dept;
            document.getElementById('modalCluster').textContent = cluster;
            document.getElementById('modalGroup').textContent = groupNumber;
            document.getElementById('modalStatus').textContent = status;
            document.getElementById('modalAdviser').textContent = adviser;
            document.getElementById('modalChairperson').textContent = chair;
            document.getElementById('modalMembers').textContent = members;
            
            new bootstrap.Modal(document.getElementById('groupInfoModal')).show();
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const typeSelectionView = document.getElementById('type-selection-view');
        const filterBar = document.getElementById('filter-bar');
        const enterButton = document.getElementById('enterButton');
        const scheduleView = document.getElementById('schedule-view');
        const deptSelect = document.getElementById('dept-select');
        const clusterSelect = document.getElementById('cluster-select');
        
        // Check for URL parameters from panel-adviser redirect
        const urlParams = new URLSearchParams(window.location.search);
        const department = urlParams.get('department');
        const cluster = urlParams.get('cluster');
        const assignmentId = urlParams.get('assignment_id');
        
        if (department && cluster) {
            // Auto-select PRE-ORAL defense type and pre-populate form
            currentDefenseType = 'PRE-ORAL';
            typeSelectionView.style.display = 'none';
            filterBar.style.display = 'grid';
            scheduleView.style.display = 'block';
            
            document.getElementById('defense-type-display').textContent = `${currentDefenseType} EVALUATION`;
            
            // Pre-populate department and cluster
            deptSelect.value = department;
            clusterSelect.value = cluster;
            
            // Hide re-defense type dropdown
            const redefenseTypeGroup = document.getElementById('redefense-type-group');
            redefenseTypeGroup.style.display = 'none';
            
            updateScheduleView();
            
            // Clear URL parameters
            window.history.replaceState({}, document.title, window.location.pathname);
        }

        document.querySelectorAll('.defense-type-button').forEach(button => {
            button.addEventListener('click', function() {
                currentDefenseType = this.getAttribute('data-defense-type');
                localStorage.setItem('selectedEvalType', currentDefenseType);
                
                typeSelectionView.classList.add('hidden');
                filterBar.classList.remove('hidden');
                scheduleView.style.display = 'none';

                document.getElementById('defense-type-display').textContent = `${currentDefenseType} EVALUATION`;
                
                const redefenseTypeGroup = document.getElementById('redefense-type-group');
                if (currentDefenseType === 'REDEFENSE') {
                    redefenseTypeGroup.style.display = 'block';
                } else {
                    redefenseTypeGroup.style.display = 'none';
                }
                
                deptSelect.value = '';
                clusterSelect.value = '';
                document.getElementById('redefense-type-select').value = '';
            });
        });
        
        document.getElementById('backToFilterButton').addEventListener('click', function() {
            localStorage.removeItem('selectedEvalType');
            typeSelectionView.classList.remove('hidden');
            filterBar.classList.add('hidden');
            scheduleView.style.display = 'none';
        });
        
        document.getElementById('backToFilterFromEval').addEventListener('click', function() {
            typeSelectionView.classList.add('hidden');
            filterBar.classList.remove('hidden');
            scheduleView.style.display = 'none';
        });

        enterButton.addEventListener('click', () => {
            typeSelectionView.classList.add('hidden');
            filterBar.classList.add('hidden');
            scheduleView.style.display = 'block';
            updateScheduleView();
            setTimeout(loadScheduleStatus, 500);
        });
        document.getElementById('redefense-type-select').addEventListener('change', () => {
            updateScheduleView();
            setTimeout(loadScheduleStatus, 500);
        });
        
        // Save feedback event listener
        document.getElementById('saveFeedback').addEventListener('click', function() {
            const feedback = document.getElementById('feedbackText').value;
            const groupId = this.getAttribute('data-group-id');
            
            if (!feedback.trim()) {
                showAlert('warning', 'Missing Feedback', 'Please enter your feedback.');
                return;
            }
            
            const dept = document.getElementById('dept-select').value;
            const cluster = document.getElementById('cluster-select').value;
            const defenseTypeDisplay = document.getElementById('defense-type-display');
            const currentDefenseType = defenseTypeDisplay ? defenseTypeDisplay.textContent.split(' ')[0] : 'UNKNOWN';
            const redefenseType = document.getElementById('redefense-type-select').value;
            
            const feedbackData = {
                department: dept,
                cluster: cluster,
                group_id: groupId,
                defense_type: currentDefenseType,
                redefense_type: currentDefenseType === 'REDEFENSE' ? redefenseType : null,
                result: 'Feedback',
                feedback: feedback
            };
            
            fetch('/api/evaluations', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(feedbackData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', 'Feedback Saved', 'Feedback has been saved successfully.');
                    bootstrap.Modal.getInstance(document.getElementById('feedbackModal')).hide();
                } else {
                    showAlert('error', 'Save Failed', 'Failed to save feedback.');
                }
            })
            .catch(error => {
                console.error('Error saving feedback:', error);
                showAlert('error', 'Save Failed', 'Failed to save feedback.');
            });
        });
        
        // Restore saved evaluation type
        const savedEvalType = localStorage.getItem('selectedEvalType');
        if (savedEvalType && !department) {
            currentDefenseType = savedEvalType;
            typeSelectionView.classList.add('hidden');
            filterBar.classList.remove('hidden');
            document.getElementById('defense-type-display').textContent = `${currentDefenseType} EVALUATION`;
            
            const redefenseTypeGroup = document.getElementById('redefense-type-group');
            if (currentDefenseType === 'REDEFENSE') {
                redefenseTypeGroup.style.display = 'block';
            } else {
                redefenseTypeGroup.style.display = 'none';
            }
        } else {
            // Initialize page visibility
            typeSelectionView.classList.remove('hidden');
            filterBar.classList.add('hidden');
            scheduleView.style.display = 'none';
        }
    });
</script>
@endsection




