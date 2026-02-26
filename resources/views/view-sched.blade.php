@extends('layouts.student')

@section('styles')
<style>
    .calendar-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 2rem;
        color: white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    .calendar-date {
        font-size: 4rem;
        font-weight: 700;
        line-height: 1;
    }
    .calendar-month {
        font-size: 1.5rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 2px;
    }
    .calendar-year {
        font-size: 1.2rem;
        opacity: 0.9;
    }
    .time-badge {
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 600;
        display: inline-block;
        margin-top: 1rem;
    }
</style>
@endsection

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-5 fw-bold text-primary">
        <i class="fas fa-calendar-alt"></i> Your Defense Schedules
    </h2>

    @if($schedules && $schedules->count() > 0)
        @foreach($schedules as $schedule)
            <div class="card border-0 shadow-lg mb-4 rounded-3">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            @if($schedule->defense_date)
                                @php
                                    // Parse date without timezone conversion
                                    $date = \Carbon\Carbon::parse($schedule->defense_date)->startOfDay();
                                @endphp
                                <div class="calendar-card text-center">
                                    <div class="calendar-month">{{ $date->format('M') }}</div>
                                    <div class="calendar-date">{{ $date->format('d') }}</div>
                                    <div class="calendar-year">{{ $date->format('Y') }}</div>
                                    @if($schedule->start_time && $schedule->end_time)
                                        <div class="time-badge">
                                            <i class="fas fa-clock"></i> {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="calendar-card text-center">
                                    <div class="calendar-month">TBD</div>
                                    <div class="calendar-date">--</div>
                                    <div class="calendar-year">----</div>
                                    <div class="time-badge">
                                        <i class="fas fa-clock"></i> Time TBD
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-8">
                            <h4 class="mb-3">
                                <i class="fas fa-graduation-cap text-primary"></i> 
                                {{ $schedule->defense_type }}
                                @if($schedule->defense_type === 'REDEFENSE' && $schedule->original_defense_type)
                                    <span class="badge bg-warning">{{ $schedule->original_defense_type }}</span>
                                @endif
                            </h4>
                            
                            <div class="mb-3">
                                <span class="badge bg-info text-dark me-2">
                                    <i class="fas fa-building"></i> {{ $schedule->department }}
                                </span>
                                <span class="badge bg-secondary me-2">
                                    <i class="fas fa-layer-group"></i> Cluster: {{ $schedule->section }}
                                </span>
                                <span class="badge bg-warning text-dark me-2">
                                    <i class="fas fa-users"></i> Group {{ $schedule->group_id }}
                                </span>
                                <span class="badge bg-{{ 
                                    $schedule->status === 'Passed' ? 'success' : 
                                    ($schedule->status === 'Failed' ? 'danger' : 
                                    ($schedule->status === 'Re-defense' ? 'warning' : 'secondary')) 
                                }}">
                                    <i class="fas fa-info-circle"></i> {{ $schedule->status ?? 'Pending' }}
                                </span>
                            </div>

                            @php
                                $panelData = $schedule->panel_data ?? [];
                                if (is_string($panelData)) {
                                    $panelData = json_decode($panelData, true) ?? [];
                                }
                            @endphp
                            
                            @if(!empty($panelData))
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <strong><i class="fas fa-user-tie text-success"></i> Adviser:</strong><br>
                                            <span class="ms-4">{{ $panelData['adviser'] ?? 'Not assigned' }}</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <strong><i class="fas fa-user-check text-primary"></i> Chairperson:</strong><br>
                                            <span class="ms-4">{{ $panelData['chairperson'] ?? 'Not assigned' }}</span>
                                        </p>
                                    </div>
                                    <div class="col-12">
                                        <p class="mb-0">
                                            <strong><i class="fas fa-users text-info"></i> Members:</strong><br>
                                            <span class="ms-4">{{ $panelData['members'] ?? 'Not assigned' }}</span>
                                        </p>
                                    </div>
                                </div>
                            @elseif($assignment)
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <strong><i class="fas fa-user-tie text-success"></i> Adviser:</strong><br>
                                            <span class="ms-4">{{ $assignment->adviser->name ?? 'Not assigned' }}</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <strong><i class="fas fa-user-check text-primary"></i> Chairperson:</strong><br>
                                            <span class="ms-4">
                                                @if($assignment->assignmentPanels->where('role', 'Chairperson')->first())
                                                    {{ $assignment->assignmentPanels->where('role', 'Chairperson')->first()->panel->name }}
                                                @else
                                                    Not assigned
                                                @endif
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-12">
                                        <p class="mb-0">
                                            <strong><i class="fas fa-users text-info"></i> Members:</strong><br>
                                            <span class="ms-4">
                                                @php
                                                    $members = $assignment->assignmentPanels->where('role', 'Member')->pluck('panel.name')->filter();
                                                @endphp
                                                {{ $members->isNotEmpty() ? $members->implode(', ') : 'Not assigned' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> Panel information not available for this schedule.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="alert alert-info text-center shadow-sm">
            <i class="fas fa-info-circle"></i> No defense schedules available yet.
        </div>
    @endif
</div>
@endsection