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
        grid-template-columns: repeat(3, 1fr); 
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
<script>
// Define functions immediately so onclick handlers work
window.currentDefenseType = '';
window.groupStatuses = {};
window.scheduleData = {};

window.selectDefenseType = function(type) {
    console.log('selectDefenseType called with:', type);
    currentDefenseType = type;
    localStorage.setItem('selectedDefenseType', type);
    localStorage.setItem('currentView', 'filter');
    document.getElementById('type-selection-view').classList.add('hidden');
    document.getElementById('filter-page').classList.remove('hidden');
    document.getElementById('schedule-view').style.display = 'none';
    document.getElementById('defense-type-display').textContent = type + ' SCHEDULING';
    const redefenseTypeGroup = document.getElementById('redefense-type-group');
    if (type === 'REDEFENSE') {
        redefenseTypeGroup.style.display = 'block';
    } else {
        redefenseTypeGroup.style.display = 'none';
    }
    // Populate clusters if BSIT is selected
    setTimeout(() => {
        if (window.populateClusters) window.populateClusters();
    }, 100);
};

window.showCalendar = function() {
    console.log('showCalendar called');
    localStorage.setItem('currentView', 'calendar');
    localStorage.setItem('selectedDefenseType', 'CALENDAR');
    document.getElementById('type-selection-view').classList.add('hidden');
    document.getElementById('schedule-view').style.display = 'none';
    document.getElementById('filter-page').classList.add('hidden');
    document.getElementById('calendar-view').style.display = 'block';
    if (window.loadCalendarData) {
        window.loadCalendarData();
    } else {
        // Fallback: call after a short delay to ensure DOMContentLoaded has fired
        setTimeout(() => {
            if (window.loadCalendarData) window.loadCalendarData();
        }, 100);
    }
};

window.showTypeSelection = function() {
    document.getElementById('type-selection-view').classList.remove('hidden');
    document.getElementById('filter-page').classList.add('hidden');
    document.getElementById('schedule-view').style.display = 'none';
    document.getElementById('calendar-view').style.display = 'none';
};

window.showFilterPage = function() {
    document.getElementById('type-selection-view').classList.add('hidden');
    document.getElementById('filter-page').classList.remove('hidden');
    document.getElementById('schedule-view').style.display = 'none';
    document.getElementById('calendar-view').style.display = 'none';
};

window.showSchedulePage = function() {
    document.getElementById('type-selection-view').classList.add('hidden');
    document.getElementById('filter-page').classList.add('hidden');
    document.getElementById('schedule-view').style.display = 'block';
    document.getElementById('calendar-view').style.display = 'none';
};

window.populateClusters = function() {
    const deptSelect = document.getElementById('dept-select');
    const clusterSelect = document.getElementById('cluster-select');
    const clusterLabel = document.getElementById('cluster-label');
    const clusterHeader = document.getElementById('cluster-header');
    
    if (!deptSelect.value) return;
    
    clusterSelect.innerHTML = '<option selected disabled value="">Loading...</option>';
    
    if (deptSelect.value === 'BSIT') {
        clusterLabel.textContent = 'Cluster';
        if (clusterHeader) clusterHeader.textContent = 'Cluster';
        
        fetch('/api/clusters/bsit')
            .then(r => r.json())
            .then(data => {
                const clusters = (data.clusters || []).sort((a, b) => parseInt(a) - parseInt(b));
                clusterSelect.innerHTML = '<option selected disabled value="">Select Cluster</option>';
                clusters.forEach(c => {
                    clusterSelect.innerHTML += `<option value="${c}">${c}</option>`;
                });
            })
            .catch(() => {
                clusterSelect.innerHTML = '<option selected disabled value="">Select Cluster</option>';
            });
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
};

window.handleEnterClick = function() {
    const dept = document.getElementById('dept-select').value;
    const cluster = document.getElementById('cluster-select').value;
    const redefenseType = document.getElementById('redefense-type-select').value;
    if (!dept || !cluster) {
        alert('Please select both Department and Cluster.');
        return;
    }
    if (currentDefenseType === 'REDEFENSE' && !redefenseType) {
        alert('Please select Defense Type for Re-defense.');
        return;
    }
    localStorage.setItem('currentView', 'schedule');
    localStorage.setItem('selectedDept', dept);
    localStorage.setItem('selectedCluster', cluster);
    if (currentDefenseType === 'REDEFENSE') localStorage.setItem('selectedRedefenseType', redefenseType);
    
    window.showSchedulePage();
    
    // Call updateScheduleView directly here
    const defenseType = currentDefenseType;
    const displayType = defenseType === 'REDEFENSE' ? `${defenseType} (${redefenseType})` : defenseType;
    document.getElementById('dept-header').textContent = `${displayType}: DEPT. OF ${dept} (Cluster ${cluster})`;
    document.getElementById('schedule-content').style.display = 'block';
    document.getElementById('empty-state').style.display = 'none';
    
    // Fetch assignment data and generate table
    fetch(`/api/assignments?department=${dept}&section=${cluster}`)
        .then(response => response.json())
        .then(assignments => {
            const assignment = assignments.find(a => a.department === dept && a.section === cluster);
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
            
            if (window.generateTableRows) {
                window.generateTableRows(adviser, chairName, memberNames);
            }
        })
        .catch(error => {
            console.error('Error fetching assignments:', error);
        });
};

window.handleBackToFilter = function() {
    localStorage.clear();
    currentDefenseType = '';
    window.showTypeSelection();
};

window.handleBackFromSchedule = function() {
    // Preserve filter values from localStorage
    const dept = localStorage.getItem('selectedDept');
    const cluster = localStorage.getItem('selectedCluster');
    const defenseType = localStorage.getItem('selectedDefenseType');
    const redefenseType = localStorage.getItem('selectedRedefenseType');
    
    // Update view state
    localStorage.setItem('currentView', 'filter');
    
    // Show filter page
    window.showFilterPage();
    
    // Restore department selection
    if (dept) {
        document.getElementById('dept-select').value = dept;
    }
    
    // Repopulate and restore cluster
    if (dept && cluster) {
        if (window.populateClusters) window.populateClusters();
        setTimeout(() => {
            document.getElementById('cluster-select').value = cluster;
        }, 150);
    }
    
    // Restore defense type display and redefense options
    if (defenseType) {
        currentDefenseType = defenseType;
        document.getElementById('defense-type-display').textContent = defenseType + ' SCHEDULING';
        
        const redefenseTypeGroup = document.getElementById('redefense-type-group');
        if (defenseType === 'REDEFENSE') {
            redefenseTypeGroup.style.display = 'block';
            if (redefenseType) {
                setTimeout(() => {
                    document.getElementById('redefense-type-select').value = redefenseType;
                }, 50);
            }
        } else {
            redefenseTypeGroup.style.display = 'none';
        }
    }
};
</script>
<div class="container-fluid" id="main-container-bs">
    
    <div id="type-selection-view" class="text-center p-5 bg-white rounded-4 d-flex flex-column align-items-center justify-content-center mb-4">
        <h1 class="app-title fw-bold text-dark-blue mb-4">DEFENSE SCHEDULING</h1>
        <h4 class="text-muted">Select the type of defense to schedule:</h4>
        <div class="type-button-container d-flex justify-content-center flex-wrap mt-5">
            <button class="defense-type-button" onclick="selectDefenseType('PRE-ORAL')">Pre-oral Defense</button>
            <button class="defense-type-button" onclick="selectDefenseType('FINAL DEFENSE')">Final Defense</button>
            <button class="defense-type-button" onclick="selectDefenseType('REDEFENSE')">Re-defense</button>
            <button class="defense-type-button" onclick="showCalendar()" style="background-color: #28a745;">&#128197; Defense Calendar</button>
        </div>
    </div>
    
    <div id="filter-page" class="bg-white p-4 rounded-3 mb-4 hidden">
        <div class="d-flex align-items-center mb-4">
            <button class="btn btn-outline-secondary me-3" id="backToFilterButton" onclick="if(window.handleBackToFilter) window.handleBackToFilter()">
                <i class="bi bi-arrow-left"></i> Back
            </button>
            <h1 class="selected-type-display mb-0" id="defense-type-display"></h1>
        </div>
        
        <div class="d-grid gap-3">
            <div class="filter-group">
                <label for="dept-select" class="form-label small fw-bold">Department</label>
                <select id="dept-select" class="form-select" onchange="if(window.populateClusters) window.populateClusters()">
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
                    <option selected disabled value="">Select Department First</option>
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

            <button class="btn btn-custom-merged py-2" id="enterButton" onclick="if(window.handleEnterClick) window.handleEnterClick()">
                <i class="bi bi-arrow-right-circle"></i> View Schedule
            </button>
        </div>
    </div>

    <div id="schedule-view" style="display: none;">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <button class="btn btn-outline-secondary" id="backToFilterFromSchedule" onclick="if(window.handleBackFromSchedule) window.handleBackFromSchedule()">
                <i class="bi bi-arrow-left"></i> Back
            </button>
            <button class="btn btn-danger" id="resetScheduleBtn" onclick="resetSchedules()">
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
                    <tbody id="schedule-table-body">
                        <!-- Groups will be loaded dynamically via JavaScript -->
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
        <button class="btn btn-secondary" id="back-to-selection" onclick="goBackToSelection()">
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
                <i class="bi bi-info-circle-fill text-white position-absolute top-0 end-0 mt-3 me-5" id="showAvailabilityBtn" style="font-size: 1.5rem; cursor: pointer; z-index: 1051;" title="Show Panel Availability" onclick="showAvailability()"></i>
            </div>
            <div class="modal-body-content-fixed">
                <div class="panel-details-box-fixed" style="display: none;">
                    <div>
                        <strong>Adviser:</strong> <span id="modal-adviser"></span><br>
                        <strong>Chairperson:</strong> <span id="modal-chairperson"></span><br>
                        <strong>Members:</strong> <span id="modal-members"></span>
                    </div>
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
                        <label for="date-display-input" class="form-label small fw-bold">Set Date</label>
                        <div class="input-group">
                            <input type="text" class="form-control text-center" id="date-display-input" placeholder="mm/dd/yyyy" readonly>
                            <span class="input-group-text" id="calendar-icon" style="cursor: pointer;"><i class="bi bi-calendar"></i></span>
                            <input type="date" class="form-control" id="date-actual-input">
                        </div>
                    </div>
                </form>

            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-warning" id="edit-schedule-button" style="display:none;">
                    <i class="bi bi-pencil"></i> Edit
                </button>
                <button type="button" class="btn btn-schedule-fixed text-white flex-fill" id="schedule-button" data-group-target="">Set Schedule</button>
            </div>
            <div class="mt-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="auto-schedule-checkbox">
                    <label class="form-check-label" for="auto-schedule-checkbox">
                        Auto-schedule remaining groups (up to group <input type="number" id="auto-schedule-limit" min="1" max="20" value="10" style="width: 60px; display: inline-block;" class="form-control form-control-sm d-inline">)
                    </label>
                </div>
            </div>
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
                
                <div class="mb-4">
                    <h6 class="fw-bold mb-2">Adviser:</h6>
                    <div class="p-3 bg-light rounded">
                        <p class="text-muted mb-0" id="adviser-name">Loading...</p>
                    </div>
                </div>
                
                <div>
                    <h6 class="fw-bold mb-2">Panel Members Availability:</h6>
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
                </div>
                <p class="mt-3 text-muted small">
                    <i class="bi bi-square-fill" style="color: var(--status-light-green);"></i> Green cells indicate full availability. 
                    <i class="bi bi-square-fill" style="color: var(--status-light-red);"></i> Red cells show scheduled conflicts (hover for details).
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning" id="editPanelBtn">
                    <i class="bi bi-pencil"></i> Edit Panel
                </button>
                <button type="button" class="btn btn-success" id="generateScheduleBtn">
                    <i class="bi bi-magic"></i> Generate Schedule
                </button>
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

{{-- Edit Panel Assignment Modal --}}
<div class="modal fade" id="editPanelAssignmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3">
            <div class="modal-header modal-header-custom rounded-top-3">
                <h5 class="modal-title">Edit Panel Assignment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small">Replace assigned panel members if they have an emergency or conflict.</p>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Current Chairperson</label>
                    <input type="text" class="form-control" id="currentChair" readonly>
                </div>
                <div class="mb-3">
                    <label for="newChairSelect" class="form-label small fw-bold">Replace with</label>
                    <select class="form-select" id="newChairSelect">
                        <option value="">Keep current</option>
                    </select>
                </div>
                <hr>
                <div id="memberReplacements"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="savePanelReplacementBtn">
                    <i class="bi bi-check-circle"></i> Save Changes
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    console.log('Script loaded');
    
    // CRITICAL: Remove ALL overlays immediately when script loads
    document.querySelectorAll('.modal-backdrop, .sidebar-overlay').forEach(el => el.remove());
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('overflow');
    document.body.style.removeProperty('padding-right');
    
    // Global variables already declared in inline script above
    // Global function for show availability button
    window.showAvailability = function() {
        console.log('showAvailability called');
        const cluster = document.getElementById('cluster-select').value;
        const dept = document.getElementById('dept-select').value;
        const modalTitle = document.getElementById('modal-title-display').textContent;
        const groupMatch = modalTitle.match(/Group (\d+)/);
        const groupNumber = groupMatch ? groupMatch[1] : '1';
        const groupTarget = 'A' + groupNumber;
        
        fetch(`/api/assignments?department=${dept}&section=${cluster}`)
            .then(r => r.json())
            .then(assignments => {
                const assignment = assignments.find(a => a.department === dept && a.section === cluster);
                let panelNames = [];
                let adviserName = 'No Adviser';
                if (assignment) {
                    adviserName = assignment.adviser || 'No Adviser';
                    const panels = assignment.panels || [];
                    const chairperson = panels.find(p => p.role === 'Chairperson');
                    const members = panels.filter(p => p.role === 'Member');
                    if (chairperson && chairperson.name) panelNames.push(chairperson.name);
                    members.forEach(m => { if (m.name) panelNames.push(m.name); });
                }
                setupAvailabilityModal(cluster, groupTarget, panelNames, dept, adviserName);
                bootstrap.Modal.getInstance(document.getElementById('scheduleModal')).hide();
                setTimeout(() => {
                    new bootstrap.Modal(document.getElementById('infoModal')).show();
                }, 300);
            })
            .catch(error => {
                console.error('Error fetching assignment panels:', error);
                showAlert('error', 'Error', 'Failed to load panel information.');
            });
    }
    
    // Global function for back button
    window.goBackToSelection = function() {
        console.log('goBackToSelection called');
        console.log('Back to selection clicked via onclick');
        localStorage.clear();
        document.getElementById('calendar-view').style.display = 'none';
        document.getElementById('type-selection-view').classList.remove('hidden');
        document.getElementById('schedule-view').style.display = 'none';
        document.getElementById('filter-page').classList.add('hidden');
    }
    
    
    // Global function for reset schedules
    window.resetSchedules = function() {
        console.log('resetSchedules called');
        const dept = document.getElementById('dept-select').value;
        const cluster = document.getElementById('cluster-select').value;
        
        if (!dept || !cluster) {
            Swal.fire('Error', 'Please select department and cluster first', 'error');
            return;
        }
        
        Swal.fire({
            title: 'Reset All Schedules?',
            text: `This will delete all defense schedules for ${dept} - ${cluster}. This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, reset all!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/defense-schedules/reset', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        department: dept,
                        section: cluster,
                        defense_type: currentDefenseType
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Deleted!', 'All schedules have been reset.', 'success');
                        if (window.loadScheduleData) {
                            window.loadScheduleData();
                        }
                    } else {
                        Swal.fire('Error', data.message || 'Failed to reset schedules', 'error');
                    }
                })
                .catch(error => {
                    console.error('Reset error:', error);
                    Swal.fire('Error', 'Failed to reset schedules', 'error');
                });
            }
        });
    }
    
    function getGroupOffset(cluster) {
        const clusterNum = parseInt(cluster);
        return (clusterNum - 4101) * 10;
    }

    window.generateTableRows = function(adviser, chairName, memberNames) {
        const tbody = document.getElementById('schedule-table-body');
        const cluster = document.getElementById('cluster-select').value;
        const dept = document.getElementById('dept-select').value;
        
        console.log('🔍 generateTableRows called with:', {dept, cluster, adviser, chairName, memberNames});
        
        if (!dept || !cluster) {
            console.error('❌ Missing dept or cluster');
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Please select department and cluster</td></tr>';
            return;
        }
        
        tbody.innerHTML = '<tr><td colspan="5" class="text-center">Loading groups...</td></tr>';
        
        // For REDEFENSE, fetch groups with Re-defense status from original defense type
        if (currentDefenseType === 'REDEFENSE') {
            const redefenseType = document.getElementById('redefense-type-select').value;
            
            console.log('🔍 Fetching groups with Re-defense status for:', {redefenseType, dept, cluster});
            
            // Fetch schedules from original defense type with Re-defense status
            fetch(`/defense-schedules/by-type?defense_type=${redefenseType}&department=${dept}&section=${cluster}`)
                .then(response => response.json())
                .then(schedules => {
                    console.log('📦 All schedules:', schedules);
                    const redefenseGroups = schedules
                        .filter(s => s.status === 'Re-defense')
                        .map(s => s.group_id);
                    console.log('✅ Groups marked for redefense:', redefenseGroups);
                    
                    if (redefenseGroups.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="5" class="text-center">No groups marked for re-defense</td></tr>';
                        return;
                    }
                    
                    fetch(`/api/groups?department=${dept}&cluster=${cluster}`)
                        .then(r => r.json())
                        .then(data => {
                            const allGroups = data.groups || [];
                            const groups = allGroups.filter(g => 
                                redefenseGroups.includes(g.group_id || g.id)
                            );
                            
                            renderGroupRows(groups, adviser, chairName, memberNames, tbody, cluster, dept);
                        })
                        .catch(error => {
                            console.error('Error fetching groups for redefense:', error);
                            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error loading groups</td></tr>';
                        });
                })
                .catch(error => {
                    console.error('Error fetching redefense schedules:', error);
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error loading redefense data</td></tr>';
                });
            return;
        }
        
        // For PRE-ORAL and FINAL DEFENSE, show all groups
        const apiUrl = `/api/groups?department=${dept}&cluster=${cluster}`;
        console.log('📡 Fetching from:', apiUrl);
        
        fetch(apiUrl)
            .then(response => {
                console.log('📥 Response status:', response.status, response.statusText);
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('📦 Raw API Response:', JSON.stringify(data, null, 2));
                const groups = data.groups || data || [];
                console.log('📊 Extracted groups array:', groups);
                console.log('📏 Groups length:', groups.length);
                
                if (!Array.isArray(groups)) {
                    console.error('❌ Groups is not an array:', typeof groups);
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Invalid data format from API</td></tr>';
                    return;
                }
                
                if (groups.length === 0) {
                    console.warn('⚠️ No groups returned from API');
                    tbody.innerHTML = `<tr><td colspan="5" class="text-center">No groups found for ${dept} - ${cluster}. Please check if groups exist in the database.</td></tr>`;
                    return;
                }
                
                console.log('✅ Calling renderGroupRows with', groups.length, 'groups');
                renderGroupRows(groups, adviser, chairName, memberNames, tbody, cluster, dept);
            })
            .catch(error => {
                console.error('❌ Error fetching groups:', error);
                tbody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Error: ${error.message}</td></tr>`;
            });
    }
    
    function renderGroupRows(groups, adviser, chairName, memberNames, tbody, cluster, dept) {
        tbody.innerHTML = '';
        
        if (groups.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center">No groups found</td></tr>';
            return;
        }
        
        const groupOffset = getGroupOffset(cluster);
        let rowsHTML = '';
        
        // Build all rows first
        groups.forEach((group, index) => {
                    const groupId = group.group_id || group.id;
                    const displayNumber = index + 1 + groupOffset;
                    const setLetter = index < Math.ceil(groups.length / 2) ? 'A' : 'B';
                    
                    rowsHTML += `
                        <tr id="group-${displayNumber}" data-group-id="${groupId}" data-set="${setLetter}">
                            ${index === 0 ? `<td class="merged-cell cluster-value" rowspan="${groups.length}">${cluster}</td>` : ''}
                            <td class="group-number" onclick="showGroupInfo('${displayNumber}', '${groupId}')">${displayNumber}</td>
                            <td><span class="status-badge status-incomplete">Loading...</span></td>
                            <td class="status-column" id="status-${groupId}"><span class="status-badge status-pending">Pending</span></td>
                            <td class="schedule-cell" data-schedule-target="${groupId}">
                                <button class="btn btn-set-schedule" data-bs-toggle="modal" data-bs-target="#scheduleModal" 
                                        data-group="${index + 1}" data-set="${setLetter}" data-real-group-id="${groupId}"
                                        data-adviser="${adviser}" data-chair="${chairName}" data-members="${memberNames}">
                                    <i class="bi bi-calendar-plus"></i> Set Schedule
                                </button>
                            </td>
                        </tr>
                    `;
                });
                
                // Insert all rows at once
                tbody.innerHTML = rowsHTML;
                
                // Scroll to group if hash is present
                if (window.location.hash) {
                    setTimeout(() => {
                        const targetElement = document.querySelector(window.location.hash);
                        if (targetElement) {
                            targetElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            targetElement.style.backgroundColor = '#fff3cd';
                            setTimeout(() => { targetElement.style.backgroundColor = ''; }, 2000);
                        }
                    }, 500);
                }
                
                // Now fetch document status for each group
                groups.forEach((group, index) => {
                    const groupId = group.group_id || group.id;
                    fetch(`/api/group-document-status?group_id=${groupId}&defense_type=${currentDefenseType}`)
                        .then(r => r.json())
                        .then(statusData => {
                            const docStatus = statusData.status || 'Incomplete';
                            const docStatusClass = docStatus === 'Complete' ? 'status-completed' : 
                                                   docStatus === 'Pending' ? 'status-pending' : 'status-incomplete';
                            
                            const row = document.querySelector(`tr[data-group-id="${groupId}"]`);
                            if (row) {
                                // For first row: cluster(1), group(2), docs(3), status(4), schedule(5)
                                // For other rows: group(1), docs(2), status(3), schedule(4)
                                const docCell = index === 0 ? row.querySelector('td:nth-child(3)') : row.querySelector('td:nth-child(2)');
                                if (docCell) {
                                    docCell.innerHTML = `<span class="status-badge ${docStatusClass}">${docStatus}</span>`;
                                }
                            }
                        })
                        .catch(() => {});
                });
                
                // Don't load old schedule data for REDEFENSE - they need new schedules
                if (currentDefenseType !== 'REDEFENSE') {
                    setTimeout(() => loadScheduleData(), 100);
                }
                
                // For FINAL DEFENSE, disable buttons for groups that haven't passed PRE-ORAL
                if (currentDefenseType === 'FINAL DEFENSE') {
                    setTimeout(() => disableFinalDefenseForNonPassed(), 200);
                }
    }

    async function fetchGroupStudents(groupNumber, department) {
        try {
            const response = await fetch(`/api/groups/by-number/${groupNumber}?department=${department}`);
            if (!response.ok) return [];
            const data = await response.json();
            if (data.success && data.group) {
                const group = data.group;
                const students = [];
                for (let i = 1; i <= 5; i++) {
                    const memberName = group[`member${i}_name`];
                    const memberStudentId = group[`member${i}_student_id`];
                    if (memberName && memberStudentId) {
                        students.push({
                            name: memberName,
                            student_id: memberStudentId,
                            is_leader: group.leader_member == i
                        });
                    }
                }
                return students;
            }
            return [];
        } catch (error) {
            return [];
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
        
        // Calculate duration in minutes
        const start = new Date(`2000-01-01T${baseStartTime}`);
        const end = new Date(`2000-01-01T${baseEndTime}`);
        const durationMinutes = (end - start) / 60000;
        
        // Fetch all groups to schedule
        fetch(`/api/groups?department=${dept}&cluster=${cluster}`)
            .then(response => response.json())
            .then(data => {
                const groups = data.groups || [];
                const schedules = [];
                let currentStart = baseStartTime;
                
                // Generate schedules for all groups
                groups.forEach((group, index) => {
                    const groupId = group.group_id || group.id;
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
                        panel_data: {
                            adviser: adviser,
                            chairperson: chair,
                            members: members
                        }
                    });
                    
                    // Next group starts when current group ends
                    currentStart = currentEnd;
                });
                
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
                    .catch(error => {
                        console.error('Error saving schedule:', error);
                        return { success: false, error: error.message };
                    })
                ))
                .then(results => {
                    const failures = results.filter(r => !r.success);
                    if (failures.length > 0) {
                        showAlert('error', 'Partial Failure', `${failures.length} out of ${schedules.length} schedules failed to save.`);
                    } else {
                        schedules.forEach(schedule => {
                            updateScheduleUI(schedule);
                        });
                        
                        showAlert('success', 'All Groups Scheduled!', `All ${schedules.length} groups have been automatically scheduled with ${durationMinutes} minute intervals.`);
                    }
                    
                    const scheduleModal = document.getElementById('scheduleModal');
                    const modalInstance = bootstrap.Modal.getInstance(scheduleModal);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                    
                    localStorage.setItem('currentView', 'schedule');
                    localStorage.setItem('selectedDept', dept);
                    localStorage.setItem('selectedCluster', cluster);
                    if (currentDefenseType === 'REDEFENSE') {
                        localStorage.setItem('selectedRedefenseType', document.getElementById('redefense-type-select').value);
                    }
                })
                .catch(error => {
                    console.error('Error auto-scheduling groups:', error);
                    showAlert('error', 'Scheduling Failed', `Failed to auto-schedule groups: ${error.message}`);
                });
            })
            .catch(error => {
                console.error('Error fetching groups:', error);
                showAlert('error', 'Scheduling Failed', 'Failed to fetch groups for auto-scheduling.');
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
        
        // Parse date without timezone conversion
        const [year, month, day] = schedule.defense_date.split('-');
        const dateDisplay = `${month}/${day}/${year}`;
        
        const adviser = schedule.panel_data.adviser || 'No Adviser';
        const chair = schedule.panel_data.chairperson || 'No Chairperson';
        const members = schedule.panel_data.members || 'No Members';
        const groupNumber = schedule.group_id.substring(1);
        const cluster = schedule.section;
        
        const scheduledTagHtml = `
            <div class="scheduled-container" 
                data-bs-toggle="modal" data-bs-target="#scheduleModal" 
                data-group="${groupNumber}" data-cluster="${cluster}" data-set="A" 
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
        updateGroupStatus(schedule.group_id, currentDefenseType, true, null);
        
        const parentRow = targetCell.closest('tr');
        if (parentRow) {
            parentRow.classList.add('row-flash');
            setTimeout(() => parentRow.classList.remove('row-flash'), 2000);
        }
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
            return { text: 'Passed', class: 'status-passed' };
        } else if (statusResult === 'Re-defense') {
            return { text: 'Re-defense', class: 'status-redefense' };
        } else if (statusResult === 'Failed') {
            return { text: 'Failed', class: 'status-failed' };
        }
        
        // If scheduled but no evaluation result yet
        if (isScheduled) {
            return { text: 'Scheduled', class: 'status-ongoing' };
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


    
    function checkPanelAvailability(panelNames, selectedDate, startTime, endTime) {
        const dept = document.getElementById('dept-select').value;
        const cluster = document.getElementById('cluster-select').value;
        const groupId = currentGroupTarget || scheduleButton.getAttribute('data-group-target');
        const defenseType = currentDefenseType === 'REDEFENSE' ? document.getElementById('redefense-type-select').value : currentDefenseType;
        
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
                section: cluster,
                group_id: groupId,
                defense_type: defenseType
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

    function setupAvailabilityModal(cluster, groupId, names, dept, adviserName = 'No Adviser') {
        const tableHeadRow = document.querySelector('#infoModal thead tr');
        const tbody = document.getElementById('availability-table-body');
        const display = document.getElementById('info-set-display');
        const adviserNameElement = document.getElementById('adviser-name');
        
        tbody.innerHTML = '<tr><td colspan="100%" class="text-center">Loading...</td></tr>';
        while (tableHeadRow.children.length > 1) {
            tableHeadRow.removeChild(tableHeadRow.lastChild);
        }

        display.textContent = `Cluster ${cluster} Group ${groupId.substring(1)}`;
        adviserNameElement.textContent = adviserName;

        const deptParam = dept || document.getElementById('dept-select').value;
        const clusterParam = cluster || document.getElementById('cluster-select').value;
        
        fetch(`/api/panels?department=${deptParam}`)
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
                    const nameCell = row.insertCell();
                    nameCell.innerHTML = `<strong>${name}</strong> <span class="badge bg-primary">Assigned</span>`;
                    
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
                
                // Show backup panels with matching availability
                const backupPanels = allPanels.filter(p => !names.includes(p.name));
                if (backupPanels.length > 0) {
                    const separatorRow = tbody.insertRow();
                    const separatorCell = separatorRow.insertCell();
                    separatorCell.colSpan = sortedDates.length + 1;
                    separatorCell.innerHTML = '<hr class="my-2"><h6 class="text-primary mb-0"><i class="bi bi-people"></i> Backup Panels (Same Availability)</h6>';
                    separatorCell.style.backgroundColor = '#e7f1ff';
                    separatorCell.style.padding = '0.75rem';
                    separatorCell.style.fontWeight = 'bold';
                    
                    backupPanels.forEach(panel => {
                        if (panel.availability) {
                            const availability = Array.isArray(panel.availability) ? panel.availability : [];
                            panelAvailabilityMap[panel.name] = {};
                            availability.forEach(avail => {
                                if (avail.date) {
                                    panelAvailabilityMap[panel.name][avail.date] = avail.time || 'Available';
                                }
                            });
                        }
                        
                        const row = tbody.insertRow();
                        const nameCell = row.insertCell();
                        nameCell.innerHTML = `<strong>${panel.name}</strong> <span class="badge bg-info">Backup</span>`;
                        
                        sortedDates.forEach(dateStr => {
                            const cell = row.insertCell();
                            const timeSlot = panelAvailabilityMap[panel.name] && panelAvailabilityMap[panel.name][dateStr];
                            if (timeSlot) {
                                cell.textContent = timeSlot;
                                cell.classList.add('availability-available');
                                cell.title = `Available: ${timeSlot}`;
                                cell.style.fontWeight = '600';
                            } else {
                                cell.textContent = 'N/A';
                                cell.classList.add('availability-unavailable');
                                cell.title = 'Not available on this date';
                            }
                        });
                    });
                }
                
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
                // Parse date without timezone conversion
                const [year, month, day] = schedule.defense_date.split('-');
                const dateDisplay = `${month}/${day}/${year}`;
                
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
                        data-group="${schedule.group_id.substring(1)}" data-cluster="${cluster}" data-set="A" 
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
                
                // Update status based on schedule status field
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
    
    function disableFinalDefenseForNonPassed() {
        const dept = document.getElementById('dept-select').value;
        const cluster = document.getElementById('cluster-select').value;
        if (dept && cluster) {
            disableIneligibleButtons(dept, cluster);
        }
    }

    window.loadCalendarData = function() {
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
            console.log('Calendar schedules loaded:', schedules.length);
            console.log('First schedule date:', schedules[0]?.defense_date);
            console.log('All schedule dates:', schedules.map(s => s.defense_date));
            window.calendarSchedules = schedules;
            
            // If we have schedules, navigate to the month of the first schedule
            if (schedules.length > 0) {
                const firstScheduleDate = new Date(schedules[0].defense_date + 'T00:00:00');
                console.log('First schedule date object:', firstScheduleDate);
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

    window.renderCalendar = function() {
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
            
            // Create date string in YYYY-MM-DD format for comparison
            const year = cellDate.getFullYear();
            const monthNum = String(cellDate.getMonth() + 1).padStart(2, '0');
            const dayNum = String(cellDate.getDate()).padStart(2, '0');
            const dateStr = `${year}-${monthNum}-${dayNum}`;
            
            // Add events for this date
            const dayEvents = (window.calendarSchedules || []).filter(schedule => {
                if (!schedule.defense_date) return false;
                // Handle both date formats: YYYY-MM-DD and full ISO string
                const scheduleDate = schedule.defense_date.split('T')[0];
                return scheduleDate === dateStr;
            });
            
            dayEvents.forEach(schedule => {
                const event = document.createElement('span');
                event.className = 'calendar-event';
                const defenseClass = schedule.defense_type === 'PRE-ORAL' ? 'pre-oral' : 
                                    schedule.defense_type === 'FINAL DEFENSE' ? 'final-defense' : 'redefense';
                event.classList.add(defenseClass);
                event.textContent = `${schedule.start_time} G${schedule.group_id}`;
                dayCell.appendChild(event);
            });
            
            // Add click event to show date details
            dayCell.addEventListener('click', () => {
                const year = cellDate.getFullYear();
                const monthNum = String(cellDate.getMonth() + 1).padStart(2, '0');
                const dayNum = String(cellDate.getDate()).padStart(2, '0');
                const clickedDateStr = `${year}-${monthNum}-${dayNum}`;
                
                const allEvents = (window.calendarSchedules || []).filter(schedule => {
                    if (!schedule.defense_date) return false;
                    const scheduleDate = schedule.defense_date.split('T')[0];
                    return scheduleDate === clickedDateStr;
                });
                showDateDetails(cellDate, allEvents);
            });
            
            grid.appendChild(dayCell);
        }
    }

    window.showDateDetails = function(date, events) {
        const dateStr = date.toLocaleDateString('en-US', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
        
        document.getElementById('selected-date-title').textContent = `Defenses for ${dateStr}`;
        const detailsBody = document.getElementById('date-details-body');
        
        // Create date string in local timezone (not UTC)
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const clickedDateStr = `${year}-${month}-${day}`;
        
        console.log('Looking for date:', clickedDateStr);
        console.log('Available schedule dates:', (window.calendarSchedules || []).map(s => s.defense_date));
        
        // Re-filter to ensure we get the right events
        const allEvents = (window.calendarSchedules || []).filter(schedule => {
            if (!schedule.defense_date) return false;
            const scheduleDate = schedule.defense_date.split('T')[0];
            return scheduleDate === clickedDateStr;
        });
        
        if (allEvents.length === 0) {
            detailsBody.innerHTML = `<p class="text-muted">No defenses scheduled for this date.</p>`;
        } else {
            let html = '';
            allEvents.forEach(schedule => {
                html += `<div class="mb-1"><small><strong>${schedule.defense_type}</strong> Group ${schedule.group_id} - ${schedule.start_time}-${schedule.end_time} (${schedule.department}-${schedule.section})</small></div>`;
            });
            detailsBody.innerHTML = html;
        }
        
        document.getElementById('date-details').style.display = 'block';
    }

    function saveScheduleWithAutoSchedule(targetId, startTime, endTime, dateActual, limit) {
        console.log('saveScheduleWithAutoSchedule called:', {targetId, startTime, endTime, dateActual, limit});
        const dept = document.getElementById('dept-select').value;
        const cluster = document.getElementById('cluster-select').value;
        const adviser = document.getElementById('modal-adviser').textContent;
        const chair = document.getElementById('modal-chairperson').textContent;
        const members = document.getElementById('modal-members').textContent;
        const defenseType = currentDefenseType === 'REDEFENSE' ? document.getElementById('redefense-type-select').value : currentDefenseType;
        
        console.log('Fetching groups and existing schedules for:', {dept, cluster});
        
        // Fetch both groups and existing schedules
        Promise.all([
            fetch(`/api/groups?department=${dept}&cluster=${cluster}`).then(r => r.json()),
            fetch(`/defense-schedules/by-type?defense_type=${defenseType}&department=${dept}&section=${cluster}`).then(r => r.json())
        ])
        .then(([groupsData, existingSchedules]) => {
            console.log('Groups fetched:', groupsData);
            console.log('Existing schedules:', existingSchedules);
            
            const allGroups = groupsData.groups || [];
            const scheduledGroupIds = new Set(existingSchedules.map(s => s.group_id));
            
            // Filter out groups that already have schedules
            const unscheduledGroups = allGroups.filter(g => {
                const groupId = g.group_id || g.id;
                return !scheduledGroupIds.has(groupId);
            }).slice(0, limit);
            
            console.log('Unscheduled groups to schedule:', unscheduledGroups.length, unscheduledGroups);
            
            if (unscheduledGroups.length === 0) {
                showAlert('info', 'No Groups to Schedule', 'All groups up to the limit already have schedules.');
                return;
            }
            
            const schedules = [];
            let currentStart = startTime;
            
            const start = new Date(`2000-01-01T${startTime}`);
            const end = new Date(`2000-01-01T${endTime}`);
            const durationMinutes = (end - start) / 60000;
            
            console.log('Duration per group:', durationMinutes, 'minutes');
            
            unscheduledGroups.forEach(group => {
                const groupId = group.group_id || group.id;
                const currentEnd = addMinutes(currentStart, durationMinutes);
                
                schedules.push({
                    department: dept,
                    section: cluster,
                    group_id: groupId,
                    defense_type: defenseType,
                    assignment_id: 1,
                    defense_date: dateActual,
                    start_time: currentStart,
                    end_time: currentEnd,
                    panel_data: { adviser, chairperson: chair, members }
                });
                
                currentStart = currentEnd;
            });
            
            console.log('Schedules to save:', schedules);
            
            Promise.all(schedules.map(schedule => 
                fetch('/defense-schedules', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(schedule)
                }).then(r => {
                    console.log('Response for group', schedule.group_id, 'status:', r.status);
                    if (!r.ok) {
                        return r.json().then(err => {
                            console.log('Error response:', err);
                            return { success: false, error: err.error, conflicts: err.conflicts, schedule };
                        });
                    }
                    return r.json().then(data => {
                        console.log('Success response:', data);
                        return data;
                    });
                }).catch(err => {
                    console.error('Fetch error for group', schedule.group_id, err);
                    return { success: false, error: err.message, schedule };
                })
            ))
            .then(results => {
                console.log('All results:', results);
                
                const failures = results.filter(r => r.success === false);
                const successes = results.filter(r => r.success === true);
                
                console.log('Successes:', successes.length, 'Failures:', failures.length);
                
                const modalElement = document.getElementById('scheduleModal');
                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                if (modalInstance) modalInstance.hide();
                
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                document.body.classList.remove('modal-open');
                document.body.style.removeProperty('overflow');
                document.body.style.removeProperty('padding-right');
                
                if (failures.length > 0) {
                    let conflictMsg = `${successes.length} groups scheduled successfully.\n\n${failures.length} conflicts detected:\n\n`;
                    failures.forEach(f => {
                        const groupNum = f.schedule.group_id.replace(/[^0-9]/g, '');
                        conflictMsg += `Group ${groupNum}: ${f.conflicts ? f.conflicts.join(', ') : f.error}\n`;
                    });
                    showAlert('warning', 'Partial Success', conflictMsg);
                } else {
                    showAlert('success', 'Auto-Schedule Complete', `${successes.length} groups scheduled successfully!`);
                }
                
                setTimeout(() => loadScheduleData(), 500);
            })
            .catch(error => {
                console.error('Error in auto-schedule:', error);
                showAlert('error', 'Auto-Schedule Failed', error.message);
            });
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            showAlert('error', 'Failed to Load Data', error.message);
        });
    }
    
    function saveScheduleToDatabase(targetId) {
        const startTime = document.getElementById('start-time-input').value;
        const endTime = document.getElementById('end-time-input').value;
        const dateActual = document.getElementById('date-actual-input').value;
        const dept = document.getElementById('dept-select').value;
        const cluster = document.getElementById('cluster-select').value;
        
        console.log('DEBUG: saveScheduleToDatabase', {targetId, startTime, endTime, dateActual, dept, cluster});
        console.log('Date input value:', dateActual);
        
        if (!startTime || !endTime || !dateActual) {
            showAlert('warning', 'Missing Information', 'Please select a Start Time, End Time, and Date before scheduling.');
            return;
        }
        
        const adviser = document.getElementById('modal-adviser').textContent;
        const chair = document.getElementById('modal-chairperson').textContent;
        const members = document.getElementById('modal-members').textContent;
        
        const scheduleData = {
            department: dept,
            section: cluster,
            group_id: targetId,
            defense_type: currentDefenseType === 'REDEFENSE' ? 'REDEFENSE' : currentDefenseType,
            original_defense_type: currentDefenseType === 'REDEFENSE' ? document.getElementById('redefense-type-select').value : null,
            status: currentDefenseType === 'REDEFENSE' ? 'Pending' : 'Scheduled',
            assignment_id: 1,
            defense_date: dateActual,
            start_time: startTime,
            end_time: endTime,
            panel_data: {
                adviser: adviser,
                chairperson: chair,
                members: members
            }
        };
        
        console.log('Sending schedule data:', scheduleData);
        console.log('Defense date being sent:', scheduleData.defense_date);
        
        fetch('/defense-schedules', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(scheduleData)
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            return response.text().then(text => {
                console.log('Raw response text:', text);
                if (!response.ok) {
                    try {
                        const errorData = JSON.parse(text);
                        console.error('Parsed error data:', errorData);
                        if (errorData.conflicts && errorData.conflicts.length > 0) {
                            throw new Error('Conflict: ' + errorData.conflicts.join('\n'));
                        }
                        if (errorData.details) {
                            const detailsStr = JSON.stringify(errorData.details);
                            throw new Error(errorData.error + ': ' + detailsStr);
                        }
                        throw new Error(errorData.error || errorData.message || 'Failed to save schedule');
                    } catch(e) {
                        if (e.message.startsWith('Conflict:')) throw e;
                        console.error('Parse error:', e);
                        throw new Error('Server error: ' + text.substring(0, 200));
                    }
                }
                try {
                    return JSON.parse(text);
                } catch(e) {
                    throw new Error('Invalid JSON response: ' + text.substring(0, 100));
                }
            });
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                const modalElement = document.getElementById('scheduleModal');
                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                if (modalInstance) {
                    modalInstance.hide();
                }
                // Remove backdrop manually
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                document.body.classList.remove('modal-open');
                document.body.style.removeProperty('overflow');
                document.body.style.removeProperty('padding-right');
                
                showAlert('success', 'Success', 'Schedule saved successfully!');
                simulateScheduleSuccess(targetId);
                setTimeout(() => loadScheduleData(), 500);
            } else {
                showAlert('error', 'Save Failed', data.error || data.message || 'Failed to save schedule.');
            }
        })
        .catch(error => {
            console.error('Error saving schedule:', error);
            
            // Clean up modal backdrop on error too
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');
            
            showAlert('error', 'Save Failed', error.message);
        });
    }

    function simulateScheduleSuccess(targetId) {
        const startTime = document.getElementById('start-time-input').value;
        const endTime = document.getElementById('end-time-input').value;
        const dateDisplay = document.getElementById('date-display-input').value;
        const dateActual = document.getElementById('date-actual-input').value;

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
                data-group="${group}" data-cluster="${cluster}" data-set="A" 
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
        
        // Update status to Ongoing when schedule is set
        updateGroupStatus(targetId, currentDefenseType, true, null);
        
        document.querySelectorAll('#schedule-table-body tr').forEach(row => {
            const isSetB = row.getAttribute('data-set') === 'B';
            row.style.backgroundColor = isSetB ? 'var(--group-stripe)' : '';
        });

        showAlert('success', 'Schedule Saved!', `Group ${targetId} scheduled for ${dateDisplay} at ${startTime} and saved to database.`);

        if (parentRow) {
            parentRow.classList.add('row-flash');
            setTimeout(() => parentRow.classList.remove('row-flash'), 2000);
        }

        const scheduleModal = document.getElementById('scheduleModal');
        const modalInstance = bootstrap.Modal.getInstance(scheduleModal);
        if (modalInstance) {
            modalInstance.hide();
        }
        // Clean up any leftover backdrops
        setTimeout(() => {
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');
        }, 300);
        
        // Save state before any potential reload
        localStorage.setItem('currentView', 'schedule');
        localStorage.setItem('selectedDept', document.getElementById('dept-select').value);
        localStorage.setItem('selectedCluster', document.getElementById('cluster-select').value);
        if (currentDefenseType === 'REDEFENSE') {
            localStorage.setItem('selectedRedefenseType', document.getElementById('redefense-type-select').value);
        }
    }
    
    // Global function for group info modal
    window.showGroupInfo = function(displayNumber, groupId) {
        console.log('showGroupInfo called:', displayNumber, groupId);
        
        const dept = document.getElementById('dept-select')?.value || 'BSIT';
        const cluster = document.getElementById('cluster-select')?.value || '4101';
        
        // Find the row to get actual data
        const row = document.querySelector(`tr[data-group-id="${groupId}"]`);
        let status = 'Pending';
        
        if (row) {
            const statusBadge = row.querySelector('.status-badge');
            if (statusBadge) {
                status = statusBadge.textContent.trim();
            }
        }
        
        // Update modal content
        document.getElementById('groupInfoTitle').textContent = `Group ${displayNumber}`;
        document.getElementById('groupDept').textContent = dept;
        document.getElementById('groupCluster').textContent = cluster;
        document.getElementById('groupStatus').textContent = status;
        
        // Set loading state
        document.getElementById('groupAdviser').textContent = 'Loading...';
        document.getElementById('groupChair').textContent = 'Loading...';
        document.getElementById('groupPanelMembers').textContent = 'Loading...';
        document.getElementById('groupMembers').innerHTML = '<p class="text-muted">Loading students...</p>';
        
        // Show modal immediately
        new bootstrap.Modal(document.getElementById('groupInfoModal')).show();
        
        // Fetch adviser assigned to this group number, department, and cluster
        fetch(`/api/groups?department=${dept}&cluster=${cluster}`)
            .then(response => response.json())
            .then(data => {
                const groups = data.groups || [];
                const group = groups.find(g => (g.group_id || g.id) == groupId || g.group_number == parseInt(displayNumber));
                
                if (group) {
                    // Now fetch adviser for this group
                    return fetch(`/api/advisers`).then(r => r.json()).then(advisers => {
                        const adviser = advisers.find(a => {
                            const sections = a.sections || [];
                            return a.department === dept && sections.includes(parseInt(displayNumber));
                        });
                        
                        if (adviser) {
                            document.getElementById('groupAdviser').textContent = adviser.name;
                        } else {
                            document.getElementById('groupAdviser').textContent = 'No Adviser';
                        }
                    });
                } else {
                    document.getElementById('groupAdviser').textContent = 'No Adviser';
                }
            })
            .catch(error => {
                console.error('Error fetching adviser:', error);
                document.getElementById('groupAdviser').textContent = 'Error loading data';
            });
        
        // Fetch panel data from assignments
        fetch(`/api/assignments?department=${dept}&section=${cluster}`)
            .then(response => response.json())
            .then(assignments => {
                const assignment = assignments.find(a => a.department === dept && a.section === cluster && a.group_number && a.group_number.includes(String(displayNumber)));
                
                if (assignment) {
                    const panels = assignment.panels || [];
                    const chairperson = panels.find(p => p.role === 'Chairperson');
                    const members = panels.filter(p => p.role === 'Member');
                    const chairName = chairperson ? chairperson.name : 'No Chairperson';
                    const memberNames = members.length > 0 ? members.map(m => m.name).join(', ') : 'No Members';
                    
                    document.getElementById('groupChair').textContent = chairName;
                    document.getElementById('groupPanelMembers').textContent = memberNames;
                } else {
                    document.getElementById('groupChair').textContent = 'No Chairperson';
                    document.getElementById('groupPanelMembers').textContent = 'No Members';
                }
            })
            .catch(error => {
                console.error('Error fetching panel data:', error);
                document.getElementById('groupChair').textContent = 'Error loading data';
                document.getElementById('groupPanelMembers').textContent = 'Error loading data';
            });
        
        // Fetch student data
        fetchGroupStudents(displayNumber, dept)
            .then(students => {
                if (students && students.length > 0) {
                    let membersHtml = '<ul class="list-unstyled mb-0">';
                    students.forEach((student) => {
                        const leaderBadge = student.is_leader ? ' <span class="badge bg-primary">Leader</span>' : '';
                        membersHtml += `<li>${student.name} (${student.student_id})${leaderBadge}</li>`;
                    });
                    membersHtml += '</ul>';
                    document.getElementById('groupMembers').innerHTML = membersHtml;
                } else {
                    document.getElementById('groupMembers').innerHTML = '<p class="text-muted">No student data available</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching students:', error);
                document.getElementById('groupMembers').innerHTML = '<p class="text-muted">No student data available</p>';
            });
    }
    
    
    document.addEventListener('DOMContentLoaded', function () {
        
        // Defense type buttons now use onclick handlers directly in HTML

        const typeSelectionView = document.getElementById('type-selection-view');
        const filterPage = document.getElementById('filter-page');
        const enterButton = document.getElementById('enterButton');
        const scheduleView = document.getElementById('schedule-view');
        const scheduleButton = document.getElementById('schedule-button');
        const scheduleModal = document.getElementById('scheduleModal');
        let currentGroupTarget = null;
        const panelEditModal = document.getElementById('panelEditModal');
        const savePanelDetailsBtn = document.getElementById('savePanelDetailsBtn');
        const deptSelect = document.getElementById('dept-select');
        const clusterSelect = document.getElementById('cluster-select');
        const dateDisplayInput = document.getElementById('date-display-input');
        const dateActualInput = document.getElementById('date-actual-input');
        

        // Check for URL parameters from panel-adviser or redefense redirect
        const urlParams = new URLSearchParams(window.location.search);
        const department = urlParams.get('department');
        const cluster = urlParams.get('cluster');
        const assignmentId = urlParams.get('assignment_id');
        const defenseType = urlParams.get('defense_type');
        const originalType = urlParams.get('original_type');
        
        console.log('URL Params:', { department, cluster, assignmentId, defenseType });
        
        if (department && cluster) {
            console.log('Auto-loading schedule for:', department, cluster);
            // Clear localStorage to prevent conflicts, but save new values
            localStorage.setItem('selectedDefenseType', defenseType || 'PRE-ORAL');
            localStorage.setItem('currentView', 'schedule');
            localStorage.setItem('selectedDept', department);
            localStorage.setItem('selectedCluster', cluster);
            if (defenseType === 'REDEFENSE' && originalType) {
                localStorage.setItem('selectedRedefenseType', originalType);
            }
            
            // Auto-select defense type based on URL parameter or default to PRE-ORAL
            currentDefenseType = defenseType || 'PRE-ORAL';
            window.showSchedulePage();
            console.log('Schedule page shown');
            
            document.getElementById('defense-type-display').textContent = `${currentDefenseType} SCHEDULING`;
            
            // Pre-populate department and cluster
            deptSelect.value = department;
            
            // Ensure cluster dropdown is populated before setting value
            if (department === 'BSIT') {
                fetch('/api/clusters')
                    .then(r => r.json())
                    .then(data => {
                        const clusters = (data.clusters || []).sort((a, b) => parseInt(a) - parseInt(b));
                        clusterSelect.innerHTML = '<option selected disabled value="">Select Cluster</option>';
                        clusters.forEach(c => {
                            clusterSelect.innerHTML += `<option value="${c}">${c}</option>`;
                        });
                        clusterSelect.value = cluster;
                        
                        const redefenseTypeGroup = document.getElementById('redefense-type-group');
                        if (currentDefenseType === 'REDEFENSE' && originalType) {
                            redefenseTypeGroup.style.display = 'block';
                            document.getElementById('redefense-type-select').value = originalType;
                        } else {
                            redefenseTypeGroup.style.display = 'none';
                        }
                        
                        updateScheduleView();
                        setTimeout(() => {
                            console.log('Calling loadScheduleData');
                            loadScheduleData();
                        }, 300);
                    });
            } else {
                clusterSelect.value = cluster;
                
                const redefenseTypeGroup = document.getElementById('redefense-type-group');
                if (currentDefenseType === 'REDEFENSE' && originalType) {
                    redefenseTypeGroup.style.display = 'block';
                    document.getElementById('redefense-type-select').value = originalType;
                } else {
                    redefenseTypeGroup.style.display = 'none';
                }
                
                updateScheduleView();
                setTimeout(() => loadScheduleData(), 300);
            }
            
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
            
            if (savedView === 'calendar') {
                typeSelectionView.classList.add('hidden');
                scheduleView.style.display = 'none';
                filterPage.classList.add('hidden');
                document.getElementById('calendar-view').style.display = 'block';
                loadCalendarData();
                return;
            }
            
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
                    
                    // Load clusters first if BSIT
                    if (savedDept === 'BSIT') {
                        fetch('/api/clusters/bsit')
                            .then(response => response.json())
                            .then(data => {
                                const clusters = (data.clusters || []).sort((a, b) => parseInt(a) - parseInt(b));
                                clusterSelect.innerHTML = '<option selected disabled value="">Select Cluster</option>';
                                clusters.forEach(cluster => {
                                    clusterSelect.innerHTML += `<option value="${cluster}">${cluster}</option>`;
                                });
                                
                                // Set values AFTER populating
                                deptSelect.value = savedDept;
                                clusterSelect.value = savedCluster;
                                
                                window.showSchedulePage();
                                const displayType = currentDefenseType === 'REDEFENSE' ? `${currentDefenseType} (${savedRedefenseType})` : currentDefenseType;
                                document.getElementById('dept-header').textContent = `${displayType}: DEPT. OF ${savedDept} (Cluster ${savedCluster})`;
                                document.getElementById('schedule-content').style.display = 'block';
                                document.getElementById('empty-state').style.display = 'none';
                                
                                // Fetch and generate table
                                fetch(`/api/assignments?department=${savedDept}&section=${savedCluster}`)
                                    .then(response => response.json())
                                    .then(assignments => {
                                        const assignment = assignments.find(a => a.department === savedDept && a.section === savedCluster);
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
                                        
                                        if (window.generateTableRows) {
                                            window.generateTableRows(adviser, chairName, memberNames);
                                        }
                                    });
                            });
                    } else {
                        // Set values FIRST for non-BSIT
                        deptSelect.value = savedDept;
                        clusterSelect.value = savedCluster;
                        
                        window.showSchedulePage();
                        const displayType = currentDefenseType === 'REDEFENSE' ? `${currentDefenseType} (${savedRedefenseType})` : currentDefenseType;
                        document.getElementById('dept-header').textContent = `${displayType}: DEPT. OF ${savedDept} (Cluster ${savedCluster})`;
                        document.getElementById('schedule-content').style.display = 'block';
                        document.getElementById('empty-state').style.display = 'none';
                        
                        // Fetch and generate table
                        fetch(`/api/assignments?department=${savedDept}&section=${savedCluster}`)
                            .then(response => response.json())
                            .then(assignments => {
                                const assignment = assignments.find(a => a.department === savedDept && a.section === savedCluster);
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
                                
                                if (window.generateTableRows) {
                                    window.generateTableRows(adviser, chairName, memberNames);
                                }
                            });
                    }
                } else {
                    window.showFilterPage();
                    if (savedDept) deptSelect.value = savedDept;
                    if (savedCluster) clusterSelect.value = savedCluster;
                }
            }
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
                document.getElementById('dept-header').textContent = `${displayType}: DEPT. OF ${dept} (Cluster ${cluster})`;
                
                document.getElementById('schedule-content').style.display = 'block';
                document.getElementById('empty-state').style.display = 'none';
                
                // Fetch assignment data and update button attributes
                fetch(`/api/assignments?department=${dept}&section=${cluster}`)
                    .then(response => response.json())
                    .then(assignments => {
                        const assignment = assignments.find(a => a.department === dept && a.section === cluster);
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
                        
                        // Generate table rows with panel data
                        generateTableRows(adviser, chairName, memberNames);
                        
                        // Update all schedule buttons with panel data
                        document.querySelectorAll('.btn-set-schedule').forEach(btn => {
                            btn.setAttribute('data-adviser', adviser);
                            btn.setAttribute('data-chair', chairName);
                            btn.setAttribute('data-members', memberNames);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching assignments:', error);
                    });
                
                document.querySelectorAll('.cluster-value').forEach(cell => {
                    cell.textContent = cluster;
                });
            } else {
                const requiredFields = defenseType === 'REDEFENSE' ? 'Department, Cluster, and Defense Type' : 'Department and Cluster';
                document.getElementById('dept-header').textContent = `Select ${requiredFields} to view the ${defenseType} schedule.`;
                document.getElementById('schedule-content').style.display = 'none';
                document.getElementById('empty-state').style.display = 'block';
            }
        }


        // Navigation functions
        const backToFilterButton = document.getElementById('backToFilterButton');
        if (backToFilterButton) {
            backToFilterButton.addEventListener('click', function() {
                console.log('Back to filter button clicked');
                localStorage.clear();
                currentDefenseType = '';
                window.showTypeSelection();
            });
        }
        
        const backToFilterFromSchedule = document.getElementById('backToFilterFromSchedule');
        if (backToFilterFromSchedule) {
            backToFilterFromSchedule.addEventListener('click', function() {
                console.log('Back to filter from schedule clicked');
                window.showFilterPage();
            });
        }

        // Department change handler
        deptSelect.addEventListener('change', function() {
            console.log('Department changed to:', this.value);
            const clusterLabel = document.getElementById('cluster-label');
            const clusterHeader = document.getElementById('cluster-header');
            
            clusterSelect.innerHTML = '<option selected disabled value="">Loading...</option>';
            
            if (this.value === 'BSIT') {
                clusterLabel.textContent = 'Cluster';
                if (clusterHeader) clusterHeader.textContent = 'Cluster';
                
                fetch('/api/clusters/bsit')
                    .then(response => {
                        if (!response.ok) throw new Error('Failed to fetch');
                        return response.json();
                    })
                    .then(data => {
                        console.log('Clusters loaded:', data);
                        const clusters = (data.clusters || []).sort((a, b) => parseInt(a) - parseInt(b));
                        clusterSelect.innerHTML = '<option selected disabled value="">Select Cluster</option>';
                        if (clusters.length > 0) {
                            clusters.forEach(cluster => {
                                clusterSelect.innerHTML += `<option value="${cluster}">${cluster}</option>`;
                            });
                        } else {
                            clusterSelect.innerHTML = '<option selected disabled value="">No clusters available</option>';
                        }
                    })
                    .catch(error => {
                        console.error('Error loading clusters:', error);
                        clusterSelect.innerHTML = '<option selected disabled value="">Select Cluster</option>';
                    });
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
        
        // Trigger change event if BSIT is already selected
        if (deptSelect.value === 'BSIT') {
            deptSelect.dispatchEvent(new Event('change'));
        }

        if (enterButton) {
            enterButton.addEventListener('click', () => {
                console.log('Enter button clicked');
                const dept = deptSelect.value;
                const cluster = clusterSelect.value;
                const redefenseType = document.getElementById('redefense-type-select').value;
                
                console.log('Values:', {dept, cluster, redefenseType, currentDefenseType});
                
                if (!dept || !cluster) {
                    showAlert('warning', 'Missing Information', 'Please select both Department and Cluster.');
                    return;
                }
                
                if (currentDefenseType === 'REDEFENSE' && !redefenseType) {
                    showAlert('warning', 'Missing Information', 'Please select Defense Type for Re-defense.');
                    return;
                }
                
                localStorage.setItem('currentView', 'schedule');
                localStorage.setItem('selectedDept', dept);
                localStorage.setItem('selectedCluster', cluster);
                if (currentDefenseType === 'REDEFENSE') {
                    localStorage.setItem('selectedRedefenseType', redefenseType);
                }
                
                window.showSchedulePage();
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
        } else {
            console.error('enterButton not found!');
        }
        document.getElementById('redefense-type-select').addEventListener('change', () => {
            updateScheduleView();
            loadScheduleData();
        });
        
        // Department change handler moved to inline script above
        
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


        scheduleModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) {
                console.error('No button found for modal trigger');
                return;
            }
            
            const isEditing = button.classList.contains('scheduled-container');
            
            const cluster = clusterSelect.value;
            const set = button.getAttribute('data-set'); 
            const group = button.getAttribute('data-group');
            const realGroupId = button.getAttribute('data-real-group-id') || (set + group);
            
            console.log('Modal opening for:', {cluster, set, group, realGroupId, isEditing});
            
            // Show/hide edit button based on whether schedule exists
            const editBtn = document.getElementById('edit-schedule-button');
            if (isEditing) {
                editBtn.style.display = 'block';
            } else {
                editBtn.style.display = 'none';
            }
            
            // Validate scheduling attempt and prevent modal from opening if not eligible
            if (!isEditing && currentDefenseType === 'FINAL DEFENSE') {
                event.preventDefault();
                validateScheduleAttempt(realGroupId, currentDefenseType).then(isValid => {
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

            console.log('Panel data:', {adviser, chair, members});

            document.getElementById('modal-title-display').textContent = `Schedule Defense: ${cluster} Group ${group}`;
            document.getElementById('modal-adviser').textContent = adviser;
            document.getElementById('modal-chairperson').textContent = chair;
            document.getElementById('modal-members').textContent = members;

            scheduleButton.setAttribute('data-group-target', realGroupId);
            currentGroupTarget = realGroupId;
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
            
            // Reset auto-schedule checkbox
            document.getElementById('auto-schedule-checkbox').checked = false;
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
            const autoSchedule = document.getElementById('auto-schedule-checkbox').checked;
            const autoLimit = parseInt(document.getElementById('auto-schedule-limit').value) || 10;
            
            console.log('Schedule button clicked:', {targetId, startTime, endTime, dateActual, autoSchedule, autoLimit});
            
            if (!startTime || !endTime || !dateActual) {
                showAlert('warning', 'Missing Information', 'Please select a Start Time, End Time, and Date before scheduling.');
                return;
            }
            
            if (autoSchedule) {
                console.log('Auto-schedule enabled, calling saveScheduleWithAutoSchedule');
                saveScheduleWithAutoSchedule(targetId, startTime, endTime, dateActual, autoLimit);
            } else {
                console.log('Auto-schedule disabled, calling saveScheduleToDatabase');
                saveScheduleToDatabase(targetId);
            }
        });

        document.getElementById('edit-schedule-button').addEventListener('click', function() {
            console.log('Edit button clicked - enabling form fields');
            
            // Enable all form fields
            document.getElementById('start-time-input').removeAttribute('readonly');
            document.getElementById('end-time-input').removeAttribute('readonly');
            document.getElementById('date-actual-input').removeAttribute('readonly');
            document.getElementById('date-display-input').removeAttribute('readonly');
            
            // Change schedule button text to Update
            scheduleButton.textContent = 'Update Schedule';
            
            // Hide edit button after clicking
            this.style.display = 'none';
            
            showAlert('info', 'Edit Mode', 'You can now modify the schedule. Click "Update Schedule" to save changes.');
        });
        
        function resetGroupUI(groupId) {
            const cell = document.querySelector(`[data-schedule-target="${groupId}"]`);
            if (!cell) return;
            
            const row = cell.closest('tr');
            const btn = row.querySelector('.btn-set-schedule, .scheduled-container');
            const adviser = btn?.getAttribute('data-adviser') || 'No Adviser';
            const chair = btn?.getAttribute('data-chair') || 'No Chairperson';
            const members = btn?.getAttribute('data-members') || 'No Members';
            const set = row.getAttribute('data-set') || 'A';
            const group = groupId.replace(/[^0-9]/g, '');
            
            cell.innerHTML = `<button class="btn btn-set-schedule" data-bs-toggle="modal" data-bs-target="#scheduleModal" 
                data-group="${group}" data-cluster="" data-set="${set}" data-real-group-id="${groupId}"
                data-adviser="${adviser}" data-chair="${chair}" data-members="${members}">
                <i class="bi bi-calendar-plus"></i> Set Schedule
            </button>`;
            updateGroupStatus(groupId, currentDefenseType, false, null);
        }
        
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
        
        document.getElementById('generateScheduleBtn').addEventListener('click', function() {
            try {
                const tbody = document.getElementById('availability-table-body');
                const tableHeadRow = document.querySelector('#infoModal thead tr');
                
                if (!tbody || !tableHeadRow) {
                    showAlert('error', 'Error', 'Could not load availability data.');
                    return;
                }
                
                const assignedRows = Array.from(tbody.querySelectorAll('tr')).filter(row => {
                    const firstCell = row.querySelector('td');
                    return firstCell && firstCell.innerHTML.includes('Assigned');
                });
                
                if (assignedRows.length === 0) {
                    showAlert('warning', 'No Data', 'No assigned panel availability data.');
                    return;
                }
                
                const dates = [];
                for (let i = 1; i < tableHeadRow.children.length; i++) {
                    dates.push(tableHeadRow.children[i].textContent);
                }
                
                if (dates.length === 0) {
                    const today = new Date();
                    const suggestedDate = new Date(today);
                    suggestedDate.setDate(suggestedDate.getDate() + 7);
                    const dateStr = suggestedDate.toISOString().split('T')[0];
                    const [year, month, day] = dateStr.split('-');
                    
                    document.getElementById('date-actual-input').value = dateStr;
                    document.getElementById('date-display-input').value = `${month}/${day}/${year}`;
                    document.getElementById('start-time-input').value = '08:00';
                    document.getElementById('end-time-input').value = '09:00';
                    
                    showAlert('info', 'Default Suggestion', `Panels haven't set availability. Suggested: ${month}/${day}/${year} at 8:00 AM - 9:00 AM.`, () => {
                        bootstrap.Modal.getInstance(document.getElementById('infoModal')).hide();
                        setTimeout(() => new bootstrap.Modal(document.getElementById('scheduleModal')).show(), 300);
                    });
                    return;
                }
                
                const availableDates = [];
                dates.forEach((dateHeader, dateIdx) => {
                    let allAvailable = true;
                    let timeSlot = null;
                    
                    assignedRows.forEach(row => {
                        const cells = row.querySelectorAll('td');
                        const cell = cells[dateIdx + 1];
                        
                        if (!cell || cell.textContent === 'N/A') {
                            allAvailable = false;
                        } else if (cell.classList.contains('availability-available')) {
                            if (!timeSlot && cell.textContent !== 'N/A' && cell.textContent !== 'Conflict') {
                                timeSlot = cell.textContent;
                            }
                        }
                    });
                    
                    if (allAvailable && timeSlot) {
                        availableDates.push({ date: dateHeader, time: timeSlot });
                    }
                });
                
                if (availableDates.length === 0) {
                    showAlert('info', 'No Available Dates', 'No common available dates found. Use "Back to Scheduling" to enter manually.');
                    return;
                }
                
                const firstSlot = availableDates[0];
                const dateParts = firstSlot.date.match(/(\d{2})\/(\d{2})/);
                const timeParts = firstSlot.time.match(/(\d{1,2}):(\d{2})/);
                
                if (dateParts && timeParts) {
                    const month = dateParts[1];
                    const day = dateParts[2];
                    const year = new Date().getFullYear();
                    const startHour = timeParts[1].padStart(2, '0');
                    const startMin = timeParts[2];
                    const endHour = String((parseInt(startHour) + 1) % 24).padStart(2, '0');
                    
                    document.getElementById('date-actual-input').value = `${year}-${month}-${day}`;
                    document.getElementById('date-display-input').value = `${month}/${day}/${year}`;
                    document.getElementById('start-time-input').value = `${startHour}:${startMin}`;
                    document.getElementById('end-time-input').value = `${endHour}:${startMin}`;
                    
                    showAlert('success', 'Available Date Found', `Suggested: ${month}/${day}/${year} at ${startHour}:${startMin}. Adjust as needed.`, () => {
                        bootstrap.Modal.getInstance(document.getElementById('infoModal')).hide();
                        setTimeout(() => new bootstrap.Modal(document.getElementById('scheduleModal')).show(), 300);
                    });
                }
            } catch (error) {
                console.error('Generate Schedule Error:', error);
                showAlert('error', 'Error', 'An error occurred. Please try again.');
            }
        });
        
        // Edit Panel button handler
        document.getElementById('editPanelBtn').addEventListener('click', function() {
            const dept = document.getElementById('dept-select').value;
            const cluster = document.getElementById('cluster-select').value;
            
            // Get current assigned panels
            fetch(`/api/assignments?department=${dept}&section=${cluster}`)
                .then(r => r.json())
                .then(assignments => {
                    const assignment = assignments.find(a => a.department === dept && a.section === cluster);
                    if (!assignment) {
                        showAlert('error', 'Error', 'No assignment found for this cluster.');
                        return;
                    }
                    
                    const panels = assignment.panels || [];
                    const chairperson = panels.find(p => p.role === 'Chairperson');
                    const members = panels.filter(p => p.role === 'Member');
                    
                    // Fetch all available panels for replacement
                    fetch(`/api/panels?department=${dept}`)
                        .then(r => r.json())
                        .then(allPanels => {
                            // Populate modal
                            document.getElementById('currentChair').value = chairperson ? chairperson.name : 'None';
                            
                            const chairSelect = document.getElementById('newChairSelect');
                            chairSelect.innerHTML = '<option value="">Keep current</option>';
                            allPanels.forEach(panel => {
                                chairSelect.innerHTML += `<option value="${panel.id}">${panel.name}</option>`;
                            });
                            
                            // Populate member replacements
                            const memberDiv = document.getElementById('memberReplacements');
                            memberDiv.innerHTML = '';
                            members.forEach((member, idx) => {
                                memberDiv.innerHTML += `
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Member ${idx + 1}: ${member.name}</label>
                                        <select class="form-select member-replace-select" data-member-id="${member.id}">
                                            <option value="">Keep current</option>
                                            ${allPanels.map(p => `<option value="${p.id}">${p.name}</option>`).join('')}
                                        </select>
                                    </div>
                                `;
                            });
                            
                            // Store assignment ID for saving
                            document.getElementById('savePanelReplacementBtn').setAttribute('data-assignment-id', assignment.id);
                            
                            // Show modal
                            bootstrap.Modal.getInstance(document.getElementById('infoModal')).hide();
                            setTimeout(() => {
                                new bootstrap.Modal(document.getElementById('editPanelAssignmentModal')).show();
                            }, 300);
                        });
                });
        });
        
        // Save panel replacement
        document.getElementById('savePanelReplacementBtn').addEventListener('click', function() {
            const assignmentId = this.getAttribute('data-assignment-id');
            const newChairId = document.getElementById('newChairSelect').value;
            const memberSelects = document.querySelectorAll('.member-replace-select');
            const newMemberIds = Array.from(memberSelects).map(s => s.value).filter(v => v);
            
            if (!newChairId && newMemberIds.length === 0) {
                showAlert('info', 'No Changes', 'No panel replacements selected.');
                return;
            }
            
            // Update assignment via API
            const updateData = {};
            if (newChairId) updateData.chairperson_id = newChairId;
            if (newMemberIds.length > 0) updateData.member_ids = newMemberIds;
            
            fetch(`/api/assignments/${assignmentId}/update-panels`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(updateData)
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', 'Panel Updated', 'Panel assignment has been updated successfully.');
                    bootstrap.Modal.getInstance(document.getElementById('editPanelAssignmentModal')).hide();
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showAlert('error', 'Update Failed', data.message || 'Failed to update panel assignment.');
                }
            })
            .catch(error => {
                console.error('Error updating panel:', error);
                showAlert('error', 'Update Failed', 'An error occurred while updating the panel.');
            });
        });
        


        
        // Make updateScheduleView globally accessible
        window.updateScheduleView = updateScheduleView;
        window.loadScheduleData = loadScheduleData;
        
        // Calendar navigation buttons
        const prevBtn = document.getElementById('prev-month');
        const nextBtn = document.getElementById('next-month');
        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                currentCalendarDate.setMonth(currentCalendarDate.getMonth() - 1);
                renderCalendar();
            });
        }
        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                currentCalendarDate.setMonth(currentCalendarDate.getMonth() + 1);
                renderCalendar();
            });
        }
        
        const initialView = localStorage.getItem('currentView');
        if (!initialView || (!urlParams.get('department') && !localStorage.getItem('selectedDefenseType'))) {
            typeSelectionView.classList.remove('hidden');
            filterPage.classList.add('hidden');
            scheduleView.style.display = 'none';
        }

    });
</script>
@endsection

