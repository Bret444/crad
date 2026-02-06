@extends('layouts.student')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-3">
                <div class="card-header {{ $notification->type == 'Approved' ? 'bg-success' : ($notification->type == 'Rejected' ? 'bg-danger' : 'bg-info') }} text-white">
                    <div class="d-flex align-items-center">
                        <i class="bi {{ $notification->type == 'Approved' ? 'bi-check-circle' : ($notification->type == 'Rejected' ? 'bi-x-circle' : 'bi-info-circle') }} fs-3 me-3"></i>
                        <div>
                            <h5 class="mb-0">Notification Details</h5>
                            <small>{{ $notification->created_at->format('F d, Y - h:i A') }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Message</h6>
                        <p class="fs-5 mb-0">{{ $notification->message }}</p>
                    </div>

                    @if($notification->submission)
                        <hr>
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Submission Details</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <small class="text-muted d-block mb-1">Document Title</small>
                                        <strong>{{ $notification->submission->documents ?? 'N/A' }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <small class="text-muted d-block mb-1">Status</small>
                                        <span class="badge {{ $notification->submission->status == 'Approved' ? 'bg-success' : ($notification->submission->status == 'Rejected' ? 'bg-danger' : 'bg-warning') }} fs-6">
                                            {{ $notification->submission->status }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <small class="text-muted d-block mb-1">Department</small>
                                        <strong>{{ $notification->submission->department ?? 'N/A' }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <small class="text-muted d-block mb-1">Group Number</small>
                                        <strong>{{ $notification->submission->group_no ?? 'N/A' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($notification->submission->feedback)
                            <hr>
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Feedback</h6>
                                <div class="alert alert-info">
                                    <i class="bi bi-chat-left-quote me-2"></i>
                                    {{ $notification->submission->feedback }}
                                </div>
                            </div>
                        @endif
                    @endif

                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ url('/std-dashboard') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                        </a>
                        @if($notification->submission && $notification->submission->file_path)
                            <a href="{{ Storage::url($notification->submission->file_path) }}" target="_blank" class="btn btn-primary">
                                <i class="bi bi-file-earmark-pdf me-2"></i>View Document
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
