@extends('layouts.student')

@push('styles')
<style>
    /* v4.0 - Ultra Modern Design */
    :root {
        --bs-primary-dark: #284b9a;
        --bs-primary-light: #3B71CA;
        --gradient-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --gradient-accent: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        background-attachment: fixed !important;
        font-family: 'Poppins', sans-serif !important;
        min-height: 100vh !important;
    }

    h1, h2, h3, h4, h5, h6, .fw-bold { 
        font-weight: 700 !important;
    }
    
    .main-title {
        background: var(--gradient-bg);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        position: relative;
    }
    
    .main-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 4px;
        background: var(--gradient-bg);
        border-radius: 2px;
    }
    
    .stage-box {
        border: none !important;
        padding: 45px !important; 
        margin-bottom: 50px !important; 
        border-radius: 30px !important; 
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(30px) saturate(180%) !important;
        box-shadow: 
            0 35px 100px rgba(0, 0, 0, 0.4),
            0 0 0 1px rgba(255, 255, 255, 0.2) inset,
            0 0 60px rgba(102, 126, 234, 0.3) !important;
        transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
        position: relative !important;
        overflow: visible !important;
        animation: slideInUp 0.8s ease-out !important;
    }
    
    @keyframes slideInUp {
        from { opacity: 0; transform: translateY(50px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .stage-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 8px;
        background: var(--gradient-bg);
        border-radius: 30px 30px 0 0;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.6);
    }
    
    .stage-box:hover {
        transform: translateY(-20px) scale(1.02) !important;
        box-shadow: 
            0 50px 140px rgba(0, 0, 0, 0.5),
            0 0 0 1px rgba(255, 255, 255, 0.3) inset,
            0 0 80px rgba(102, 126, 234, 0.5) !important;
    }

    .stage-title {
        text-align: center;
        font-weight: 800 !important;
        margin-bottom: 45px;
        font-size: 3rem !important; 
        background: var(--gradient-bg);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        position: relative;
        letter-spacing: -1px;
        animation: fadeIn 1s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .stage-title::after {
        content: '';
        position: absolute;
        bottom: -18px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 5px;
        background: var(--gradient-bg);
        border-radius: 3px;
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.6);
    }

    .defense-section {
        border: none;
        padding: 2rem;
        margin-bottom: 2rem;
        border-radius: 15px;
        background: linear-gradient(145deg, #f8faff 0%, #e8f2ff 100%);
        box-shadow: inset 0 2px 10px rgba(59, 113, 202, 0.1);
        position: relative;
    }
    
    .defense-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--bs-primary-light), transparent);
        border-radius: 15px 15px 0 0;
    }

    .section-header {
        color: var(--bs-primary-dark);
        font-size: 1.3rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .section-header::before {
        content: '';
        width: 4px;
        height: 25px;
        background: var(--bs-primary-light);
        border-radius: 2px;
    }

    .document-item {
        border: none !important;
        padding: 22px 28px !important; 
        margin-bottom: 18px !important;
        border-radius: 18px !important;
        background: linear-gradient(145deg, #ffffff, #f5f7fa) !important;
        box-shadow: 
            0 10px 30px rgba(0, 0, 0, 0.12),
            inset 0 1px 0 rgba(255, 255, 255, 1) !important;
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
        position: relative !important;
        overflow: hidden !important;
    }
    
    .document-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 6px;
        background: var(--gradient-bg);
        transform: scaleY(0);
        transition: transform 0.5s ease;
    }
    
    .document-item:hover {
        background: linear-gradient(145deg, #ffffff, #ffffff) !important;
        transform: translateX(12px) scale(1.03) !important;
        box-shadow: 
            0 20px 50px rgba(0, 0, 0, 0.18),
            inset 0 1px 0 rgba(255, 255, 255, 1) !important;
    }
    
    .document-item:hover::before {
        transform: scaleY(1);
    }

    .document-title-link {
        cursor: pointer;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #2c3e50;
        text-decoration: none;
        font-size: 1.1rem;
    }
    
    .document-title-link:hover {
        color: var(--bs-primary-dark);
    }
    
    .document-title-link i {
        transition: all 0.3s ease;
        color: var(--bs-primary-light);
        font-size: 1.2rem;
    }

    .document-title-link[aria-expanded="true"] i {
        transform: rotate(180deg);
        color: var(--bs-primary-dark);
    }
    
    .upload-form-content {
        padding-top: 1.5rem;
        border-top: 2px dashed rgba(59, 113, 202, 0.3);
        margin-top: 1.5rem;
        background: rgba(248, 250, 255, 0.5);
        border-radius: 8px;
        padding: 1.5rem;
    }
    
    .form-label-hidden {
        display: none;
    }
    
    .document-divider {
        height: 2px;
        background: linear-gradient(to right, transparent, rgba(59, 113, 202, 0.3), transparent);
        margin: 1.5rem 0;
        border: none;
        border-radius: 1px;
    }
    
    .btn-primary {
        background: var(--gradient-bg);
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(59, 113, 202, 0.3);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(59, 113, 202, 0.4);
        background: linear-gradient(135deg, #5a6fd8 0%, #6b5b95 100%);
    }
    
    .form-control {
        border: 2px solid rgba(59, 113, 202, 0.2);
        border-radius: 8px;
        padding: 12px 15px;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
    }
    
    .form-control:focus {
        border-color: var(--bs-primary-light);
        box-shadow: 0 0 0 0.2rem rgba(59, 113, 202, 0.25);
        background: white;
    }
    
    .input-group {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    
    <div class="stage-box p-4 mt-4 shadow-sm mb-5">
        <div class="stage-title text-center fw-bold fs-5">Submission</div> 
        
        <h5 class="section-header fw-bold mb-3 text-primary">Pre-Oral Defense</h5>
        <div class="defense-section p-3 p-md-4">
            <div class="accordion accordion-flush" id="preOralAccordion">
                @foreach(['Research Title Proposal', 'Research Forum', 'Clearance', 'Manuscript Chapter 1-3'] as $docTitle)
                    @php
                        $docUniqueID = 'pre-oral-' . strtolower(str_replace(' ', '-', $docTitle));
                        $formTargetID = 'form-collapse-submission-' . $docUniqueID;
                    @endphp
                    
                    <div class="accordion-item document-item">
                        <h2 class="accordion-header" id="heading-{{ $docUniqueID }}">
                            <button class="accordion-button document-title-link collapsed" type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#{{ $formTargetID }}" 
                                aria-expanded="false" 
                                aria-controls="{{ $formTargetID }}">
                                <span>{{ $docTitle }}</span>
                            </button>
                        </h2>
                        <div id="{{ $formTargetID }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $docUniqueID }}" data-bs-parent="#preOralAccordion">
                            <div class="accordion-body upload-form-content">
                                @if($docTitle === 'Manuscript Chapter 1-3')
                                    <form class="upload-form" action="{{ route('student.submissions.store') }}" method="POST" enctype="multipart/form-data" data-document="{{ $docTitle }}" data-type="link">
                                        @csrf
                                        <input type="hidden" name="documents" value="{{ $docTitle }}">
                                        <input type="hidden" name="defense_type" value="Pre-Oral">
                                        <input type="hidden" name="department" value="{{ auth()->user()->department ?? 'N/A' }}">
                                        <input type="hidden" name="cluster" value="{{ auth()->user()->section_cluster ?? 4101 }}">
                                        <input type="hidden" name="group_no" value="{{ auth()->user()->group_no ?? 0 }}">
                                        <label class="form-label mb-2">Google Drive Link</label>
                                        <div class="input-group mb-3">
                                            <input type="url" name="drive_link" class="form-control" placeholder="https://drive.google.com/...">
                                        </div>
                                        <label class="form-label mb-2">Or Upload File</label>
                                        <div class="input-group mb-3">
                                            <input type="file" name="file" class="form-control">
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="terms-{{ $docUniqueID }}" required>
                                            <label class="form-check-label" for="terms-{{ $docUniqueID }}">
                                                I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
                                            </label>
                                        </div>
                                        <button class="btn btn-primary" type="submit">Submit</button>
                                    </form>
                                @else
                                    <form class="upload-form" action="{{ route('student.submissions.store') }}" method="POST" enctype="multipart/form-data" data-document="{{ $docTitle }}">
                                        @csrf
                                        <input type="hidden" name="documents" value="{{ $docTitle }}">
                                        <input type="hidden" name="defense_type" value="Pre-Oral">
                                        <input type="hidden" name="department" value="{{ auth()->user()->department ?? 'N/A' }}">
                                        <input type="hidden" name="cluster" value="{{ auth()->user()->section_cluster ?? 4101 }}">
                                        <input type="hidden" name="group_no" value="{{ auth()->user()->group_no ?? 0 }}">
                                        <label class="form-label mb-2 form-label-hidden">Upload a File</label>
                                        <div class="input-group mb-3">
                                            <input type="file" name="file" class="form-control" required>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="terms-{{ $docUniqueID }}" required>
                                            <label class="form-check-label" for="terms-{{ $docUniqueID }}">
                                                I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
                                            </label>
                                        </div>
                                        <button class="btn btn-primary" type="submit">Upload</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <div class="stage-box p-4 mt-4 shadow-sm mb-5">
        <div class="stage-title text-center fw-bold fs-5">Final Defense</div> 
        
        <h5 class="section-header fw-bold mb-3 text-primary">Final Defense</h5>
        <div class="defense-section p-3 p-md-4">
            <div class="accordion accordion-flush" id="finalDefenseAccordion">
                @foreach(['Summary of Corrections', 'Clearance', 'Manuscript Chapter 1-5'] as $docTitle)
                    @php
                        $docUniqueID = 'final-defense-' . strtolower(str_replace(' ', '-', $docTitle));
                        $formTargetID = 'form-collapse-finaldefense-' . $docUniqueID;
                    @endphp
                    
                    <div class="accordion-item document-item">
                        <h2 class="accordion-header" id="heading-{{ $docUniqueID }}">
                            <button class="accordion-button document-title-link collapsed" type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#{{ $formTargetID }}" 
                                aria-expanded="false" 
                                aria-controls="{{ $formTargetID }}">
                                <span>{{ $docTitle }}</span>
                            </button>
                        </h2>
                        <div id="{{ $formTargetID }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $docUniqueID }}" data-bs-parent="#finalDefenseAccordion">
                            <div class="accordion-body upload-form-content">
                                @if($docTitle === 'Manuscript Chapter 1-5')
                                    <form class="upload-form" action="{{ route('student.submissions.store') }}" method="POST" enctype="multipart/form-data" data-document="{{ $docTitle }}" data-type="link">
                                        @csrf
                                        <input type="hidden" name="documents" value="{{ $docTitle }}">
                                        <input type="hidden" name="defense_type" value="Final Defense">
                                        <input type="hidden" name="department" value="{{ auth()->user()->department ?? 'N/A' }}">
                                        <input type="hidden" name="cluster" value="{{ auth()->user()->section_cluster ?? 4101 }}">
                                        <input type="hidden" name="group_no" value="{{ auth()->user()->group_no ?? 0 }}">
                                        <label class="form-label mb-2">Google Drive Link</label>
                                        <div class="input-group mb-3">
                                            <input type="url" name="drive_link" class="form-control" placeholder="https://drive.google.com/...">
                                        </div>
                                        <label class="form-label mb-2">Or Upload File</label>
                                        <div class="input-group mb-3">
                                            <input type="file" name="file" class="form-control">
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="terms-{{ $docUniqueID }}" required>
                                            <label class="form-check-label" for="terms-{{ $docUniqueID }}">
                                                I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
                                            </label>
                                        </div>
                                        <button class="btn btn-primary" type="submit">Submit</button>
                                    </form>
                                @else
                                    <form class="upload-form" action="{{ route('student.submissions.store') }}" method="POST" enctype="multipart/form-data" data-document="{{ $docTitle }}">
                                        @csrf
                                        <input type="hidden" name="documents" value="{{ $docTitle }}">
                                        <input type="hidden" name="defense_type" value="Final Defense">
                                        <input type="hidden" name="department" value="{{ auth()->user()->department ?? 'N/A' }}">
                                        <input type="hidden" name="cluster" value="{{ auth()->user()->section_cluster ?? 4101 }}">
                                        <input type="hidden" name="group_no" value="{{ auth()->user()->group_no ?? 0 }}">
                                        <label class="form-label mb-2 form-label-hidden">Upload a File</label>
                                        <div class="input-group mb-3">
                                            <input type="file" name="file" class="form-control" required>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="terms-{{ $docUniqueID }}" required>
                                            <label class="form-check-label" for="terms-{{ $docUniqueID }}">
                                                I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
                                            </label>
                                        </div>
                                        <button class="btn btn-primary" type="submit">Upload</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <h5 class="section-header fw-bold mb-3 mt-4 text-primary">Re-Defense</h5>
        <div class="defense-section p-3 p-md-4">
            <div class="accordion accordion-flush" id="finalDefenseReDefenseAccordion">
                @php
                    $docTitle = 'Clearance';
                    $docUniqueID = 'final-re-defense-clearance';
                    $formTargetID = 'form-collapse-finaldefense-' . $docUniqueID;
                @endphp
                <div class="accordion-item document-item">
                    <h2 class="accordion-header" id="heading-{{ $docUniqueID }}">
                        <button class="accordion-button document-title-link collapsed" type="button" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#{{ $formTargetID }}" 
                            aria-expanded="false" 
                            aria-controls="{{ $formTargetID }}">
                            <span>{{ $docTitle }}</span>
                        </button>
                    </h2>
                    <div id="{{ $formTargetID }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $docUniqueID }}" data-bs-parent="#finalDefenseReDefenseAccordion">
                        <div class="accordion-body upload-form-content">
                            <form class="upload-form" action="{{ route('student.submissions.store') }}" method="POST" enctype="multipart/form-data" data-document="{{ $docTitle }}">
                                @csrf
                                <input type="hidden" name="documents" value="{{ $docTitle }}">
                                <input type="hidden" name="defense_type" value="Final Defense Re-Defense">
                                <input type="hidden" name="department" value="{{ auth()->user()->department ?? 'N/A' }}">
                                <input type="hidden" name="cluster" value="{{ auth()->user()->section_cluster ?? 4101 }}">
                                <input type="hidden" name="group_no" value="{{ auth()->user()->group_no ?? 0 }}">
                                <label class="form-label mb-2 form-label-hidden">Upload a File</label>
                                <div class="input-group mb-3">
                                    <input type="file" name="file" class="form-control" required>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="terms-{{ $docUniqueID }}" required>
                                    <label class="form-check-label" for="terms-{{ $docUniqueID }}">
                                        I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
                                    </label>
                                </div>
                                <button class="btn btn-primary" type="submit">Upload</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($submissions) && $submissions->count() > 0)
    <div class="stage-box p-4 mt-4 shadow-sm">
        <div class="stage-title">Document Status</div>
        <div class="defense-section">
            @foreach($submissions as $submission)
                @php
                    $statusClass = match($submission->status) {
                        'Approved' => 'bg-success',
                        'Rejected' => 'bg-danger',
                        default => 'bg-warning text-dark',
                    };
                @endphp
                <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                    <div>
                        <h6 class="mb-1">{{ $submission->documents }} <small class="text-muted">({{ $submission->defense_type ?? 'N/A' }})</small></h6>
                        <small class="text-muted d-block">Submitted: {{ $submission->created_at->format('Y-m-d') }}</small>
                        <small class="text-muted d-block">Approval: {{ $submission->updated_at->format('Y-m-d') ?? 'N/A' }}</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge {{ $statusClass }} status-badge">{{ $submission->status }}</span>
                        @if($submission->status === 'Rejected')
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#resubmitModal{{ $submission->id }}">
                                <i class="bi bi-arrow-repeat"></i> Resubmit
                            </button>
                        @endif
                    </div>
                </div>
                
                @if($submission->status === 'Rejected')
                    <div class="modal fade" id="resubmitModal{{ $submission->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4">
                                <form action="{{ route('student.files.resubmit', $submission->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header bg-primary text-white rounded-top-4">
                                        <h5 class="modal-title"><i class="bi bi-arrow-repeat"></i> Revise & Resubmit</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Proposal Title</label>
                                            <input type="text" name="documents" class="form-control rounded-3" value="{{ $submission->documents }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Upload New File</label>
                                            <input type="file" name="file" class="form-control rounded-3" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary rounded-3">Resubmit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Document Submission Terms</h6>
                <p>By submitting documents through this system, you agree to the following terms:</p>
                <ul>
                    <li>All submitted documents must be original work or properly cited</li>
                    <li>Documents must be in acceptable formats (PDF, DOC, DOCX)</li>
                    <li>File size must not exceed 10MB per document</li>
                    <li>Submitted documents become part of your academic record</li>
                    <li>False or plagiarized submissions may result in academic penalties</li>
                    <li>You are responsible for ensuring document quality and completeness</li>
                    <li>The institution reserves the right to verify document authenticity</li>
                </ul>
                <h6>Privacy and Data Protection</h6>
                <p>Your submitted documents will be:</p>
                <ul>
                    <li>Stored securely in our academic database</li>
                    <li>Accessible only to authorized academic personnel</li>
                    <li>Used solely for academic evaluation purposes</li>
                    <li>Retained according to institutional record-keeping policies</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirm Submission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to submit this document?</p>
                <p><strong>Document:</strong> <span id="confirmDocumentName"></span></p>
                <p><strong>File:</strong> <span id="confirmFileName"></span></p>
                <p class="text-warning"><small>Once submitted, you cannot modify this submission unless it is rejected.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmSubmitBtn">Yes, Submit</button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="successModalLabel"><i class="bi bi-check-circle"></i> Upload Successful</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                <p class="mt-3 fs-5">Your document has been submitted successfully!</p>
                <p class="text-muted">The coordinator will review your submission.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    @if(session('success'))
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
        
        // Reload page after modal is closed to show updated submissions
        document.getElementById('successModal').addEventListener('hidden.bs.modal', function () {
            window.location.reload();
        });
    @endif

    let currentForm = null;

    function attachFormEventListeners() {
        document.querySelectorAll('.upload-form').forEach(form => {
            form.removeEventListener('submit', handleFormSubmit);
            form.addEventListener('submit', handleFormSubmit);
        });
    }

    function handleFormSubmit(e) {
        e.preventDefault();
        
        const documentTitle = e.target.getAttribute('data-document');
        const isLinkForm = e.target.getAttribute('data-type') === 'link';
        const termsCheckbox = e.target.querySelector('input[type="checkbox"]');
        
        let inputValue = '';
        if (isLinkForm) {
            const linkInput = e.target.querySelector('input[name="drive_link"]');
            const fileInput = e.target.querySelector('input[type="file"]');
            const linkValue = linkInput.value;
            const file = fileInput.files[0];
            
            if (!linkValue && !file) {
                alert('Please enter a Google Drive link or upload a file.');
                return false;
            }
            inputValue = linkValue || file.name;
        } else {
            const fileInput = e.target.querySelector('input[type="file"]');
            const file = fileInput.files[0];
            if (!file) {
                alert('Please choose a file to upload.');
                return false;
            }
            inputValue = file.name;
        }

        if (!termsCheckbox.checked) {
            alert('Please accept the Terms and Conditions.');
            return false;
        }

        currentForm = e.target;
        
        document.getElementById('confirmDocumentName').textContent = documentTitle;
        document.getElementById('confirmFileName').textContent = inputValue;
        
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
        confirmModal.show();
    }

    document.getElementById('confirmSubmitBtn').addEventListener('click', function() {
        if (currentForm) {
            const confirmModal = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
            confirmModal.hide();
            
            currentForm.submit();
        }
    });

    attachFormEventListeners();
});
</script>
@endsection