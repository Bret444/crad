@extends('layouts.coordinator')

@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-4 fw-bold text-primary">
        <i class="fas fa-tachometer-alt"></i> Coordinator Dashboard
    </h2>
    
    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="background-color: #3b82f6;">
                <div class="card-body text-white text-center py-3">
                    <i class="fas fa-user-tie fa-2x mb-2"></i>
                    <h4 class="fw-bold mb-1">{{ $totalAdvisers }}</h4>
                    <p class="mb-0 small">Total Advisers</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="background-color: #eab308;">
                <div class="card-body text-white text-center py-3">
                    <i class="fas fa-users fa-2x mb-2"></i>
                    <h4 class="fw-bold mb-1">{{ $totalPanels }}</h4>
                    <p class="mb-0 small">Panel Members</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="background-color: #10b981;">
                <div class="card-body text-white text-center py-3">
                    <i class="fas fa-user-friends fa-2x mb-2"></i>
                    <h4 class="fw-bold mb-1">{{ $totalGroups }}</h4>
                    <p class="mb-0 small">Student Groups</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="background-color: #f59e0b;">
                <div class="card-body text-white text-center py-3">
                    <i class="fas fa-file-alt fa-2x mb-2"></i>
                    <h4 class="fw-bold mb-1">{{ $totalSubmissions }}</h4>
                    <p class="mb-0 small">Total Submissions</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Pending Submissions -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-warning text-dark fw-bold">
                    <i class="fas fa-clock"></i> Pending Submissions ({{ $pendingSubmissions }})
                </div>
                <div class="card-body">
                    @if($recentSubmissions->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentSubmissions as $submission)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $submission->documents }}</h6>
                                            <small class="text-muted">
                                                <i class="fas fa-user"></i> {{ $submission->user->name ?? 'N/A' }} | 
                                                <i class="fas fa-users"></i> Group {{ $submission->group_no }}
                                            </small>
                                        </div>
                                        <span class="badge bg-{{ 
                                            $submission->status === 'Approved' ? 'success' : 
                                            ($submission->status === 'Rejected' ? 'danger' : 'warning') 
                                        }}">
                                            {{ $submission->status }}
                                        </span>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar"></i> {{ $submission->created_at->format('M d, Y h:i A') }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('coordinator-title-proposal') }}" class="btn btn-sm btn-primary">
                                View All Submissions <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    @else
                        <p class="text-muted text-center my-4">No recent submissions</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Upcoming Defenses -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="fas fa-calendar-check"></i> Upcoming Defenses
                </div>
                <div class="card-body">
                    @if($upcomingDefenses->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($upcomingDefenses as $defense)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $defense->defense_type }}</h6>
                                            <small class="text-muted">
                                                <i class="fas fa-users"></i> Group {{ $defense->group_id }} | 
                                                <i class="fas fa-layer-group"></i> {{ $defense->section }}
                                            </small>
                                        </div>
                                        <span class="badge bg-info text-dark">
                                            {{ \Carbon\Carbon::parse($defense->defense_date)->format('M d') }}
                                        </span>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-clock"></i> {{ $defense->start_time }} - {{ $defense->end_time }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center my-4">No upcoming defenses scheduled</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
