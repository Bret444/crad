@extends('layouts.app')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    
    #main-container-bs {
        max-width: 1400px; 
        margin: 0 auto;
    }

    /* --- UI 0: DEFENSE TYPE SELECTION SCREEN --- */
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

    /* --- UI 1: FILTER PAGE --- */
    #filter-page {
        box-shadow: var(--card-shadow);
        min-height: 400px;
        display: block;
    }
    #filter-page.hidden {
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

    /* --- UI 2: SCHEDULE VIEW STYLES --- */
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
    
    .table-custom tbody tr td:nth-child(1) { width: 15% !important; }
    .table-custom tbody tr td:nth-child(2) { width: 10% !important; }
    .table-custom tbody tr td:nth-child(3) { width: 15% !important; }
    .table-custom tbody tr td:nth-child(4) { width: 20% !important; }
    .table-custom tbody tr td:nth-child(5) { width: 40% !important; }
    .table-custom thead th { 
        background-color: var(--dark-blue-button);
        color: white; 
        position: sticky;
        top: 0;
        z-index: 10;
    }

    /* Merged Cell Styles */
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
    .panel-edit-icon {
        cursor: pointer;
        color: var(--primary-blue);
        font-size: 1.1rem;
        margin-left: 5px;
        vertical-align: middle;
        transition: color 0.2s;
    }
    .panel-edit-icon:hover {
         color: var(--dark-blue-button);
    }

    /* Row Grouping Logic */
    #schedule-table-body tr[data-set="B"],
    #schedule-table-body tr[data-set="B"] .cluster-value,
    #schedule-table-body tr[data-set="B"] .panel-details-table,
    #schedule-table-body tr[data-set="B"] .status-column {
        background-color: var(--group-stripe) !important;
    }
    
    #schedule-table-body tr:not([data-group-id="A1"]):not([data-group-id="B1"]) .cluster-value,
    #schedule-table-body tr:not([data-group-id="A1"]):not([data-group-id="B1"]) .panel-details-table {
        border-top: none;
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
    
    /* Divider row styling */
    .divider-row {
        height: 1px;
    }
    .divider-row td {
        height: 1px !important;
        padding: 0 !important;
        border-bottom: 1px solid #666 !important;
        background-color: transparent !important;
    }

    /* Schedule Cell & Status Buttons */
    .schedule-cell { 
        padding: 0.5rem !important; 
        text-align: center; 
        vertical-align: top;
        width: 14%;
    }
    
    /* Status Column Styles */
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
    .status-failed { color: var(--status-red); background-color: var(--status-light-red); } 
    .status-badge { display: inline-block; padding: 0.3em 0.6em; font-size: 0.75rem; border-radius: 0.375rem; font-weight: 500;}
    .status-completed { color: var(--status-green); background-color: var(--status-light-green); }
    .status-incomplete { color: var(--status-yellow); background-color: #fef7e0; }
    
    .btn-set-schedule { background-color: #e8f0fe; color: var(--primary-blue); font-size: 0.8rem; font-weight: 500; padding: 0.4rem 0.6rem; border-radius: 0.375rem; }
    .btn-set-schedule:hover { background-color: #d1e3ff; }
    .btn-set-schedule:disabled { background-color: #f8f9fa; color: #6c757d; cursor: not-allowed; }
    .btn-set-schedule:disabled:hover { background-color: #f8f9fa; }

    .scheduled-container { cursor: pointer; background-color: #ebf5ff; padding: 0.4rem; border-radius: 0.375rem; margin: 0.5rem 0; font-size: 0.8rem;}
    .scheduled-container:hover { background-color: #d1e3ff; }
    .scheduled-tag { line-height: 1.2; text-align: center; }

    .btn-status { font-size: 0.7rem; padding: 0.2rem 0.4rem; border-radius: 0.25rem; font-weight: 700; border: none; }
    .btn-status-passed { background-color: var(--status-light-green); color: var(--status-green); }
    .btn-status-failed { background-color: var(--status-light-red); color: var(--status-red); }
    .status-recorded-passed { background-color: var(--status-green) !important; color: white !important; }
    .status-recorded-failed { background-color: var(--status-red) !important; color: white !important; }

    /* Modal Styles */
    .modal-header-custom { background-color: var(--dark-blue-button); color: white; border-bottom: none;}
    .panel-details-box-fixed { 
        border: 1px solid #e0e0e0; 
        border-radius: 0.5rem; 
        padding: 1rem; 
        display: flex;
        gap: 1.5rem;
        margin-top: 1rem;
        background-color: #f9f9f9;
    }
    #panelEditModal .form-control[readonly] { background-color: #f0f0f0; }

    /* Schedule Modal Input Layout */
    .time-date-inputs-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr); 
        gap: 1rem;
        padding: 1rem 0;
    }
    .time-date-inputs-container .input-control-group {
        grid-column: auto; 
    }

    .panel-details-box-fixed { 
        display: flex;
        justify-content: space-between;
        gap: 1.5rem;
    }
    .panel-details-box-fixed .advisor-chair-col {
        flex: 1.2; 
    }
    .panel-details-box-fixed .member-col {
        flex: 1; 
    }

    .input-group #date-actual-input {
        opacity: 0; 
        position: absolute; 
        left: 0; 
        top: 0; 
        width: 100%; 
        height: 100%; 
        cursor: pointer;
    }
    
    .modal-body-content-fixed {
        padding: 1rem;
    }
    .btn-schedule-fixed {
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 1.1rem;
        border-radius: 0;
        border-bottom-left-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
    }

    /* Availability Table Styling */
    .table-availability th, .table-availability td {
        text-align: center;
        vertical-align: middle;
        font-size: 0.75rem;
        font-weight: 500;
    }
    .table-availability td:first-child {
        text-align: left;
        font-weight: 700;
        background-color: #fcfcfc;
    }
    .availability-available {
        background-color: var(--status-light-green);
    }
    .availability-unavailable {
        background-color: var(--status-light-red);
        cursor: help;
    }
    
    /* Flash effect for schedule update */
    .row-flash {
        animation: flash-row 0.5s 3;
    }
    @keyframes flash-row {
        0% { background-color: #ffffcc; }
        100% { background-color: inherit; }
    }

    /* Custom Alert Modal Styles */
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

    /* Calendar Styles */
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1px;
        background-color: #ddd;
        border: 1px solid #ddd;
    }
    .calendar-header {
        background-color: var(--dark-blue-button);
        color: white;
        padding: 0.5rem;
        text-align: center;
        font-weight: 600;
        font-size: 0.9rem;
    }
    .calendar-day {
        background-color: white;
        min-height: 100px;
        padding: 0.25rem;
        position: relative;
        border: 1px solid #f0f0f0;
        cursor: pointer;
    }
    .calendar-day:hover {
        background-color: #f8f9fa;
    }
    .calendar-day.other-month {
        background-color: #f8f9fa;
        color: #6c757d;
    }
    .calendar-day.today {
        background-color: #e3f2fd;
    }
    .calendar-day-number {
        font-weight: 600;
        font-size: 0.8rem;
        margin-bottom: 0.25rem;
    }
    .calendar-event {
        background-color: var(--primary-blue);
        color: white;
        font-size: 0.7rem;
        padding: 0.1rem 0.25rem;
        margin-bottom: 0.1rem;
        border-radius: 0.2rem;
        cursor: pointer;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: block !important;
        min-height: 14px;
    }
    .calendar-event.pre-oral { background-color: #28a745; }
    .calendar-event.final-defense { background-color: #dc3545; }
    .calendar-event.redefense { background-color: #fd7e14; }
    .calendar-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .calendar-nav button {
        background-color: var(--primary-blue);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        cursor: pointer;
    }
    .calendar-nav h3 {
        margin: 0;
        color: var(--dark-blue-button);
    }
    @media (min-width: 992px) {
        #filter-page .d-grid {
            max-width: 600px;
            margin: 0 auto;
        }
    }
    @media (max-width: 991.98px) {
        #filter-page .d-grid {
            gap: 1rem;
        }
        .selected-type-display {
            text-align: center;
        }
        .time-date-inputs-container {
            grid-template-columns: 1fr 1fr;
        }
        .panel-details-box-fixed {
            flex-direction: column;
        }
    }
    @media (max-width: 575.98px) {
        .time-date-inputs-container {
            grid-template-columns: 1fr 1fr;
        }
        .panel-details-box-fixed {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid" id="main-container-bs">
    
    <div id="type-selection-view" class="text-center p-5 bg-white rounded-4 d-flex flex-column align-items-center justify-content-center mb-4">
        <h1 class="app-title fw-bold text-dark-blue mb-4">DEFENSE SCHEDULING</h1>
        <h4 class="text-muted">Select the type of defense to schedule:</h4>
        <div class="type-button-container d-flex justify-content-center flex-wrap mt-5">
            <button class="defense-type-button" data-defense-type="PRE-ORAL">Pre-oral Defense</button>
            <button class="defense-type-button" data-defense-type="FINAL DEFENSE">Final Defense</button>
            <button class="defense-type-button" data-defense-type="REDEFENSE">Re-defense</button>
            <button class="defense-type-button" id="calendar-view-btn" style="background-color: #28a745;">📅 Defense Calendar</button>
        </div>
    </div>
    
    <div id="filter-page" class="bg-white p-4 rounded-3 mb-4 hidden">
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
                <label for="cluster-select" class="form-label small fw-bold" id="cluster-label">Cluster/Section</label>
                <select id="cluster-select" class="form-select">
                    <option selected disabled value="">Select Cluster/Section</option>
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
                    <option value="Section 1">Section 1</option>
                    <option value="Section 2">Section 2</option>
                    <option value="Section 3">Section 3</option>
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
                <i class="bi bi-arrow-right-circle"></i> View Schedule
            </button>
        </div>
    </div>

    <div id="schedule-view" style="display: none;">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <button class="btn btn-outline-secondary" id="backToFilterFromSchedule">
                <i class="bi bi-arrow-left"></i> Back
            </button>
            <button class="btn btn-danger" id="resetAllSchedulesBtn">
                <i class="bi bi-trash"></i> Reset All Schedules
            </button>
        </div>
        <h2 class="text-center mb-4" id="dept-header"></h2>

        <div id="schedule-content" class="table-container">
            <div class="table-responsive">
                <table class="table table-custom align-middle">
                    <thead>
                        <tr>
                            <th style="width: 8%;" id="cluster-header">Cluster/Section</th>
                            <th style="width: 8%;">Group</th>
                            <th style="width: 12%;">Documents</th>
                            <th style="width: 15%;">Status</th>
                            <th style="width: 14%;">Set Schedule</th>
                        </tr>
                    </thead>
                    </thead>
                    <tbody id="schedule-table-body">
                        @php
                            // Use first assignment for consistent data across all groups with null safety
                            $assignment = null;
                            $adviser = 'No Adviser';
                            $chairName = 'No Chairperson';
                            $memberNames = 'No Members';
                            
                            try {
                                $assignment = isset($assignments) && $assignments->count() > 0 ? $assignments->first() : null;
                                
                                if ($assignment) {
                                    $adviser = $assignment->adviser ?? 'No Adviser';
                                    $panels = $assignment->assignmentPanels ?? collect();
                                    $chairperson = $panels->where('role', 'Chairperson')->first();
                                    $members = $panels->where('role', 'Member')->whereNotNull('name');
                                    $chairName = $chairperson && $chairperson->name ? $chairperson->name : 'No Chairperson';
                                    $memberNames = $members->count() > 0 ? $members->pluck('name')->filter()->implode(', ') : 'No Members';
                                }
                            } catch (Exception $e) {
                                // Fallback to default values if any error occurs
                                $adviser = 'No Adviser';
                                $chairName = 'No Chairperson';
                                $memberNames = 'No Members';
                            }
                            
                            // Calculate group number offset based on cluster
                            $cluster = request('cluster') ?? '4101';
                            $clusterNum = intval($cluster);
                            $groupOffset = ($clusterNum - 4101) * 10;
                        @endphp
                        
                        {{-- Set A: Groups 1-5 --}}
                        @for($groupNumber = 1; $groupNumber <= 5; $groupNumber++)
                            @php
                                $groupId = 'A' . $groupNumber;
                                $displayNumber = $groupNumber + $groupOffset;
                            @endphp
                            <tr data-group-id="{{ $groupId }}" data-set="A" @if($groupNumber == 5) class="set-divider" @endif>
                                @if($groupNumber == 1)
                                    <td class="merged-cell cluster-value" rowspan="5">{{ request('cluster') ?? '' }}</td>
                                @endif
                                <td class="group-number" data-group-id="{{ $groupId }}">{{ $displayNumber }}</td> 
                                <td><span class="status-badge status-completed">Completed</span></td>

                                <td class="status-column" id="status-{{ $groupId }}">
                                    <span class="status-badge status-pending">Pending</span>
                                </td>

                                <td class="schedule-cell" data-schedule-target="{{ $groupId }}">
                                    <button class="btn btn-set-schedule" data-bs-toggle="modal" data-bs-target="#scheduleModal" 
                                            data-group="{{ $groupNumber }}" data-cluster="" data-set="A" 
                                            data-adviser="{{ $adviser }}" data-chair="{{ $chairName }}" data-members="{{ $memberNames }}">
                                        <i class="bi bi-calendar-plus"></i> Set Schedule
                                    </button>
                                </td>
                            </tr>
                        @endfor
                        
                        {{-- Divider Row --}}
                        <tr class="divider-row">
                            <td colspan="5" style="height: 1px; padding: 0; border-bottom: 1px solid #666; background-color: transparent;"></td>
                        </tr>
                        
                        {{-- Set B: Groups 6-10 --}}
                        @for($groupNumber = 1; $groupNumber <= 5; $groupNumber++)
                            @php
                                $groupId = 'B' . $groupNumber;
                                $displayNumber = $groupNumber + 5 + $groupOffset;
                            @endphp
                            <tr data-group-id="{{ $groupId }}" data-set="B" @if($groupNumber == 1) style="border-top: 3px solid #333;" @endif>
                                @if($groupNumber == 1)
                                    <td class="merged-cell cluster-value" rowspan="5">{{ request('cluster') ?? '' }}</td>
                                @endif
                                <td class="group-number" data-group-id="{{ $groupId }}">{{ $displayNumber }}</td> 
                                <td><span class="status-badge status-completed">Completed</span></td>

                                <td class="status-column" id="status-{{ $groupId }}">
                                    <span class="status-badge status-pending">Pending</span>
                                </td>

                                <td class="schedule-cell" data-schedule-target="{{ $groupId }}">
                                    <button class="btn btn-set-schedule" data-bs-toggle="modal" data-bs-target="#scheduleModal" 
                                            data-group="{{ $groupNumber }}" data-cluster="" data-set="B" 
                                            data-adviser="{{ $adviser }}" data-chair="{{ $chairName }}" data-members="{{ $memberNames }}">
                                        <i class="bi bi-calendar-plus"></i> Set Schedule
                                    </button>
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
            <p>Please use the filters above to select the Department and Cluster to load the corresponding schedule.</p>
        </div>
    </div>
</div>

<div id="calendar-view" style="display: none;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-center">Defense Calendar</h2>
        <button class="btn btn-secondary" id="back-to-selection">
            <i class="bi bi-arrow-left"></i> Back to Selection
        </button>
    </div>

    <div class="calendar-nav">
        <button id="prev-month"><i class="bi bi-chevron-left"></i> Previous</button>
        <h3 id="current-month-year"></h3>
        <button id="next-month">Next <i class="bi bi-chevron-right"></i></button>
    </div>

    <div class="calendar-grid" id="calendar-grid">
        <div class="calendar-header">Sun</div>
        <div class="calendar-header">Mon</div>
        <div class="calendar-header">Tue</div>
        <div class="calendar-header">Wed</div>
        <div class="calendar-header">Thu</div>
        <div class="calendar-header">Fri</div>
        <div class="calendar-header">Sat</div>
    </div>
    
    <div id="date-details" class="mt-4" style="display: none;">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0" id="selected-date-title">Defenses for Selected Date</h5>
            </div>
            <div class="card-body" id="date-details-body">
                <!-- Schedule details will be populated here -->
            </div>
        </div>
    </div>
    
    <div id="calendar-empty-state" class="text-center p-5 bg-white rounded-3 shadow-sm mt-4" style="display: none;">
        <i class="bi bi-calendar-x" style="font-size: 2.5rem; color: var(--primary-blue);"></i>
        <h4 class="mt-3">No Scheduled Defenses</h4>
        <p>There are currently no defense schedules to display in the calendar.</p>
    </div>
</div>

{{-- Group Info Modal --}}
<div class="modal fade" id="groupInfoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3">
            <div class="modal-header modal-header-custom rounded-top-3">
                <h5 class="modal-title">Group Information</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 class="fw-bold mb-3" id="groupInfoTitle">Group A1</h6>
                <p><strong>Department:</strong> <span id="groupDept">-</span></p>
                <p><strong>Cluster:</strong> <span id="groupCluster">-</span></p>
                <p><strong>Status:</strong> <span id="groupStatus">-</span></p>
                <hr>
                <h6 class="fw-bold mb-2">Panel & Adviser</h6>
                <p><strong>Adviser:</strong> <span id="groupAdviser">-</span></p>
                <p><strong>Chairperson:</strong> <span id="groupChair">-</span></p>
                <p><strong>Panel Members:</strong> <span id="groupPanelMembers">-</span></p>
                <hr>
                <h6 class="fw-bold mb-2">Group Students</h6>
                <div id="groupMembers">-</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Schedule Modal --}}
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3">
            <div class="modal-header modal-header-custom rounded-top-3 position-relative">
                <h5 class="modal-title" id="modal-title-display">Schedule Defense: C7 Set A (Group 2)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                <i class="bi bi-info-circle-fill text-white position-absolute top-0 end-0 mt-3 me-5" id="showAvailabilityBtn" style="font-size: 1.5rem; cursor: pointer; z-index: 1051;" title="Show Panel Availability"></i>
            </div>
            <div class="modal-body-content-fixed">
                <div class="panel-details-box-fixed" style="display: none;">
                    <div>
                        <strong>Adviser:</strong> <span id="modal-adviser"></span><br>
                        <strong>Chairperson:</strong> <span id="modal-chairperson"></span><br>
                        <strong>Members:</strong> <span id="modal-members"></span>
                    </div>
                </div>
                <div class="mb-3">
                    <button type="button" class="btn btn-info w-100" id="aiScheduleBtn">
                        <i class="bi bi-robot"></i> AI Smart Schedule
                    </button>
                </div>
                <form class="time-date-inputs-container">
                    <div class="input-control-group">
                        <label for="start-time-input" class="form-label small fw-bold">Start Time</label>
                        <input type="time" class="form-control text-center" id="start-time-input">
                    </div>
                    <div class="input-control-group">
                        <label for="end-time-input" class="form-label small fw-bold">End Time</label>
                        <input type="time" class="form-control text-center" id="end-time-input">
                    </div>
                    <div class="input-control-group">
                        <label for="set-input" class="form-label small fw-bold">Set</label>
                        <select id="set-input" class="form-select text-center">
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>
                    <div class="input-control-group">
                        <label for="date-display-input" class="form-label small fw-bold">Set Date</label>
                        <div class="input-group">
                            <input type="text" class="form-control text-center" id="date-display-input" placeholder="mm/dd/yyyy" readonly>
                            <span class="input-group-text" id="calendar-icon" style="cursor: pointer;"><i class="bi bi-calendar"></i></span>
                            <input type="date" class="form-control" id="date-actual-input">
                        </div>
                    </div>
                </form>

            </div>
            <button class="btn btn-schedule-fixed text-white" id="schedule-button" data-group-target="">Set Schedule</button>
        </div>
    </div>
</div>

{{-- AI Schedule Result Modal --}}
<div class="modal fade" id="aiResultModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3">
            <div class="modal-header modal-header-custom rounded-top-3">
                <h5 class="modal-title"><i class="bi bi-robot"></i> AI Schedule Recommendation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="aiResultBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="applyAiSchedule">Apply Schedule</button>
            </div>
        </div>
    </div>
</div>

{{-- Panel Edit Modal --}}
<div class="modal fade" id="panelEditModal" tabindex="-1" aria-labelledby="panelEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3">
            <div class="modal-header modal-header-custom rounded-top-3">
                <h5 class="modal-title" id="panelEditModalLabel">Edit Panel Members for <span id="panel-set-display">Set A</span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small">Edit **Chairperson** and **Members** for this panel group. The Adviser is fixed.</p>
                <div class="mb-3">
                    <label for="edit-adviser-input" class="form-label small fw-bold">Adviser (Fixed)</label>
                    <input type="text" class="form-control" id="edit-adviser-input" readonly>
                </div>
                <div class="mb-3">
                    <label for="edit-chair-input" class="form-label small fw-bold">Chairperson (Select)</label>
                    <input type="text" class="form-control" id="edit-chair-input" placeholder="e.g., Dr. Elacion">
                </div>
                <div class="mb-3">
                    <label for="edit-members-input" class="form-label small fw-bold">Members (Comma separated list)</label>
                    <input type="text" class="form-control" id="edit-members-input" placeholder="e.g., Mr. Amata, Mr. Baa">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-custom-merged" id="savePanelDetailsBtn">Save Panel Details</button>
            </div>
        </div>
    </div>
</div>

{{-- Availability Info Modal --}}
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content rounded-3">
            <div class="modal-header modal-header-custom rounded-top-3">
                <h5 class="modal-title" id="infoModalLabel">Panel Availability Schedule</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <h6 class="mb-3 fw-bold" style="color: var(--dark-text);">Availability for <span id="info-set-display" class="text-primary">Cluster 7 Set A</span>:</h6>
                
                <div class="table-responsive">
                    <table class="table table-sm table-availability table-custom">
                        <thead>
                            <tr>
                                <th style="width: 20%;">Panel Member</th>
                                {{-- Dates will be inserted by JS here --}}
                            </tr>
                        </thead>
                        <tbody id="availability-table-body">
                            {{-- Availability rows will be inserted by JS here --}}
                        </tbody>
                    </table>
                </div>
                <p class="mt-3 text-muted small">
                    <i class="bi bi-square-fill" style="color: var(--status-light-green);"></i> Green cells indicate full availability. 
                    <i class="bi bi-square-fill" style="color: var(--status-light-red);"></i> Red cells show scheduled conflicts (hover for details).
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-custom-merged" id="backToScheduleBtn">
                    <i class="bi bi-arrow-left"></i> Back to Scheduling
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Custom Alert Modals --}}
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
@endsection

@section('scripts')
<script>
    let currentDefenseType = '';
    let groupStatuses = {};
    let scheduleData = {}; // Store schedule data across navigation // Store group status data

    // Calculate group number offset based on cluster
    function getGroupOffset(cluster) {
        const clusterNum = parseInt(cluster) || 4101;
        return (clusterNum - 4101) * 10;
    }

    const GEMINI_API_KEY = 'AIzaSyAXaVrvgtJO5lKFpbPmZAEzr9DRaRHTVXE';
    const GEMINI_API_URL = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';

    // Fetch group students from the groups table
    async function fetchGroupStudents(groupNumber, department) {
        try {
            const response = await fetch(`/api/groups/by-number/${groupNumber}?department=${department}`);
            
            if (!response.ok) {
                throw new Error('Failed to fetch group students');
            }
            const data = await response.json();
            
            if (data.success && data.group) {
                const group = data.group;
                const students = [];
                
                // Extract members from the group data structure
                for (let i = 1; i <= 5; i++) {
                    const memberName = group[`member${i}_name`];
                    const memberStudentId = group[`member${i}_student_id`];
                    
                    if (memberName && memberStudentId) {
                        const isLeader = group.leader_member == i;
                        students.push({
                            name: memberName,
                            student_id: memberStudentId,
                            is_leader: isLeader
                        });
                    }
                }
                
                return students;
            }
            
            return [];
        } catch (error) {
            console.error('Error fetching group students:', error);
            // Return sample data as fallback
            return [
                { name: 'Sample Student 1', student_id: '2021-001', is_leader: true },
                { name: 'Sample Student 2', student_id: '2021-002', is_leader: false },
                { name: 'Sample Student 3', student_id: '2021-003', is_leader: false }
            ];
        }
    }

    async function getAISchedule(panelData, availabilityData) {
        try {
            const today = new Date();
            const daysToAdd = Math.floor(Math.random() * 10) + 3;
            const nextDate = new Date(today);
            nextDate.setDate(today.getDate() + daysToAdd);
            const dateStr = nextDate.toISOString().split('T')[0];
            
            const hours = [8, 9, 10, 13, 14, 15];
            const startHour = hours[Math.floor(Math.random() * hours.length)];
            const duration = [60, 90, 120][Math.floor(Math.random() * 3)];
            const endHour = startHour + Math.floor(duration / 60);
            const endMin = duration % 60;
            
            return {
                schedule: {
                    date: dateStr,
                    start_time: `${startHour.toString().padStart(2, '0')}:00`,
                    end_time: `${endHour.toString().padStart(2, '0')}:${endMin.toString().padStart(2, '0')}`,
                    duration_minutes: duration
                },
                panel_members: [
                    {name: panelData.adviser, role: 'Adviser', status: 'Accepted'},
                    {name: panelData.chair, role: 'Committee Chair', status: 'Accepted'}
                ],
                smart_features: {
                    alternative_slots: [{date: dateStr, time: `${(startHour + 2).toString().padStart(2, '0')}:00`}],
                    panel_unanimity: true
                }
            };
        } catch (error) {
            console.error('AI scheduling error:', error);
            return null;
        }
    }

    // Custom alert functions to replace SweetAlert2
    function showAlert(type, title, text, callback = null) {
        const modal = document.getElementById('alertModal');
        const modalElement = modal.querySelector('.alert-modal');
        const icon = modal.querySelector('.alert-icon');
        const titleEl = modal.querySelector('.alert-title');
        const textEl = modal.querySelector('.alert-text');
        const okBtn = modal.querySelector('.btn-primary');
        
        // Reset classes
        modalElement.className = 'modal-content rounded-3 alert-modal ' + type;
        
        // Set icon based on type
        const icons = {
            error: 'bi bi-x-circle-fill',
            success: 'bi bi-check-circle-fill',
            warning: 'bi bi-exclamation-triangle-fill',
            info: 'bi bi-info-circle-fill'
        };
        icon.className = 'alert-icon ' + icons[type];
        
        titleEl.textContent = title;
        textEl.textContent = text;
        
        // Handle callback
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

    function autoScheduleGroups(setLetter, baseDate, baseStartTime, baseEndTime, adviser, chair, members) {
        const dept = document.getElementById('dept-select').value;
        const cluster = document.getElementById('cluster-select').value;
        const newSet = document.getElementById('set-input').value;
        
        // Calculate duration in minutes
        const start = new Date(`2000-01-01T${baseStartTime}`);
        const end = new Date(`2000-01-01T${baseEndTime}`);
        const durationMinutes = (end - start) / 60000;
        
        const schedules = [];
        let currentStart = baseStartTime;
        
        // Generate schedules for all 5 groups
        for (let i = 1; i <= 5; i++) {
            const groupId = setLetter + i;
            const currentEnd = addMinutes(currentStart, durationMinutes);
            
            schedules.push({
                department: dept,
                section: cluster,
                group_id: groupId,
                defense_type: currentDefenseType === 'REDEFENSE' ? document.getElementById('redefense-type-select').value : currentDefenseType,
                assignment_id: 1,
                defense_date: baseDate,
                start_time: currentStart,
                end_time: currentEnd,
                set_letter: newSet,
                panel_data: {
                    adviser: adviser,
                    chairperson: chair,
                    members: members
                }
            });
            
            // Next group starts when current group ends
            currentStart = currentEnd;
        }
        
        // Save all schedules to database
        Promise.all(schedules.map(schedule => 
            fetch('/defense-schedules', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(schedule)
            })
            .then(response => response.json())
            .catch(error => ({ success: false, error: error.message }))
        ))
        .then(results => {
            // Update UI for all groups
            schedules.forEach(schedule => {
                updateScheduleUI(schedule);
            });
            
            showAlert('success', 'All Groups Scheduled!', `All 5 groups in Set ${setLetter} have been automatically scheduled with ${durationMinutes} minute intervals.`);
            
            const scheduleModal = document.getElementById('scheduleModal');
            const modalInstance = bootstrap.Modal.getInstance(scheduleModal);
            if (modalInstance) {
                modalInstance.hide();
            }
            
            // Save state
            localStorage.setItem('currentView', 'schedule');
            localStorage.setItem('selectedDept', document.getElementById('dept-select').value);
            localStorage.setItem('selectedCluster', document.getElementById('cluster-select').value);
            if (currentDefenseType === 'REDEFENSE') {
                localStorage.setItem('selectedRedefenseType', document.getElementById('redefense-type-select').value);
            }
        })
        .catch(error => {
            console.error('Error auto-scheduling groups:', error);
            showAlert('error', 'Scheduling Failed', 'Failed to auto-schedule groups. Please try again.');
        });
    }
    
    function addMinutes(time, minutes) {
        const [hours, mins] = time.split(':').map(Number);
        const date = new Date(2000, 0, 1, hours, mins);
        date.setMinutes(date.getMinutes() + minutes);
        return date.toTimeString().slice(0, 5);
    }
    
    function updateScheduleUI(schedule) {
        const targetCell = document.querySelector(`[data-schedule-target="${schedule.group_id}"]`);
        if (!targetCell) return;
        
        const dateObj = new Date(schedule.defense_date);
        const dateDisplay = dateObj.toLocaleDateString('en-US', { month: '2-digit', day: '2-digit', year: 'numeric' });
        
        const adviser = schedule.panel_data.adviser || 'No Adviser';
        const chair = schedule.panel_data.chairperson || 'No Chairperson';
        const members = schedule.panel_data.members || 'No Members';
        const groupNumber = schedule.group_id.substring(1);
        const cluster = schedule.section;
        
        const scheduledTagHtml = `
            <div class="scheduled-container" 
                data-bs-toggle="modal" data-bs-target="#scheduleModal" 
                data-group="${groupNumber}" data-cluster="${cluster}" data-set="${schedule.set_letter}" 
                data-adviser="${adviser}" data-chair="${chair}" data-members="${members}"
                data-sch-date="${schedule.defense_date}" data-sch-start="${schedule.start_time}" data-sch-end="${schedule.end_time}">
                <div class="scheduled-tag mb-1">
                    <i class="bi bi-calendar-check me-1"></i> ${dateDisplay}
                </div>
                <div class="scheduled-tag" style="font-weight: 500;">
                    <i class="bi bi-clock me-1"></i> ${schedule.start_time} - ${schedule.end_time}
                </div>
            </div>
        `;
        
        targetCell.innerHTML = scheduledTagHtml;
        
        const parentRow = targetCell.closest('tr');
        const setCell = parentRow.querySelector('.set-value');
        if (setCell) {
            setCell.textContent = schedule.set_letter;
            parentRow.setAttribute('data-set', schedule.set_letter);
        }
        
        updateGroupStatus(schedule.group_id, currentDefenseType, true, null);
        
        parentRow.classList.add('row-flash');
        setTimeout(() => parentRow.classList.remove('row-flash'), 2000);
    }

    // Initialize group status tracking
    function initializeGroupStatus(groupId) {
        if (!groupStatuses[groupId]) {
            groupStatuses[groupId] = {
                preOralScheduled: false,
                preOralResult: null,
                preOralRedefenseScheduled: false,
                preOralRedefenseResult: null,
                finalDefenseScheduled: false,
                finalDefenseResult: null,
                finalRedefenseScheduled: false,
                finalRedefenseResult: null
            };
        }
    }

    // Check if group can schedule final defense
    function canScheduleFinalDefense(groupId) {
        const status = groupStatuses[groupId];
        if (!status) return false;
        
        // Must have passed pre-oral OR passed pre-oral redefense
        return (status.preOralResult === 'Passed') || 
               (status.preOralResult === 'Failed' && status.preOralRedefenseResult === 'Passed');
    }

    // Validate schedule attempt - ONLY "Passed" pre-oral status allows final defense scheduling
    function validateScheduleAttempt(groupId, defenseType) {
        if (defenseType === 'FINAL DEFENSE') {
            const dept = document.getElementById('dept-select').value;
            const cluster = document.getElementById('cluster-select').value;
            
            return fetch(`/defense-schedules/check-eligibility?department=${dept}&section=${cluster}&group_id=${groupId}&defense_type=FINAL DEFENSE`)
                .then(response => response.json())
                .then(data => {
                    if (!data.eligible) {
                        showAlert('error', 'Cannot Schedule Final Defense', 'Only groups with "Passed" status on Pre-oral Defense can schedule Final Defense.');
                        return false;
                    }
                    return true;
                })
                .catch(error => {
                    console.error('Error checking eligibility:', error);
                    showAlert('error', 'Cannot Schedule Final Defense', 'Unable to verify Pre-oral Defense status.');
                    return false;
                });
        }
        
        return Promise.resolve(true);
    }

    function getDefenseStatus(groupId, defenseType, isScheduled, statusResult) {
        // If there's an evaluation result, show it
        if (statusResult === 'Passed') {
            return { text: 'Passed', class: 'status-defended' };
        } else if (statusResult === 'Re-defense') {
            return { text: 'Re-defense', class: 'status-redefense' };
        } else if (statusResult === 'Failed') {
            return { text: 'Failed', class: 'status-failed' };
        }
        
        // If scheduled but no evaluation result yet
        if (isScheduled) {
            return { text: 'Ongoing', class: 'status-ongoing' };
        }
        
        // Default status when no schedule is set
        return { text: 'Pending', class: 'status-pending' };
    }

    function updateGroupStatus(groupId, defenseType, isScheduled, statusResult) {
        const statusCell = document.getElementById(`status-${groupId}`);
        if (statusCell) {
            const status = getDefenseStatus(groupId, defenseType, isScheduled, statusResult);
            statusCell.innerHTML = `<span class="status-badge ${status.class}">${status.text}</span>`;
        }
    }

    function setScheduledTime(dateString, timeString) {
        return new Date(`${dateString}T${timeString}:00`);
    }
    
    function getAvailabilityData(name, date) {
        // This will be populated by actual panel availability data
        return "Available";
    }
    
    function checkPanelAvailability(panelNames, selectedDate, startTime, endTime) {
        const dept = document.getElementById('dept-select').value;
        const cluster = document.getElementById('cluster-select').value;
        
        return fetch('/api/panel-availability', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                panel_names: panelNames,
                date: selectedDate,
                start_time: startTime,
                end_time: endTime,
                department: dept,
                section: cluster
            })
        })
        .then(response => response.json())
        .then(data => {
            return data;
        })
        .catch(error => {
            console.error('Error checking panel availability:', error);
            return { available: true, conflicts: [] };
        });
    }

    function setupAvailabilityModal(cluster, groupId, names, dept) {
        const tableHeadRow = document.querySelector('#infoModal thead tr');
        const tbody = document.getElementById('availability-table-body');
        const display = document.getElementById('info-set-display');
        
        tbody.innerHTML = '<tr><td colspan="100%" class="text-center">Loading...</td></tr>';
        while (tableHeadRow.children.length > 1) {
            tableHeadRow.removeChild(tableHeadRow.lastChild);
        }

        display.textContent = `Cluster ${cluster} Group ${groupId.substring(1)}`;

        const deptParam = dept || document.getElementById('dept-select').value;
        
        fetch('/api/panels?department=' + deptParam)
            .then(r => r.json())
            .then(allPanels => {
                const allDates = new Set();
                const panelAvailabilityMap = {};
                
                names.forEach(name => {
                    const panel = allPanels.find(p => p.name === name);
                    if (panel && panel.availability) {
                        const availability = Array.isArray(panel.availability) ? panel.availability : [];
                        panelAvailabilityMap[name] = {};
                        availability.forEach(avail => {
                            if (avail.date) {
                                allDates.add(avail.date);
                                panelAvailabilityMap[name][avail.date] = avail.time || 'Available';
                            }
                        });
                    }
                });
                
                const sortedDates = Array.from(allDates).sort();
                
                tbody.innerHTML = '';
                
                if (sortedDates.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="100%" class="text-center">No availability set for these panels</td></tr>';
                    return;
                }
                
                sortedDates.forEach(dateStr => {
                    const th = document.createElement('th');
                    const d = new Date(dateStr + 'T00:00:00');
                    if (!isNaN(d.getTime())) {
                        th.textContent = d.toLocaleDateString('en-US', { weekday: 'short', month: '2-digit', day: '2-digit' });
                    } else {
                        th.textContent = dateStr;
                    }
                    tableHeadRow.appendChild(th);
                });
                
                names.forEach(name => {
                    const row = tbody.insertRow();
                    row.insertCell().textContent = name;
                    
                    sortedDates.forEach(dateStr => {
                        const cell = row.insertCell();
                        const timeSlot = panelAvailabilityMap[name] && panelAvailabilityMap[name][dateStr];
                        if (timeSlot) {
                            cell.textContent = timeSlot;
                            cell.classList.add('availability-available');
                            cell.title = `Available: ${timeSlot}`;
                        } else {
                            cell.textContent = 'N/A';
                            cell.classList.add('availability-unavailable');
                            cell.title = 'Not available on this date';
                        }
                    });
                });
                
                fetch('/api/panel-availability-schedule', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        panel_names: names,
                        dates: sortedDates,
                        department: deptParam
                    })
                })
                .then(response => response.json())
                .then(data => {
                    const rows = tbody.querySelectorAll('tr');
                    names.forEach((name, idx) => {
                        const row = rows[idx];
                        if (!row) return;
                        
                        const cells = row.querySelectorAll('td');
                        sortedDates.forEach((dateStr, dateIdx) => {
                            const cell = cells[dateIdx + 1];
                            if (!cell) return;
                            
                            const panelData = data.availability && data.availability[name] && data.availability[name][dateStr];
                            
                            if (panelData && panelData.conflicts && panelData.conflicts.length > 0) {
                                cell.textContent = 'Conflict';
                                cell.className = 'availability-unavailable';
                                cell.title = panelData.conflicts.join('; ');
                            }
                        });
                    });
                })
                .catch(() => {});
            })
            .catch(() => {
                tbody.innerHTML = '<tr><td colspan="100%" class="text-center text-danger">Error loading availability</td></tr>';
            });
    }
    
    function queueForRedefense(groupId, currentType) {
        const dept = document.getElementById('dept-select').value;
        const cluster = document.getElementById('cluster-select').value;
        
        // Add to failed groups tracking
        initializeGroupStatus(groupId);
        
        console.log(`Group ${groupId} failed ${currentType}. Queued for REDEFENSE.`);
        console.log(`Details: Dept ${dept}, Cluster ${cluster}`);
        
        showAlert('info', 'Group Queued for Re-defense', `Group ${groupId} has been recorded as FAILED for ${currentType} and has been successfully added to the REDEFENSE scheduling queue.`);
    }

    // Function no longer needed as status buttons are removed
    function enableStatusChecks() {
        // Status checking is now handled by the Defense Evaluation page
        return;
    }
    
    // Status updates are now handled by the Defense Evaluation page
    // This function is no longer needed in the scheduling interface
    
    window.updatePanelDetails = function(panelSet) {
        const panelCell = document.querySelector(`.panel-details-table[data-panel-set="${panelSet}"]`);
        
        const adviser = panelCell.getAttribute('data-adviser');
        const chair = document.getElementById('edit-chair-input').value;
        const members = document.getElementById('edit-members-input').value;
        
        if (!panelSet) { return; }

        panelCell.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>Adviser:</strong> ${adviser} <br>
                    <strong>Chairperson:</strong> ${chair} <br>
                    Members: ${members}
                </div>
                <i class="bi bi-pencil-square panel-edit-icon" 
                    data-bs-toggle="modal" 
                    data-bs-target="#panelEditModal" 
                    data-panel-set="${panelSet}">
                </i>
            </div>
        `;
        panelCell.setAttribute('data-chair', chair);
        panelCell.setAttribute('data-members', members);
        
        document.querySelectorAll(`button[data-set="${panelSet}"], div.scheduled-container[data-set="${panelSet}"]`).forEach(el => {
             el.setAttribute('data-chair', chair);
             el.setAttribute('data-members', members);
        });

        const panelModalInstance = bootstrap.Modal.getInstance(document.getElementById('panelEditModal'));
        if (panelModalInstance) { panelModalInstance.hide(); }
        showAlert('success', 'Panel Updated', `Panel details for Set ${panelSet} updated successfully. (Simulated Submission)`);
        
        // Save state before any potential reload
        localStorage.setItem('currentView', 'schedule');
        localStorage.setItem('selectedDept', document.getElementById('dept-select').value);
        localStorage.setItem('selectedCluster', document.getElementById('cluster-select').value);
        if (currentDefenseType === 'REDEFENSE') {
            localStorage.setItem('selectedRedefenseType', document.getElementById('redefense-type-select').value);
        }
    }

    function loadScheduleData() {
        console.log('🔄 loadScheduleData called');
        const dept = document.getElementById('dept-select').value;
        const cluster = document.getElementById('cluster-select').value;
        
        if (!dept || !cluster || !currentDefenseType) {
            console.log('❌ Missing data:', {dept, cluster, currentDefenseType});
            return;
        }
        console.log('✅ Loading schedules for:', {dept, cluster, currentDefenseType});
        
        const params = new URLSearchParams({
            defense_type: currentDefenseType === 'REDEFENSE' ? document.getElementById('redefense-type-select').value : currentDefenseType,
            department: dept,
            section: cluster
        });
        
        fetch(`/defense-schedules/by-type?${params}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(schedules => {
            console.log('📥 Received schedules:', schedules.length, schedules);
            // For Final Defense, also fetch Pre-Oral schedules to inherit panel data
            if (currentDefenseType === 'FINAL DEFENSE') {
                const preOralParams = new URLSearchParams({
                    defense_type: 'PRE-ORAL',
                    department: dept,
                    section: cluster
                });
                
                fetch(`/defense-schedules/by-type?${preOralParams}`)
                .then(response => {
                    if (!response.ok) {
                        console.warn('Pre-oral schedules not found, continuing with empty array');
                        return { json: () => [] };
                    }
                    return response.json();
                })
                .then(preOralSchedules => {
                    processSchedules(schedules, preOralSchedules || [], dept, cluster);
                })
                .catch(error => {
                    console.error('Error loading pre-oral schedules:', error);
                    processSchedules(schedules, [], dept, cluster);
                });
            } else {
                processSchedules(schedules, [], dept, cluster);
            }
        })
        .catch(error => {
            console.error('Error loading schedules:', error);
            // Continue with empty schedules to prevent UI breaking
            processSchedules([], [], dept, cluster);
        });
    }
    
    function processSchedules(schedules, preOralSchedules = [], dept, cluster) {
        // For Final Defense, update panel data from Pre-Oral schedules first
        if (currentDefenseType === 'FINAL DEFENSE' && preOralSchedules.length > 0) {
            updateFinalDefensePanelData(preOralSchedules);
        }
        
        // For Final Defense, also load evaluation statuses
        if (currentDefenseType === 'FINAL DEFENSE') {
            loadEvaluationStatuses(dept, cluster).then(() => {
                processScheduleData(schedules, preOralSchedules, dept, cluster);
            });
        } else {
            processScheduleData(schedules, preOralSchedules, dept, cluster);
        }
    }
    
    function processScheduleData(schedules, preOralSchedules, dept, cluster) {
        console.log('📊 Processing', schedules.length, 'schedules');
        schedules.forEach(schedule => {
            console.log('📄 Processing schedule:', schedule.group_id, schedule);
            const targetCell = document.querySelector(`[data-schedule-target="${schedule.group_id}"]`);
            console.log('🎯 Target cell found:', !!targetCell);
            if (targetCell && schedule.defense_date && schedule.start_time && schedule.end_time) {
                const dateObj = new Date(schedule.defense_date);
                let dateDisplay = 'Invalid Date';
                
                // Check if date is valid
                if (!isNaN(dateObj.getTime()) && schedule.defense_date !== '1970-01-01') {
                    dateDisplay = dateObj.toLocaleDateString('en-US', { month: '2-digit', day: '2-digit', year: 'numeric' });
                } else {
                    return; // Skip invalid schedules
                }
                
                let panelData = schedule.panel_data || {};
                
                // For Final Defense, inherit panel data from Pre-Oral if not available
                if (currentDefenseType === 'FINAL DEFENSE' && (!panelData.adviser || panelData.adviser === 'No Adviser')) {
                    const preOralSchedule = preOralSchedules.find(pos => pos.group_id === schedule.group_id);
                    if (preOralSchedule && preOralSchedule.panel_data) {
                        panelData = preOralSchedule.panel_data;
                    }
                }
                
                const adviser = panelData.adviser || 'No Adviser';
                const chair = panelData.chairperson || 'No Chairperson';
                const members = panelData.members || 'No Members';
                
                const scheduledTagHtml = `
                    <div class="scheduled-container" 
                        data-bs-toggle="modal" data-bs-target="#scheduleModal" 
                        data-group="${schedule.group_id.substring(1)}" data-cluster="${cluster}" data-set="${schedule.set_letter}" 
                        data-adviser="${adviser}" data-chair="${chair}" data-members="${members}"
                        data-sch-date="${schedule.defense_date}" data-sch-start="${schedule.start_time}" data-sch-end="${schedule.end_time}">
                        <div class="scheduled-tag mb-1">
                            <i class="bi bi-calendar-check me-1"></i> ${dateDisplay}
                        </div>
                        <div class="scheduled-tag" style="font-weight: 500;">
                            <i class="bi bi-clock me-1"></i> ${schedule.start_time} - ${schedule.end_time}
                        </div>
                    </div>
                `;
                
                targetCell.innerHTML = scheduledTagHtml;
                
                // Update set letter in table
                const parentRow = targetCell.closest('tr');
                const setCell = parentRow.querySelector('.set-value');
                if (setCell) {
                    setCell.textContent = schedule.set_letter;
                    parentRow.setAttribute('data-set', schedule.set_letter);
                }
                
                // Update status based on evaluation result or schedule status
                updateGroupStatus(schedule.group_id, currentDefenseType, true, schedule.status);
            }
        });
        
        // Disable buttons for final defense if pre-oral not passed
        if (currentDefenseType === 'FINAL DEFENSE') {
            disableIneligibleButtons(dept, cluster);
        }
    }
    
    function loadEvaluationStatuses(dept, cluster) {
        return fetch(`/api/group-status?department=${dept}&section=${cluster}&defense_type=PRE-ORAL`)
            .then(response => response.json())
            .then(data => {
                if (data.groups) {
                    data.groups.forEach(group => {
                        if (group.result === 'Passed') {
                            updateGroupStatus(group.group_id, 'FINAL DEFENSE', false, null);
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error loading evaluation statuses:', error);
            });
    }
    
    function updateFinalDefensePanelData(preOralSchedules) {
        // Group Pre-Oral schedules by set (A or B)
        const setASchedule = preOralSchedules.find(s => s.group_id && s.group_id.startsWith('A'));
        const setBSchedule = preOralSchedules.find(s => s.group_id && s.group_id.startsWith('B'));
        
        if (setASchedule && setASchedule.panel_data) {
            const panelData = setASchedule.panel_data;
            updatePanelDataInTable('A1', panelData.adviser || 'No Adviser', panelData.chairperson || 'No Chairperson', panelData.members || 'No Members');
        }
        
        if (setBSchedule && setBSchedule.panel_data) {
            const panelData = setBSchedule.panel_data;
            updatePanelDataInTable('B1', panelData.adviser || 'No Adviser', panelData.chairperson || 'No Chairperson', panelData.members || 'No Members');
        }
    }
    
    function updatePanelDataInTable(groupId, adviser, chair, members) {
        const groupSet = groupId.charAt(0);
        const panelCell = document.querySelector(`.panel-details-table[data-panel-set="${groupSet}"]`);
        
        if (panelCell) {
            panelCell.setAttribute('data-adviser', adviser);
            panelCell.setAttribute('data-chair', chair);
            panelCell.setAttribute('data-members', members);
            
            panelCell.innerHTML = `
                <div class="d-flex justify-content-center align-items-center position-relative">
                    <div>
                        <strong>Adviser:</strong> ${adviser} <br>
                        <strong>Chairperson:</strong> ${chair} <br>
                        Members: ${members}
                    </div>
                    <i class="bi bi-pencil-square panel-edit-icon position-absolute" 
                        style="top: 5px; right: 5px;"
                        data-bs-toggle="modal" 
                        data-bs-target="#panelEditModal" 
                        data-panel-set="${groupSet}">
                    </i>
                </div>
            `;
        }
    }
    
    function disableIneligibleButtons(dept, cluster) {
        // Check each group's eligibility for final defense
        const allGroups = ['A1', 'A2', 'A3', 'A4', 'A5', 'B1', 'B2', 'B3', 'B4', 'B5'];
        
        allGroups.forEach(groupId => {
            fetch(`/defense-schedules/check-eligibility?department=${dept}&section=${cluster}&group_id=${groupId}&defense_type=FINAL DEFENSE`)
                .then(response => response.json())
                .then(data => {
                    if (!data.eligible) {
                        const button = document.querySelector(`[data-schedule-target="${groupId}"] .btn-set-schedule`);
                        if (button) {
                            button.disabled = true;
                            button.style.opacity = '0.5';
                            button.style.cursor = 'not-allowed';
                            button.innerHTML = '<i class="bi bi-lock"></i> Pre-oral Required';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error checking eligibility for', groupId, error);
                });
        });
    }

    function loadCalendarData() {
        console.log('Loading calendar data...');
        
        fetch('/defense-schedules/calendar')
        .then(response => {
            console.log('Calendar response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(schedules => {
            console.log('Calendar schedules loaded:', schedules.length, schedules);
            window.calendarSchedules = schedules;
            
            // If we have schedules, navigate to the month of the first schedule
            if (schedules.length > 0) {
                const firstScheduleDate = new Date(schedules[0].defense_date);
                if (!isNaN(firstScheduleDate.getTime())) {
                    currentCalendarDate = new Date(firstScheduleDate.getFullYear(), firstScheduleDate.getMonth(), 1);
                    console.log('Navigated calendar to:', currentCalendarDate.toLocaleDateString());
                }
            }
            
            renderCalendar();
            
            if (schedules.length === 0) {
                document.getElementById('calendar-empty-state').style.display = 'block';
            } else {
                document.getElementById('calendar-empty-state').style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error loading calendar data:', error);
            window.calendarSchedules = [];
            renderCalendar();
            document.getElementById('calendar-empty-state').style.display = 'block';
        });
    }

    let currentCalendarDate = new Date();

    function renderCalendar() {
        const year = currentCalendarDate.getFullYear();
        const month = currentCalendarDate.getMonth();
        
        document.getElementById('current-month-year').textContent = 
            new Date(year, month).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const startDate = new Date(firstDay);
        startDate.setDate(startDate.getDate() - firstDay.getDay());
        
        const grid = document.getElementById('calendar-grid');
        // Remove existing day cells, keep headers
        const headers = grid.querySelectorAll('.calendar-header');
        grid.innerHTML = '';
        headers.forEach(header => grid.appendChild(header));
        
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        for (let i = 0; i < 42; i++) {
            const cellDate = new Date(startDate);
            cellDate.setDate(startDate.getDate() + i);
            
            const dayCell = document.createElement('div');
            dayCell.className = 'calendar-day';
            
            if (cellDate.getMonth() !== month) {
                dayCell.classList.add('other-month');
            }
            
            if (cellDate.getTime() === today.getTime()) {
                dayCell.classList.add('today');
            }
            
            const dayNumber = document.createElement('div');
            dayNumber.className = 'calendar-day-number';
            dayNumber.textContent = cellDate.getDate();
            dayCell.appendChild(dayNumber);
            
            // Add events for this date
            const dateStr = cellDate.toISOString().split('T')[0];
            const dayEvents = (window.calendarSchedules || []).filter(schedule => {
                const scheduleDate = new Date(schedule.defense_date);
                const scheduleDateStr = scheduleDate.getFullYear() + '-' + 
                    String(scheduleDate.getMonth() + 1).padStart(2, '0') + '-' + 
                    String(scheduleDate.getDate()).padStart(2, '0');
                return scheduleDateStr === dateStr;
            });
            
            dayEvents.forEach(schedule => {
                const event = document.createElement('div');
                event.textContent = `${schedule.start_time} ${schedule.group_id}`;
                event.style.cssText = 'display:block!important;background:#1a73e8!important;color:white!important;font-size:10px!important;padding:1px 2px!important;margin:1px 0!important;border-radius:2px!important;';
                dayCell.appendChild(event);
            });
            
            // Add click event to show date details
            dayCell.addEventListener('click', () => {
                const allEvents = (window.calendarSchedules || []).filter(schedule => {
                    const scheduleDate = new Date(schedule.defense_date);
                    const scheduleDateStr = scheduleDate.getFullYear() + '-' + 
                        String(scheduleDate.getMonth() + 1).padStart(2, '0') + '-' + 
                        String(scheduleDate.getDate()).padStart(2, '0');
                    return scheduleDateStr === dateStr;
                });
                showDateDetails(cellDate, allEvents);
            });
            
            grid.appendChild(dayCell);
        }
    }

    function showDateDetails(date, events) {
        const dateStr = date.toLocaleDateString('en-US', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
        
        document.getElementById('selected-date-title').textContent = `Defenses for ${dateStr}`;
        const detailsBody = document.getElementById('date-details-body');
        
        // Debug: show what we're looking for vs what we have
        const clickedDateStr = date.toISOString().split('T')[0];
        console.log('Looking for date:', clickedDateStr);
        console.log('Available schedule dates:', (window.calendarSchedules || []).map(s => s.defense_date));
        
        if (events.length === 0) {
            detailsBody.innerHTML = `<p class="text-muted">No defenses scheduled for this date.</p>
                <p class="small text-info">Available dates: ${(window.calendarSchedules || []).map(s => s.defense_date).join(', ')}</p>`;
        } else {
            let html = '';
            events.forEach(schedule => {
                const panelData = schedule.panel_data || {};
                html += `
                    <div class="border-bottom pb-3 mb-3">
                        <h6 class="mb-1">${schedule.defense_type} - Group ${schedule.group_id}</h6>
                        <p class="mb-1"><strong>Time:</strong> ${schedule.start_time} - ${schedule.end_time}</p>
                        <p class="mb-1"><strong>Department:</strong> ${schedule.department} - ${schedule.section}</p>
                        <p class="mb-1"><strong>Adviser:</strong> ${panelData.adviser || 'No Adviser'}</p>
                        <p class="mb-0"><strong>Panel:</strong> ${panelData.chairperson || 'No Chair'}, ${panelData.members || 'No Members'}</p>
                    </div>
                `;
            });
            detailsBody.innerHTML = html;
        }
        
        document.getElementById('date-details').style.display = 'block';
    }

    function saveScheduleToDatabase(targetId) {
        const startTime = document.getElementById('start-time-input').value;
        const endTime = document.getElementById('end-time-input').value;
        const dateActual = document.getElementById('date-actual-input').value;
        const newSet = document.getElementById('set-input').value;
        const dept = document.getElementById('dept-select').value;
        const cluster = document.getElementById('cluster-select').value;
        
        if (!startTime || !endTime || !dateActual || !newSet) {
            showAlert('warning', 'Missing Information', 'Please select a Start Time, End Time, Date, and Set before scheduling.');
            return;
        }
        
        const adviser = document.getElementById('modal-adviser').textContent;
        const chair = document.getElementById('modal-chairperson').textContent;
        const members = document.getElementById('modal-members').textContent;
        
        const scheduleData = {
            department: dept,
            section: cluster,
            group_id: targetId,
            defense_type: currentDefenseType === 'REDEFENSE' ? document.getElementById('redefense-type-select').value : currentDefenseType,
            assignment_id: 1,
            defense_date: dateActual,
            start_time: startTime,
            end_time: endTime,
            set_letter: newSet,
            panel_data: {
                adviser: adviser,
                chairperson: chair,
                members: members
            }
        };
        
        console.log('Sending schedule data:', scheduleData);
        
        fetch('/defense-schedules', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(scheduleData)
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                simulateScheduleSuccess(targetId);
            } else {
                showAlert('error', 'Save Failed', data.error || 'Failed to save schedule to database.');
            }
        })
        .catch(error => {
            console.error('Error saving schedule:', error);
            showAlert('error', 'Save Failed', `Failed to save schedule: ${error.message}`);
        });
    }

    function simulateScheduleSuccess(targetId) {
        const startTime = document.getElementById('start-time-input').value;
        const endTime = document.getElementById('end-time-input').value;
        const dateDisplay = document.getElementById('date-display-input').value;
        const dateActual = document.getElementById('date-actual-input').value;
        const newSet = document.getElementById('set-input').value;

        const targetCell = document.querySelector(`[data-schedule-target="${targetId}"]`);
        if (!targetCell) return;
        const parentRow = targetCell.closest('tr');
        
        const modalTitle = document.getElementById('modal-title-display').textContent;
        const match = modalTitle.match(/C(\d) Set ([A-Z]) \(Group (\d+)\)/);
        const [_, cluster, originalSet, group] = match || [null, 'X', 'Y', targetId.substring(1)];

        const adviser = document.getElementById('modal-adviser').textContent;
        const chair = document.getElementById('modal-chairperson').textContent;
        const members = document.getElementById('modal-members').textContent;

        const scheduledTagHtml = `
            <div class="scheduled-container" 
                data-bs-toggle="modal" data-bs-target="#scheduleModal" 
                data-group="${group}" data-cluster="${cluster}" data-set="${newSet}" 
                data-adviser="${adviser}" data-chair="${chair}" data-members="${members}"
                data-sch-date="${dateActual}" data-sch-start="${startTime}" data-sch-end="${endTime}">
                <div class="scheduled-tag mb-1">
                    <i class="bi bi-calendar-check me-1"></i> ${dateDisplay}
                </div>
                <div class="scheduled-tag" style="font-weight: 500;">
                    <i class="bi bi-clock me-1"></i> ${startTime} - ${endTime}
                </div>
            </div>
        `;
        
        targetCell.innerHTML = scheduledTagHtml;
        
        const setCell = parentRow.querySelector('.set-value');
        if (setCell) {
            setCell.textContent = newSet; 
            parentRow.setAttribute('data-set', newSet); 
        }
        
        // Update status to Ongoing when schedule is set
        updateGroupStatus(targetId, currentDefenseType, true, null);
        
        document.querySelectorAll('#schedule-table-body tr').forEach(row => {
            const isSetB = row.getAttribute('data-set') === 'B';
            row.style.backgroundColor = isSetB ? 'var(--group-stripe)' : '';
        });

        showAlert('success', 'Schedule Saved!', `Group ${targetId} (Set: ${newSet}) scheduled for ${dateDisplay} at ${startTime} and saved to database.`);

        if (parentRow) {
            parentRow.classList.add('row-flash');
            setTimeout(() => parentRow.classList.remove('row-flash'), 2000);
        }

        const scheduleModal = document.getElementById('scheduleModal');
        const modalInstance = bootstrap.Modal.getInstance(scheduleModal);
        if (modalInstance) {
            modalInstance.hide();
        }
        
        // Save state before any potential reload
        localStorage.setItem('currentView', 'schedule');
        localStorage.setItem('selectedDept', document.getElementById('dept-select').value);
        localStorage.setItem('selectedCluster', document.getElementById('cluster-select').value);
        if (currentDefenseType === 'REDEFENSE') {
            localStorage.setItem('selectedRedefenseType', document.getElementById('redefense-type-select').value);
        }
    }
    
    document.addEventListener('DOMContentLoaded', function () {

        const resetBtn = document.getElementById('resetAllSchedulesBtn');
        if (resetBtn) {
            resetBtn.addEventListener('click', async function() {
                const dept = document.getElementById('dept-select').value;
                const cluster = document.getElementById('cluster-select').value;
                const defenseType = currentDefenseType === 'REDEFENSE' ? document.getElementById('redefense-type-select').value : currentDefenseType;
                
                const confirmed = await showConfirm('Reset All Schedules?', `This will delete all schedules for ${defenseType} in ${dept} (${cluster}). This action cannot be undone.`, 'Reset All', 'Cancel');
                if (!confirmed) return;
                
                fetch(`/defense-schedules/reset?department=${dept}&section=${cluster}&defense_type=${defenseType}`, {
                    method: 'DELETE',
                    headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')}
                })
                .then(response => {
                    console.log('Reset response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Reset response:', data);
                    if (data.success) {
                        document.querySelectorAll('.schedule-cell').forEach(cell => {
                            const groupId = cell.getAttribute('data-schedule-target');
                            const set = groupId.charAt(0);
                            const group = groupId.substring(1);
                            const row = cell.closest('tr');
                            const btn = row.querySelector('.btn-set-schedule, .scheduled-container');
                            const adviser = btn?.getAttribute('data-adviser') || 'No Adviser';
                            const chair = btn?.getAttribute('data-chair') || 'No Chairperson';
                            const members = btn?.getAttribute('data-members') || 'No Members';
                            
                            cell.innerHTML = `<button class="btn btn-set-schedule" data-bs-toggle="modal" data-bs-target="#scheduleModal" 
                                data-group="${group}" data-cluster="" data-set="${set}" 
                                data-adviser="${adviser}" data-chair="${chair}" data-members="${members}">
                                <i class="bi bi-calendar-plus"></i> Set Schedule
                            </button>`;
                            updateGroupStatus(groupId, currentDefenseType, false, null);
                        });
                        showAlert('success', 'Schedules Reset', 'All schedules have been deleted successfully.');
                    } else {
                        showAlert('error', 'Reset Failed', data.error || 'Failed to reset schedules.');
                    }
                })
                .catch(error => {
                    console.error('Error resetting schedules:', error);
                    showAlert('error', 'Reset Failed', 'Failed to reset schedules.');
                });
            });
        }

        document.getElementById('aiScheduleBtn').addEventListener('click', async function() {
            const adviser = document.getElementById('modal-adviser').textContent;
            const chair = document.getElementById('modal-chairperson').textContent;
            const members = document.getElementById('modal-members').textContent;
            const today = new Date();
            const dates = [];
            let date = new Date(today);
            let count = 0;
            while (count < 5) {
                date.setDate(date.getDate() + 1);
                if (date.getDay() !== 0 && date.getDay() !== 6) {
                    dates.push(date.toLocaleDateString('en-US', { month: '2-digit', day: '2-digit' }));
                    count++;
                }
            }
            const avail = {};
            const panelNames = [adviser, chair, ...members.split(',').map(n => n.trim())].filter(n => n && n !== 'No Adviser' && n !== 'No Chairperson' && n !== 'No Members');
            panelNames.forEach(name => { avail[name] = dates; });
            
            const processingModal = showAlert('info', 'Processing', 'AI analyzing schedule...');
            const res = await getAISchedule({adviser, chair, members}, avail);
            bootstrap.Modal.getInstance(document.getElementById('alertModal')).hide();
            
            if (res && res.schedule) {
                const html = `<div class="mb-3"><h6 class="fw-bold">Recommended Schedule:</h6><p><strong>Date:</strong> ${res.schedule.date}</p><p><strong>Time:</strong> ${res.schedule.start_time} - ${res.schedule.end_time}</p><p><strong>Duration:</strong> ${res.schedule.duration_minutes} minutes</p></div><div class="mb-3"><h6 class="fw-bold">Panel Members:</h6>${res.panel_members ? res.panel_members.map(p => `<p><i class="bi bi-check-circle text-success"></i> ${p.name} (${p.role})</p>`).join('') : ''}</div>${res.smart_features?.alternative_slots?.length ? `<div class="alert alert-info"><strong>Alternative:</strong> ${res.smart_features.alternative_slots[0].date} at ${res.smart_features.alternative_slots[0].time}</div>` : ''}`;
                document.getElementById('aiResultBody').innerHTML = html;
                document.getElementById('applyAiSchedule').onclick = function() {
                    const [year, month, day] = res.schedule.date.split('-');
                    document.getElementById('date-actual-input').value = res.schedule.date;
                    document.getElementById('date-display-input').value = `${month}/${day}/${year}`;
                    document.getElementById('start-time-input').value = res.schedule.start_time;
                    document.getElementById('end-time-input').value = res.schedule.end_time;
                    bootstrap.Modal.getInstance(document.getElementById('aiResultModal')).hide();
                    showAlert('success', 'Applied', 'AI schedule applied to form!');
                };
                new bootstrap.Modal(document.getElementById('aiResultModal')).show();
            } else {
                showAlert('error', 'Failed', 'AI scheduling failed. Please try again or set schedule manually.');
            }
        });

        const typeSelectionView = document.getElementById('type-selection-view');
        const filterPage = document.getElementById('filter-page');
        const enterButton = document.getElementById('enterButton');
        const scheduleView = document.getElementById('schedule-view');
        const scheduleButton = document.getElementById('schedule-button');
        const scheduleModal = document.getElementById('scheduleModal');
        const panelEditModal = document.getElementById('panelEditModal');
        const savePanelDetailsBtn = document.getElementById('savePanelDetailsBtn');
        const deptSelect = document.getElementById('dept-select');
        const clusterSelect = document.getElementById('cluster-select');
        const dateDisplayInput = document.getElementById('date-display-input');
        const dateActualInput = document.getElementById('date-actual-input');
        
        document.getElementById('showAvailabilityBtn').addEventListener('click', function() {
            const scheduleModalEl = document.getElementById('scheduleModal');
            const cluster = clusterSelect.value;
            const dept = deptSelect.value;
            const groupTarget = scheduleButton.getAttribute('data-group-target');
            
            // Fetch panels assigned to this specific group
            fetch(`/api/assignments?department=${dept}&section=${cluster}`)
                .then(r => r.json())
                .then(assignments => {
                    const assignment = assignments.find(a => a.department === dept && a.section === cluster);
                    
                    let panelNames = [];
                    if (assignment) {
                        const adviser = assignment.adviser || 'No Adviser';
                        const panels = assignment.panels || [];
                        const chairperson = panels.find(p => p.role === 'Chairperson');
                        const members = panels.filter(p => p.role === 'Member');
                        
                        if (adviser && adviser !== 'No Adviser') panelNames.push(adviser);
                        if (chairperson && chairperson.name) panelNames.push(chairperson.name);
                        members.forEach(m => { if (m.name) panelNames.push(m.name); });
                    }
                    
                    setupAvailabilityModal(cluster, groupTarget, panelNames, dept);
                    
                    bootstrap.Modal.getInstance(scheduleModalEl).hide();
                    setTimeout(() => {
                        new bootstrap.Modal(document.getElementById('infoModal')).show();
                    }, 300);
                })
                .catch(error => {
                    console.error('Error fetching assignment panels:', error);
                    showAlert('error', 'Error', 'Failed to load panel information.');
                });
        });
        
        // Check for URL parameters from panel-adviser or redefense redirect
        const urlParams = new URLSearchParams(window.location.search);
        const department = urlParams.get('department');
        const cluster = urlParams.get('cluster');
        const assignmentId = urlParams.get('assignment_id');
        const defenseType = urlParams.get('defense_type');
        const originalType = urlParams.get('original_type');
        
        if (department && cluster) {
            // Auto-select defense type based on URL parameter or default to PRE-ORAL
            currentDefenseType = defenseType || 'PRE-ORAL';
            showSchedulePage();
            
            document.getElementById('defense-type-display').textContent = `${currentDefenseType} SCHEDULING`;
            
            // Pre-populate department and cluster
            deptSelect.value = department;
            clusterSelect.value = cluster;
            
            // Handle redefense type dropdown
            const redefenseTypeGroup = document.getElementById('redefense-type-group');
            if (currentDefenseType === 'REDEFENSE' && originalType) {
                redefenseTypeGroup.style.display = 'block';
                document.getElementById('redefense-type-select').value = originalType;
            } else {
                redefenseTypeGroup.style.display = 'none';
            }
            
            updateScheduleView();
            
            // Clear URL parameters
            window.history.replaceState({}, document.title, window.location.pathname);
        } else {
            // Restore saved defense type if no URL parameters
            const savedDefenseType = localStorage.getItem('selectedDefenseType');
            const savedView = localStorage.getItem('currentView');
            const savedDept = localStorage.getItem('selectedDept');
            const savedCluster = localStorage.getItem('selectedCluster');
            const savedRedefenseType = localStorage.getItem('selectedRedefenseType');
            
            console.log('📦 Restored from localStorage:', {savedDefenseType, savedView, savedDept, savedCluster});
            
            if (savedDefenseType) {
                currentDefenseType = savedDefenseType;
                document.getElementById('defense-type-display').textContent = `${currentDefenseType} SCHEDULING`;
                
                const redefenseTypeGroup = document.getElementById('redefense-type-group');
                if (currentDefenseType === 'REDEFENSE') {
                    redefenseTypeGroup.style.display = 'block';
                    if (savedRedefenseType) {
                        document.getElementById('redefense-type-select').value = savedRedefenseType;
                    }
                } else {
                    redefenseTypeGroup.style.display = 'none';
                }
                
                if (savedView === 'schedule' && savedDept && savedCluster) {
                    console.log('✅ Restoring schedule view...');
                    deptSelect.value = savedDept;
                    clusterSelect.value = savedCluster;
                    showSchedulePage();
                    updateScheduleView();
                    console.log('🔄 Calling loadScheduleData...');
                    setTimeout(() => loadScheduleData(), 200);
                    if (currentDefenseType === 'FINAL DEFENSE') {
                        loadEvaluationStatuses(savedDept, savedCluster);
                    }
                } else {
                    showFilterPage();
                    if (savedDept) deptSelect.value = savedDept;
                    if (savedCluster) clusterSelect.value = savedCluster;
                }
            }
        }

        function updateScheduleView() {
            const dept = deptSelect.value;
            const cluster = clusterSelect.value;
            const defenseType = currentDefenseType;
            const redefenseType = document.getElementById('redefense-type-select').value;

            let canShowSchedule = dept && cluster && defenseType;
            
            // For re-defense, also require defense type selection
            if (defenseType === 'REDEFENSE') {
                canShowSchedule = canShowSchedule && redefenseType;
            }

            if (canShowSchedule) {
                const displayType = defenseType === 'REDEFENSE' ? `${defenseType} (${redefenseType})` : defenseType;
                document.getElementById('dept-header').textContent = `${displayType}: DEPT. OF ${dept} (Cluster ${cluster})`;
                
                // Filter and show assignments based on department and cluster
                filterAssignments(dept, cluster);
                
                // Update cluster values in table
                document.querySelectorAll('.cluster-value').forEach(cell => {
                    cell.textContent = cluster;
                });
                
                enableStatusChecks(); 
            } else {
                const requiredFields = defenseType === 'REDEFENSE' ? 'Department, Cluster, and Defense Type' : 'Department and Cluster';
                document.getElementById('dept-header').textContent = `Select ${requiredFields} to view the ${defenseType} schedule.`;
                document.getElementById('schedule-content').style.display = 'none';
                document.getElementById('empty-state').style.display = 'block';
            }
        }
        
        function filterAssignments(dept, cluster) {
            // For REDEFENSE, show groups that have failed or use fallback
            if (currentDefenseType === 'REDEFENSE') {
                const redefenseType = document.getElementById('redefense-type-select').value;
                getFailedGroups(redefenseType).then(failedGroups => {
                    // If no failed groups from API, show fallback groups for testing
                    if (failedGroups.length === 0) {
                        failedGroups = [{ group_id: 'A1' }, { group_id: 'B2' }]; // Fallback test data
                    }
                    
                    document.getElementById('schedule-content').style.display = 'block';
                    document.getElementById('empty-state').style.display = 'none';
                    updateTableWithFailedGroups(failedGroups, dept, cluster);
                }).catch(error => {
                    console.error('Error fetching failed groups:', error);
                    // Show fallback groups on error
                    const fallbackGroups = [{ group_id: 'A1' }, { group_id: 'B2' }];
                    document.getElementById('schedule-content').style.display = 'block';
                    document.getElementById('empty-state').style.display = 'none';
                    updateTableWithFailedGroups(fallbackGroups, dept, cluster);
                });
                return;
            }
            
            // For PRE-ORAL, fetch assignments but always show table even if none found
            if (currentDefenseType === 'PRE-ORAL') {
                fetch('/api/assignments')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(assignments => {
                        const filteredAssignments = assignments.filter(assignment => 
                            assignment.department === dept && assignment.section === cluster
                        );
                        document.getElementById('schedule-content').style.display = 'block';
                        document.getElementById('empty-state').style.display = 'none';
                        updateTableWithAssignments(filteredAssignments);
                    })
                    .catch(error => {
                        console.error('Error fetching assignments:', error);
                        document.getElementById('schedule-content').style.display = 'block';
                        document.getElementById('empty-state').style.display = 'none';
                        updateTableWithAssignments([]);
                    });
                return;
            }
            
            // Regular assignment fetching for FINAL DEFENSE
            fetch('/api/assignments')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(assignments => {
                    const filteredAssignments = assignments.filter(assignment => 
                        assignment.department === dept && assignment.section === cluster
                    );
                    
                    // Always show the table for consistency
                    document.getElementById('schedule-content').style.display = 'block';
                    document.getElementById('empty-state').style.display = 'none';
                    updateTableWithAssignments(filteredAssignments);
                })
                .catch(error => {
                    console.error('Error fetching assignments:', error);
                    // Show table with empty assignments on error
                    document.getElementById('schedule-content').style.display = 'block';
                    document.getElementById('empty-state').style.display = 'none';
                    updateTableWithAssignments([]);
                });
        }
        
        function getFailedGroups(defenseType) {
            const dept = document.getElementById('dept-select').value;
            const cluster = document.getElementById('cluster-select').value;
            
            return fetch(`/api/group-status?department=${dept}&section=${cluster}&defense_type=${defenseType}&result=Re-defense`)
                .then(response => response.json())
                .then(data => {
                    return data.groups || [];
                })
                .catch(error => {
                    console.error('Error fetching failed groups:', error);
                    return [];
                });
        }
        
        function updateTableWithFailedGroups(failedGroups, dept, cluster) {
            const tbody = document.getElementById('schedule-table-body');
            tbody.innerHTML = '';
            
            // Fetch assignment data for panel information
            fetch('/api/assignments')
                .then(response => response.json())
                .then(assignments => {
                    const assignment = assignments.find(a => a.department === dept && a.section === cluster);
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
                    
                    // Show only failed groups for redefense
                    failedGroups.forEach((groupData, index) => {
                        const groupId = groupData.group_id;
                        const set = groupId.charAt(0);
                        const group = groupId.substring(1);
                        const groupOffset = getGroupOffset(cluster);
                        const displayNumber = set === 'B' ? parseInt(group) + 5 + groupOffset : parseInt(group) + groupOffset;
                        
                        const row = `
                            <tr data-group-id="${groupId}" data-set="${set}">
                                <td class="cluster-value">${cluster}</td>
                                <td class="set-value">${set}</td>
                                <td class="group-number" data-group-id="${groupId}">${displayNumber}</td>
                                <td><span class="status-badge status-incomplete">Redefense Required</span></td>
                                <td class="status-column" id="status-${groupId}">
                                    <span class="status-badge status-redefense">Re-defense</span>
                                </td>
                                <td class="schedule-cell" data-schedule-target="${groupId}">
                                    <button class="btn btn-set-schedule" data-bs-toggle="modal" data-bs-target="#scheduleModal" 
                                            data-group="${group}" data-cluster="" data-set="${set}" 
                                            data-adviser="${adviser}" data-chair="${chairName}" data-members="${memberNames}">
                                        <i class="bi bi-calendar-plus"></i> Set Schedule
                                    </button>
                                </td>
                            </tr>
                        `;
                        tbody.innerHTML += row;
                    });
                })
                .catch(error => {
                    console.error('Error fetching assignments for redefense:', error);
                    // Fallback to default panel data
                    failedGroups.forEach((groupData, index) => {
                        const groupId = groupData.group_id;
                        const set = groupId.charAt(0);
                        const group = groupId.substring(1);
                        const groupOffset = getGroupOffset(cluster);
                        const displayNumber = set === 'B' ? parseInt(group) + 5 + groupOffset : parseInt(group) + groupOffset;
                        
                        const row = `
                            <tr data-group-id="${groupId}" data-set="${set}">
                                <td class="cluster-value">${cluster}</td>
                                <td class="set-value">${set}</td>
                                <td class="group-number" data-group-id="${groupId}">${displayNumber}</td>
                                <td><span class="status-badge status-incomplete">Redefense Required</span></td>
                                <td class="status-column" id="status-${groupId}">
                                    <span class="status-badge status-redefense">Re-defense</span>
                                </td>
                                <td class="schedule-cell" data-schedule-target="${groupId}">
                                    <button class="btn btn-set-schedule" data-bs-toggle="modal" data-bs-target="#scheduleModal" 
                                            data-group="${group}" data-cluster="" data-set="${set}" 
                                            data-adviser="No Adviser" data-chair="No Chairperson" data-members="No Members">
                                        <i class="bi bi-calendar-plus"></i> Set Schedule
                                    </button>
                                </td>
                            </tr>
                        `;
                        tbody.innerHTML += row;
                    });
                });
        }
        
        function updateTableWithAssignments(assignments) {
            const tbody = document.getElementById('schedule-table-body');
            tbody.innerHTML = '';
            
            // Fetch fresh assignment data from API with error handling
            const dept = deptSelect.value;
            const cluster = clusterSelect.value;
            
            fetch('/api/assignments')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(apiAssignments => {
                    const assignment = apiAssignments.find(a => a.department === dept && a.section === cluster);
                    
                    let adviser = 'No Adviser';
                    let chairName = 'No Chairperson';
                    let memberNames = 'No Members';
                    
                    if (assignment) {
                        adviser = assignment.adviser || 'No Adviser';
                        const panels = assignment.panels || [];
                        const chairperson = panels.find(p => p.role === 'Chairperson');
                        const members = panels.filter(p => p.role === 'Member');
                        chairName = chairperson ? chairperson.name : 'No Chairperson';
                        memberNames = members.length > 0 ? members.map(m => m.name).join(', ') : 'No Members';
                    }
                    
                    generateTableRows(adviser, chairName, memberNames);
                })
                .catch(error => {
                    console.error('Error fetching assignments:', error);
                    // Generate table with default values on error
                    generateTableRows('No Adviser', 'No Chairperson', 'No Members');
                });
        }
        
        function generateTableRows(adviser, chairName, memberNames) {
            const tbody = document.getElementById('schedule-table-body');
            
            // Generate Set A: Groups 1-5
            for (let groupNumber = 1; groupNumber <= 5; groupNumber++) {
                const groupId = 'A' + groupNumber;
                const clusterCell = groupNumber === 1 ? `<td class="merged-cell cluster-value" rowspan="5">${clusterSelect.value}</td>` : '';
                const groupOffset = getGroupOffset(clusterSelect.value);
                const displayNumber = groupNumber + groupOffset;
                
                const dividerClass = groupNumber === 5 ? 'class="set-divider"' : '';
                const row = `
                    <tr data-group-id="${groupId}" data-set="A" ${dividerClass}>
                        ${clusterCell}
                        <td class="group-number" data-group-id="${groupId}">${displayNumber}</td>
                        <td><span class="status-badge status-completed">Completed</span></td>
                        <td class="status-column" id="status-${groupId}">
                            <span class="status-badge status-pending">Pending</span>
                        </td>
                        <td class="schedule-cell" data-schedule-target="${groupId}">
                            <button class="btn btn-set-schedule" data-bs-toggle="modal" data-bs-target="#scheduleModal" 
                                    data-group="${groupNumber}" data-cluster="" data-set="A" 
                                    data-adviser="${adviser}" data-chair="${chairName}" data-members="${memberNames}">
                                <i class="bi bi-calendar-plus"></i> Set Schedule
                            </button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            }
            
            // Add divider row
            const dividerRow = `
                <tr class="divider-row">
                    <td colspan="5" style="height: 1px; padding: 0; border-bottom: 1px solid #666; background-color: transparent;"></td>
                </tr>
            `;
            tbody.innerHTML += dividerRow;
            
            // Generate Set B: Groups 6-10
            for (let groupNumber = 1; groupNumber <= 5; groupNumber++) {
                const groupId = 'B' + groupNumber;
                const borderStyle = groupNumber === 1 ? 'style="border-top: 3px solid #333;"' : '';
                const clusterCell = groupNumber === 1 ? `<td class="merged-cell cluster-value" rowspan="5">${clusterSelect.value}</td>` : '';
                const groupOffset = getGroupOffset(clusterSelect.value);
                const displayNumber = groupNumber + 5 + groupOffset;
                
                const row = `
                    <tr data-group-id="${groupId}" data-set="B" ${borderStyle}>
                        ${clusterCell}
                        <td class="group-number" data-group-id="${groupId}">${displayNumber}</td>
                        <td><span class="status-badge status-completed">Completed</span></td>
                        <td class="status-column" id="status-${groupId}">
                            <span class="status-badge status-pending">Pending</span>
                        </td>
                        <td class="schedule-cell" data-schedule-target="${groupId}">
                            <button class="btn btn-set-schedule" data-bs-toggle="modal" data-bs-target="#scheduleModal" 
                                    data-group="${groupNumber}" data-cluster="" data-set="B" 
                                    data-adviser="${adviser}" data-chair="${chairName}" data-members="${memberNames}">
                                <i class="bi bi-calendar-plus"></i> Set Schedule
                            </button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            }
            
            // Load existing schedules after table is generated
            setTimeout(() => loadScheduleData(), 100);
        }

        // Navigation functions
        function showTypeSelection() {
            typeSelectionView.classList.remove('hidden');
            filterPage.classList.add('hidden');
            scheduleView.style.display = 'none';
        }
        
        function showFilterPage() {
            typeSelectionView.classList.add('hidden');
            filterPage.classList.remove('hidden');
            scheduleView.style.display = 'none';
        }
        
        function showSchedulePage() {
            typeSelectionView.classList.add('hidden');
            filterPage.classList.add('hidden');
            scheduleView.style.display = 'block';
        }

        document.querySelectorAll('.defense-type-button').forEach(button => {
            button.addEventListener('click', function() {
                currentDefenseType = this.getAttribute('data-defense-type');
                localStorage.setItem('selectedDefenseType', currentDefenseType);
                localStorage.setItem('currentView', 'filter');
                showFilterPage();
                document.getElementById('defense-type-display').textContent = currentDefenseType + ' SCHEDULING';
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
        
        // Update cluster/section options based on department
        deptSelect.addEventListener('change', function() {
            const clusterSelect = document.getElementById('cluster-select');
            const clusterLabel = document.getElementById('cluster-label');
            const clusterHeader = document.getElementById('cluster-header');
            
            clusterSelect.innerHTML = '';
            
            if (this.value === 'BSIT') {
                clusterLabel.textContent = 'Cluster';
                if (clusterHeader) clusterHeader.textContent = 'Cluster';
                clusterSelect.innerHTML = `
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
                `;
            } else {
                clusterLabel.textContent = 'Section';
                if (clusterHeader) clusterHeader.textContent = 'Section';
                clusterSelect.innerHTML = `
                    <option selected disabled value="">Select Section</option>
                    <option value="Section 1">Section 1</option>
                    <option value="Section 2">Section 2</option>
                    <option value="Section 3">Section 3</option>
                    <option value="Section 4">Section 4</option>
                    <option value="Section 5">Section 5</option>
                `;
            }
        });
        
        document.getElementById('backToFilterButton').addEventListener('click', function() {
            localStorage.removeItem('selectedDefenseType');
            localStorage.removeItem('currentView');
            localStorage.removeItem('selectedDept');
            localStorage.removeItem('selectedCluster');
            localStorage.removeItem('selectedRedefenseType');
            showTypeSelection();
        });
        
        document.getElementById('backToFilterFromSchedule').addEventListener('click', function() {
            showFilterPage();
        });

        enterButton.addEventListener('click', () => {
            const dept = deptSelect.value;
            const cluster = clusterSelect.value;
            const redefenseType = document.getElementById('redefense-type-select').value;
            
            localStorage.setItem('currentView', 'schedule');
            localStorage.setItem('selectedDept', dept);
            localStorage.setItem('selectedCluster', cluster);
            if (currentDefenseType === 'REDEFENSE') {
                localStorage.setItem('selectedRedefenseType', redefenseType);
            }
            
            showSchedulePage();
            updateScheduleView();
            loadScheduleData();
            if (currentDefenseType === 'FINAL DEFENSE') {
                const dept = deptSelect.value;
                const cluster = clusterSelect.value;
                if (dept && cluster) {
                    loadEvaluationStatuses(dept, cluster);
                }
            }
        });
        document.getElementById('redefense-type-select').addEventListener('change', () => {
            updateScheduleView();
            loadScheduleData();
        });
        
        // Load schedules when department or cluster changes
        deptSelect.addEventListener('change', () => {
            if (document.getElementById('schedule-view').style.display === 'block') {
                setTimeout(() => loadScheduleData(), 100);
            }
        });
        
        clusterSelect.addEventListener('change', () => {
            if (document.getElementById('schedule-view').style.display === 'block') {
                setTimeout(() => loadScheduleData(), 100);
            }
        });
        
        document.getElementById('calendar-icon').addEventListener('click', () => {
             dateActualInput.focus();
        });
         dateDisplayInput.addEventListener('click', () => {
             dateActualInput.focus();
        });

        dateActualInput.addEventListener('change', function() {
            if (this.value) {
                const [year, month, day] = this.value.split('-');
                dateDisplayInput.value = `${month}/${day}/${year}`;
            } else {
                dateDisplayInput.value = '';
            }
        });
        
        setInterval(enableStatusChecks, 60000); 

        scheduleModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const isEditing = button.classList.contains('scheduled-container');
            
            const cluster = clusterSelect.value;
            const set = button.getAttribute('data-set'); 
            const group = button.getAttribute('data-group');
            const targetId = set + group;
            
            // Validate scheduling attempt and prevent modal from opening if not eligible
            if (!isEditing && currentDefenseType === 'FINAL DEFENSE') {
                event.preventDefault();
                validateScheduleAttempt(targetId, currentDefenseType).then(isValid => {
                    if (!isValid) {
                        return false;
                    }
                    // If valid, manually show the modal
                    const modalInstance = new bootstrap.Modal(scheduleModal);
                    modalInstance.show();
                });
                return;
            }
            
            const panelCell = document.querySelector(`.panel-details-table[data-panel-set="${set}"]`);

            const adviser = button.getAttribute('data-adviser') || 'No Adviser';
            const chair = button.getAttribute('data-chair') || 'No Chairperson';
            const members = button.getAttribute('data-members') || 'No Members';

            document.getElementById('modal-title-display').textContent = `Schedule Defense: C${cluster} Set ${set} (Group ${group})`;
            document.getElementById('modal-adviser').textContent = adviser;
            document.getElementById('modal-chairperson').textContent = chair;
            document.getElementById('modal-members').textContent = members;

            scheduleButton.setAttribute('data-group-target', targetId); 
            scheduleButton.textContent = isEditing ? 'Update Schedule' : 'Set Schedule';

            document.getElementById('start-time-input').value = isEditing ? button.getAttribute('data-sch-start') : ''; 
            document.getElementById('end-time-input').value = isEditing ? button.getAttribute('data-sch-end') : ''; 
            
            const schDate = isEditing ? button.getAttribute('data-sch-date') : '';
            dateActualInput.value = schDate;
            if (schDate) {
                const [year, month, day] = schDate.split('-');
                dateDisplayInput.value = `${month}/${day}/${year}`;
            } else {
                dateDisplayInput.value = '';
            }
            
            document.getElementById('set-input').value = set;
        });

        panelEditModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const panelSet = button.getAttribute('data-panel-set');
            
            const panelCell = document.querySelector(`.panel-details-table[data-panel-set="${panelSet}"]`);

            const adviser = panelCell.getAttribute('data-adviser');
            const chair = panelCell.getAttribute('data-chair');
            const members = panelCell.getAttribute('data-members');

            document.getElementById('panel-set-display').textContent = `Set ${panelSet}`;
            savePanelDetailsBtn.setAttribute('data-panel-set', panelSet); 
            
            document.getElementById('edit-adviser-input').value = adviser; 
            document.getElementById('edit-chair-input').value = chair;
            document.getElementById('edit-members-input').value = members;
        });

        savePanelDetailsBtn.addEventListener('click', function() {
            const panelSet = this.getAttribute('data-panel-set');
            updatePanelDetails(panelSet);
        });
        
        scheduleButton.addEventListener('click', function() {
            const targetId = this.getAttribute('data-group-target');
            const startTime = document.getElementById('start-time-input').value;
            const endTime = document.getElementById('end-time-input').value;
            const dateActual = document.getElementById('date-actual-input').value;
            
            if (!startTime || !endTime || !dateActual) {
                showAlert('warning', 'Missing Information', 'Please select a Start Time, End Time, and Date before scheduling.');
                return;
            }
            
            // Get panel names for availability check
            const adviser = document.getElementById('modal-adviser').textContent;
            const chair = document.getElementById('modal-chairperson').textContent;
            const members = document.getElementById('modal-members').textContent;
            const panelNames = [adviser, chair].concat(members.split(',').map(m => m.trim())).filter(name => name && name !== 'No Adviser' && name !== 'No Chairperson' && name !== 'No Members');
            
            // Check panel availability before scheduling
            checkPanelAvailability(panelNames, dateActual, startTime, endTime)
                .then(availabilityResult => {
                    if (!availabilityResult.available && availabilityResult.conflicts && availabilityResult.conflicts.length > 0) {
                        const conflictDetails = availabilityResult.conflicts.map(conflict => 
                            `${conflict.panel_name}: ${conflict.conflict_type} (${conflict.conflict_time})`
                        ).join('\n');
                        
                        showAlert('error', 'Scheduling Conflict Detected', 
                            `The following panel members have conflicts on the selected date and time:\n\n${conflictDetails}\n\nPlease choose a different date or time.`);
                        return;
                    }
                    
                    // Mark as scheduled in group status
                    initializeGroupStatus(targetId);
                    if (currentDefenseType === 'PRE-ORAL') {
                        groupStatuses[targetId].preOralScheduled = true;
                    } else if (currentDefenseType === 'FINAL DEFENSE') {
                        groupStatuses[targetId].finalDefenseScheduled = true;
                    } else if (currentDefenseType === 'REDEFENSE') {
                        const redefenseType = document.getElementById('redefense-type-select').value;
                        if (redefenseType === 'PRE-ORAL') {
                            groupStatuses[targetId].preOralRedefenseScheduled = true;
                        } else if (redefenseType === 'FINAL DEFENSE') {
                            groupStatuses[targetId].finalRedefenseScheduled = true;
                        }
                    }
                    
                    // Check if this is Group 1 and auto-schedule other groups
                    const groupNumber = parseInt(targetId.substring(1));
                    const setLetter = targetId.charAt(0);
                    
                    if (groupNumber === 1) {
                        autoScheduleGroups(setLetter, dateActual, startTime, endTime, adviser, chair, members);
                    } else {
                        saveScheduleToDatabase(targetId);
                    }
                })
                .catch(error => {
                    console.error('Error checking availability:', error);
                    showAlert('error', 'Availability Check Failed', 'Unable to verify panel availability. Please try again.');
                });
        });

        document.getElementById('backToScheduleBtn').addEventListener('click', function() {
            const infoModalElement = document.getElementById('infoModal');
            const scheduleModalElement = document.getElementById('scheduleModal');

            const infoModalInstance = bootstrap.Modal.getInstance(infoModalElement);
            if (infoModalInstance) { 
                infoModalInstance.hide(); 
            }
            
            setTimeout(() => { 
                const scheduleModalInstance = bootstrap.Modal.getOrCreateInstance(scheduleModalElement);
                scheduleModalInstance.show(); 
            }, 100); 
        });
        

        
        // Initialize page visibility based on saved state
        const initialView = localStorage.getItem('currentView');
        if (!initialView || (!urlParams.get('department') && !localStorage.getItem('selectedDefenseType'))) {
            typeSelectionView.classList.remove('hidden');
            filterPage.classList.add('hidden');
            scheduleView.style.display = 'none';
        }
        
        enableStatusChecks();
        
        // Group info modal - click handler
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('group-number')) {
                const groupId = e.target.getAttribute('data-group-id');
                const displayNumber = e.target.textContent;
                const row = e.target.closest('tr');
                const dept = deptSelect.value;
                const cluster = clusterSelect.value || row.querySelector('.cluster-value')?.textContent || '-';
                const status = row.querySelector('.status-badge')?.textContent || 'Pending';
                
                // Get panel data from the schedule button in the same row
                const scheduleButton = row.querySelector('.btn-set-schedule, .scheduled-container');
                
                let adviser = 'Dr. Sample Adviser';
                let chairName = 'Dr. Sample Chair';
                let memberNames = 'Prof. Member 1, Prof. Member 2';
                
                if (scheduleButton) {
                    adviser = scheduleButton.getAttribute('data-adviser') || 'Dr. Sample Adviser';
                    chairName = scheduleButton.getAttribute('data-chair') || 'Dr. Sample Chair';
                    memberNames = scheduleButton.getAttribute('data-members') || 'Prof. Member 1, Prof. Member 2';
                }
                
                // Update modal content
                document.getElementById('groupInfoTitle').textContent = `Group ${displayNumber}`;
                document.getElementById('groupDept').textContent = dept || '-';
                document.getElementById('groupCluster').textContent = cluster;
                document.getElementById('groupStatus').textContent = status;
                document.getElementById('groupAdviser').textContent = adviser;
                document.getElementById('groupChair').textContent = chairName;
                document.getElementById('groupPanelMembers').textContent = memberNames;
                
                // Fetch student data from groups table
                fetchGroupStudents(displayNumber, dept)
                    .then(students => {
                        let membersHtml = '<ul class="list-unstyled mb-0">';
                        students.forEach((student) => {
                            const leaderBadge = student.is_leader ? ' <span class="badge bg-primary">Leader</span>' : '';
                            membersHtml += `<li>${student.name} (${student.student_id})${leaderBadge}</li>`;
                        });
                        membersHtml += '</ul>';
                        
                        document.getElementById('groupMembers').innerHTML = membersHtml || '<p class="text-muted">No members found</p>';
                    })
                    .catch(error => {
                        console.error('Error fetching group students:', error);
                        document.getElementById('groupMembers').innerHTML = '<p class="text-muted">Error loading student data</p>';
                    });
                
                new bootstrap.Modal(document.getElementById('groupInfoModal')).show();
            }
        });

        // Calendar view button
        document.getElementById('calendar-view-btn').addEventListener('click', function() {
            typeSelectionView.classList.add('hidden');
            scheduleView.style.display = 'none';
            filterPage.classList.add('hidden');
            document.getElementById('calendar-view').style.display = 'block';
            loadCalendarData();
        });

        // Back to selection button
        document.getElementById('back-to-selection').addEventListener('click', function() {
            document.getElementById('calendar-view').style.display = 'none';
            typeSelectionView.classList.remove('hidden');
            scheduleView.style.display = 'none';
            filterPage.classList.add('hidden');
        });

        // Calendar navigation
        document.getElementById('prev-month').addEventListener('click', function() {
            currentCalendarDate.setMonth(currentCalendarDate.getMonth() - 1);
            renderCalendar();
        });

        document.getElementById('next-month').addEventListener('click', function() {
            currentCalendarDate.setMonth(currentCalendarDate.getMonth() + 1);
            renderCalendar();
        });

    });
</script>
@endsection
