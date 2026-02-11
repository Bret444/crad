@extends('layouts.student')
{{-- Updated: Panel filtering by assignment_id --}}

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .dashboard-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: none;
        border-radius: 15px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }
    
    .card-header {
        border: none;
        border-radius: 15px 15px 0 0 !important;
        padding: 1.25rem;
        font-weight: 600;
    }
    
    .progress {
        height: 20px;
        border-radius: 10px;
        background: rgba(0, 0, 0, 0.1);
    }
    
    .progress-bar {
        border-radius: 10px;
        background: linear-gradient(90deg, #28a745, #20c997);
    }
    
    .step-card {
        border: none;
        border-radius: 10px;
        transition: all 0.2s ease;
    }
    
    .step-card.completed {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .step-card.pending {
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
    }
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .welcome-header {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        color: white;
        padding: 2rem;
        margin-bottom: 2rem;
        text-align: center;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Welcome Header -->
    <div class="welcome-header">
        <h2 class="mb-2">Welcome to Your Research Dashboard</h2>
        <p class="mb-0">Track your research progress and manage your submissions</p>
        <!-- DEBUG: Group {{ $group->group_no }} - Assignment ID: {{ $group->assignment_id ?? 'NULL' }} -->
    </div>

    <div class="row g-4">
        <!-- Group Details -->
        <div class="col-md-4">
            <div class="card dashboard-card h-100">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-users me-2"></i> Your Group Details
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="fas fa-hashtag text-primary fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Group {{ $group->group_no ?? 'N/A' }}</h5>
                            <small class="text-muted d-block">{{ $group->department ?? 'N/A' }}</small>
                            <small class="text-muted d-block">{{ $group->cluster ?? 'N/A' }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Adviser & Panel -->
        <div class="col-md-4">
            <div class="card dashboard-card h-100">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-user-tie me-2"></i> Adviser & Panel
                </div>
                <div class="card-body">
                    <!-- Adviser Section -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-user-graduate text-info me-2"></i>
                            <strong>Adviser:</strong>
                        </div>
                        @if($group->adviser)
                            <div class="ms-4">
                                <p class="mb-1 fw-semibold">{{ $group->adviser->name }}</p>
                                @if($group->adviser->expertise)
                                    <small class="text-muted">{{ $group->adviser->expertise }}</small>
                                @endif
                            </div>
                        @else
                            <p class="ms-4 text-muted mb-0">Not assigned yet</p>
                        @endif
                    </div>
                    
                    <!-- Panel Members Section -->
                    <div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-users text-info me-2"></i>
                            <strong>Panel Members:</strong>
                        </div>
                        @if($group->assignment_id && $group->panels && $group->panels->count() > 0)
                            <div class="ms-4">
                                @foreach($group->panels as $panel)
                                    <div class="d-flex justify-content-between align-items-start mb-2 p-2 bg-light rounded">
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold">{{ $panel->name }}</div>
                                            @if($panel->expertise)
                                                <small class="text-muted d-block">{{ $panel->expertise }}</small>
                                            @endif
                                        </div>
                                        <span class="badge bg-secondary ms-2">{{ $panel->role }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="ms-4 text-muted mb-0">No panel members assigned yet</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Research Status -->
        <div class="col-md-4">
            <div class="card dashboard-card h-100">
                <div class="card-header bg-warning text-dark">
                    <i class="fas fa-tasks me-2"></i> Research Status
                </div>
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <div class="mb-3">
                        <i class="fas fa-clipboard-check fs-1 text-warning mb-3"></i>
                        <h6 class="mb-3">Research Title Status</h6>
                    </div>
                    <span class="status-badge
                        @if($submissionStatus === 'APPROVED') bg-success text-white
                        @elseif($submissionStatus === 'REJECTED') bg-danger text-white
                        @else bg-secondary text-white
                        @endif">
                        {{ strtoupper($submissionStatus ?? 'PENDING') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Research Progress -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card dashboard-card">
                <div class="card-header bg-dark text-white">
                    <i class="fas fa-chart-line me-2"></i> Research Progress
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h5 class="mb-3">Progress: {{ $progress }}/6 Steps Completed</h5>
                        <div class="progress mb-3">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: {{ ($progress/6)*100 }}%" 
                                 aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="6">
                                {{ round(($progress/6)*100) }}%
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-3">
                        @php
                            $stepNames = [
                                1 => 'Proposal',
                                2 => 'Research Forum', 
                                3 => 'Clearance 1',
                                4 => 'Pre-Oral',
                                5 => 'Clearance 2',
                                6 => 'Final Defense'
                            ];
                        @endphp
                        
                        @for($i = 1; $i <= 6; $i++)
                            <div class="col-md-4 col-sm-6">
                                <div class="card step-card {{ ($progressSteps[$i] ?? false) ? 'completed' : 'pending' }}">
                                    <div class="card-body py-3 text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            @if($progressSteps[$i] ?? false)
                                                <i class="fas fa-check-circle me-2 fs-5"></i>
                                            @else
                                                <i class="far fa-circle me-2 fs-5 text-muted"></i>
                                            @endif
                                            <span class="fw-semibold">{{ $stepNames[$i] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
