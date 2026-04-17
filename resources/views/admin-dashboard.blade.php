@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-4 fw-bold text-primary">
        <i class="fas fa-tachometer-alt"></i> Admin Dashboard
    </h2>
    
    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="background-color: #3b82f6;">
                <div class="card-body text-white text-center py-3">
                    <i class="fas fa-file-alt fa-2x mb-2"></i>
                    <h4 class="fw-bold mb-1">{{ $totalSubmissions }}</h4>
                    <p class="mb-0 small">Total Submissions</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="background-color: #f59e0b;">
                <div class="card-body text-white text-center py-3">
                    <i class="fas fa-clock fa-2x mb-2"></i>
                    <h4 class="fw-bold mb-1">{{ $pendingSubmissions }}</h4>
                    <p class="mb-0 small">Pending Review</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="background-color: #10b981;">
                <div class="card-body text-white text-center py-3">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <h4 class="fw-bold mb-1">{{ $approvedSubmissions }}</h4>
                    <p class="mb-0 small">Approved</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="background-color: #ef4444;">
                <div class="card-body text-white text-center py-3">
                    <i class="fas fa-times-circle fa-2x mb-2"></i>
                    <h4 class="fw-bold mb-1">{{ $rejectedSubmissions }}</h4>
                    <p class="mb-0 small">Rejected</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-3">
        <!-- Bar Chart -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-success text-white fw-bold">
                    <i class="fas fa-chart-bar"></i> Submissions by Department
                </div>
                <div class="card-body">
                    <canvas id="departmentChart" height="100"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Defense Chart -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-warning text-white fw-bold">
                    <i class="fas fa-chart-pie"></i> Defense Status
                </div>
                <div class="card-body">
                    <canvas id="defenseChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-3 mt-2">
        <!-- Department Statistics -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-info text-white fw-bold">
                    <i class="fas fa-building"></i> Department Overview
                </div>
                <div class="card-body">
                    @forelse($departmentStats as $dept)
                    <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded" style="background-color: #f8f9fa;">
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $dept->department }}</h6>
                        </div>
                        <div>
                            <span class="badge bg-primary">{{ $dept->count }} submissions</span>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted text-center my-4">No department data available</p>
                    @endforelse
                </div>
            </div>
        </div>
        <!-- Recent Submissions -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="fas fa-history"></i> Recent Submissions
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Student</th>
                                    <th>Documents</th>
                                    <th>Department</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentSubmissions as $submission)
                                <tr>
                                    <td>{{ $submission->user->name ?? 'N/A' }}</td>
                                    <td>{{ Str::limit($submission->documents, 30) }}</td>
                                    <td>{{ $submission->department }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($submission->status == 'Approved') bg-success
                                            @elseif($submission->status == 'Rejected') bg-danger
                                            @else bg-warning
                                            @endif">
                                            {{ $submission->status }}
                                        </span>
                                    </td>
                                    <td>{{ $submission->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.track-proposal') }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No submissions yet
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light text-center">
                    <a href="{{ route('admin.track-proposal') }}" class="btn btn-primary">
                        <i class="fas fa-list-ul me-1"></i>View All Submissions
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- Feedback Modal -->
        <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <form id="feedbackForm" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="feedbackModalLabel">Send Feedback</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <textarea name="feedback" class="form-control" rows="4" placeholder="Enter feedback..." required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Send Feedback</button>
                    </div>
                </div>
            </form>
          </div>
        </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Bar Chart
    const ctx = document.getElementById('departmentChart').getContext('2d');
    const departmentChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [@foreach($departmentStats as $dept)'{{ $dept->department }}',@endforeach],
            datasets: [{
                label: 'Submissions',
                data: [@foreach($departmentStats as $dept){{ $dept->count }},@endforeach],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(255, 205, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                    'rgba(255, 159, 64, 0.8)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 205, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Defense Status Pie Chart
    const defenseCtx = document.getElementById('defenseChart').getContext('2d');
    const defenseChart = new Chart(defenseCtx, {
        type: 'pie',
        data: {
            labels: ['Scheduled', 'Passed', 'Pending', 'Re-defense', 'Failed'],
            datasets: [{
                data: [
                    {{ $defenseStats['scheduled'] ?? 0 }},
                    {{ $defenseStats['passed'] ?? 0 }},
                    {{ $defenseStats['pending'] ?? 0 }},
                    {{ $defenseStats['redefense'] ?? 0 }},
                    {{ $defenseStats['failed'] ?? 0 }}
                ],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 205, 86, 0.8)',
                    'rgba(255, 159, 64, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 205, 86, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(239, 68, 68, 1)'
                ],
                borderWidth: 1
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

    // Feedback Modal
    var feedbackModal = document.getElementById('feedbackModal');
    var feedbackForm = document.getElementById('feedbackForm');

    feedbackModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; 
        var fileId = button.getAttribute('data-id'); 

        feedbackForm.action = "/admin/submission/" + fileId + "/feedback";
    });
});
</script>
@endsection
