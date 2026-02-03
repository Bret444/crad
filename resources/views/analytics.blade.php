@extends('layouts.app')
@section('content')

<div class="container py-4">
    <h1 class="text-center mb-4 text-primary fw-bold">Research Analytics Dashboard</h1>
    
    <!-- Department Filter -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Filter by Department</label>
                    <select name="department" class="form-select">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ $selectedDepartment == $dept ? 'selected' : '' }}>
                                {{ $dept }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center shadow-sm border-primary">
                <div class="card-body">
                    <i class="fas fa-file-alt fa-2x text-primary mb-2"></i>
                    <h5>Total Submissions</h5>
                    <p class="fs-4 fw-bold text-primary">{{ $totalSubmissions }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm border-success">
                <div class="card-body">
                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                    <h5>Approved</h5>
                    <p class="fs-4 fw-bold text-success">{{ $approvedSubmissions }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm border-danger">
                <div class="card-body">
                    <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                    <h5>Rejected</h5>
                    <p class="fs-4 fw-bold text-danger">{{ $rejectedSubmissions }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm border-warning">
                <div class="card-body">
                    <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                    <h5>Pending</h5>
                    <p class="fs-4 fw-bold text-warning">{{ $pendingSubmissions }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Defense Metrics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center shadow-sm" style="border-left: 4px solid #0d6efd;">
                <div class="card-body">
                    <i class="fas fa-calendar-alt fa-2x text-primary mb-2"></i>
                    <h6>Total Defenses</h6>
                    <p class="fs-4 fw-bold">{{ $totalDefenseSchedules ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm" style="border-left: 4px solid #198754;">
                <div class="card-body">
                    <i class="fas fa-check-double fa-2x text-success mb-2"></i>
                    <h6>Passed</h6>
                    <p class="fs-4 fw-bold text-success">{{ $passedDefenses ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm" style="border-left: 4px solid #dc3545;">
                <div class="card-body">
                    <i class="fas fa-times fa-2x text-danger mb-2"></i>
                    <h6>Failed</h6>
                    <p class="fs-4 fw-bold text-danger">{{ $failedDefenses ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm" style="border-left: 4px solid #ffc107;">
                <div class="card-body">
                    <i class="fas fa-calendar-check fa-2x text-warning mb-2"></i>
                    <h6>Scheduled</h6>
                    <p class="fs-4 fw-bold text-warning">{{ $scheduledDefenses ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Completion Rate -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5>Project Completion Rate</h5>
                    <div class="progress mb-2" style="height: 30px;">
                        <div class="progress-bar bg-success" style="width: {{ $completionRate }}%">
                            {{ $completionRate }}%
                        </div>
                    </div>
                    <small class="text-muted">{{ $approvedSubmissions }} out of {{ $totalSubmissions }} submissions approved</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-chart-bar"></i> Submissions by Status
                </div>
                <div class="card-body">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-chart-pie"></i> Defense Results
                </div>
                <div class="card-body">
                    <canvas id="defenseChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-chart-pie"></i> Department Distribution
                </div>
                <div class="card-body">
                    <canvas id="departmentChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Defense Type Statistics -->
    @if(isset($defenseTypeStats) && $defenseTypeStats->count() > 0)
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <i class="fas fa-graduation-cap"></i> Defense Type Statistics
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Defense Type</th>
                            <th>Total</th>
                            <th>Passed</th>
                            <th>Failed</th>
                            <th>Scheduled</th>
                            <th>Pending</th>
                            <th>Success Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($defenseTypeStats as $stat)
                            <tr>
                                <td><strong>{{ $stat->defense_type }}</strong></td>
                                <td>{{ $stat->total }}</td>
                                <td><span class="badge bg-success">{{ $stat->passed }}</span></td>
                                <td><span class="badge bg-danger">{{ $stat->failed }}</span></td>
                                <td><span class="badge bg-info">{{ $stat->scheduled }}</span></td>
                                <td><span class="badge bg-warning">{{ $stat->pending }}</span></td>
                                <td>
                                    @php
                                        $evaluated = $stat->passed + $stat->failed;
                                        $rate = $evaluated > 0 ? round(($stat->passed / $evaluated) * 100, 1) : 0;
                                    @endphp
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" style="width: {{ $rate }}%">
                                            {{ $rate }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Department Statistics Table -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <i class="fas fa-table"></i> Department Statistics
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Department</th>
                            <th>Total</th>
                            <th>Approved</th>
                            <th>Rejected</th>
                            <th>Pending</th>
                            <th>Success Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departmentStats as $stat)
                            <tr>
                                <td><strong>{{ $stat->department }}</strong></td>
                                <td>{{ $stat->total }}</td>
                                <td><span class="badge bg-success">{{ $stat->approved }}</span></td>
                                <td><span class="badge bg-danger">{{ $stat->rejected }}</span></td>
                                <td><span class="badge bg-warning">{{ $stat->pending }}</span></td>
                                <td>
                                    @php
                                        $rate = $stat->total > 0 ? round(($stat->approved / $stat->total) * 100, 1) : 0;
                                    @endphp
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" style="width: {{ $rate }}%">
                                            {{ $rate }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Submissions -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-secondary text-white">
            <i class="fas fa-history"></i> Recent Submissions
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Documents</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSubmissions as $submission)
                            <tr>
                                <td>{{ $submission->user->name ?? 'Unknown' }}</td>
                                <td>{{ $submission->documents ?? $submission->documents ?? 'N/A' }}</td>
                                <td>{{ $submission->department }}</td>
                                <td>
                                    <span class="badge 
                                        {{ $submission->status == 'Approved' ? 'bg-success' : 
                                           ($submission->status == 'Rejected' ? 'bg-danger' : 'bg-warning') }}">
                                        {{ $submission->status }}
                                    </span>
                                </td>
                                <td>{{ $submission->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No submissions found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Defense Schedules -->
    @if(isset($recentDefenseSchedules) && $recentDefenseSchedules->count() > 0)
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-calendar-alt"></i> Recent Defense Schedules
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Group</th>
                            <th>Defense Type</th>
                            <th>Department</th>
                            <th>Section</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentDefenseSchedules as $schedule)
                            <tr>
                                <td><strong>{{ $schedule->group_id }}</strong></td>
                                <td>{{ $schedule->defense_type }}</td>
                                <td>{{ $schedule->department }}</td>
                                <td>{{ $schedule->section }}</td>
                                <td>
                                    @if($schedule->defense_date && $schedule->defense_date != '1970-01-01')
                                        {{ \Carbon\Carbon::parse($schedule->defense_date)->format('M d, Y') }}
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                                <td>
                                    @if($schedule->start_time && $schedule->end_time)
                                        {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge 
                                        {{ $schedule->status == 'Passed' ? 'bg-success' : 
                                           ($schedule->status == 'Failed' ? 'bg-danger' : 
                                           ($schedule->status == 'Scheduled' ? 'bg-info' : 'bg-warning')) }}">
                                        {{ $schedule->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Export -->
    <div class="text-end no-print">
        <button class="btn btn-outline-primary" onclick="window.print()">
            <i class="fas fa-print"></i> Print / Export Report
        </button>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Status Chart
    new Chart(document.getElementById("statusChart"), {
        type: "bar",
        data: {
            labels: ["Approved", "Rejected", "Pending"],
            datasets: [{
                label: "Submissions",
                data: [{{ $approvedSubmissions }}, {{ $rejectedSubmissions }}, {{ $pendingSubmissions }}],
                backgroundColor: ["#198754", "#dc3545", "#ffc107"]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Defense Chart
    new Chart(document.getElementById("defenseChart"), {
        type: "doughnut",
        data: {
            labels: ["Passed", "Failed", "Scheduled", "Pending"],
            datasets: [{
                data: [{{ $passedDefenses ?? 0 }}, {{ $failedDefenses ?? 0 }}, {{ $scheduledDefenses ?? 0 }}, {{ $pendingDefenses ?? 0 }}],
                backgroundColor: ["#198754", "#dc3545", "#0d6efd", "#ffc107"]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Department Chart
    new Chart(document.getElementById("departmentChart"), {
        type: "pie",
        data: {
            labels: [
                @foreach($departmentStats as $stat)
                    "{{ $stat->department }}",
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach($departmentStats as $stat)
                        {{ $stat->total }},
                    @endforeach
                ],
                backgroundColor: [
                    "#0d6efd", "#20c997", "#6f42c1", "#fd7e14", "#dc3545", 
                    "#198754", "#ffc107", "#6c757d", "#e83e8c", "#17a2b8"
                ]
            }]
        },
        options: {
            responsive: true
        }
    });
</script>
@endsection