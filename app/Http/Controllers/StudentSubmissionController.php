<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentSubmissionController extends Controller
{
    public function submission()
    {
        $user = auth()->user();
        $submissions = Submission::where('user_id', $user->id)->get();
        
        return view('submission', compact('submissions'));
    }

    public function index()
    {
        $userId = auth()->id();
        $user = auth()->user();
        $submissions = Submission::where('user_id', $userId)->get();
        
        $latestSubmission = $submissions->last();
        $submissionStatus = $latestSubmission ? $latestSubmission->status : 'PENDING';
        $progress = $submissions->count();
        
        // Fetch actual assignment data with adviser and panels
        $assignment = \App\Models\Assignment::with(['adviser', 'assignmentPanels.panel'])
            ->where('department', $user->department)
            ->where('section', $user->cluster ?? '4101')
            ->first();
        
        $group = (object) [
            'group_no' => $user->group_no ?? 'N/A', 
            'department' => $user->department ?? 'N/A',
            'adviser' => $assignment ? $assignment->adviser : (object)['name' => 'Not assigned'],
            'panels' => $assignment ? $assignment->assignmentPanels->map(function($ap) {
                return (object)[
                    'name' => $ap->panel->name ?? 'N/A',
                    'role' => $ap->role ?? 'N/A'
                ];
            }) : collect()
        ];
        
        $progressSteps = [
            1 => $submissions->count() >= 1,
            2 => false,
            3 => false,
            4 => false,
            5 => false,
            6 => false
        ];

        return view('std-dashboard', compact('submissions', 'submissionStatus', 'progress', 'group', 'progressSteps'));
    }


    public function store(Request $request)
    {
        // Check if document is Research Forum to allow images
        $isResearchForum = stripos($request->documents, 'research forum') !== false;
        
        $fileValidation = $isResearchForum 
            ? 'nullable|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120'
            : 'nullable|mimes:pdf,doc,docx|max:5120';
        
        $request->validate([
            'documents' => 'required|string|max:255',
            'file'  => $fileValidation,
            'drive_link' => 'nullable|url'
        ]);
        
        $user = auth()->user();
        $path = null;
        
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('submissions', 'public');
        }

        $submission = Submission::create([
            'user_id' => $user->id,
            'title' => $request->documents,
            'documents' => $request->documents,
            'defense_type' => $request->defense_type,
            'department' => $user->department ?? 'N/A',
            'cluster' => $user->cluster ?? 4101,
            'group_no' => $user->group_no ?? 0,
            'file_path' => $path ?? $request->drive_link,
            'status' => 'Pending',
            'submitted_by' => $user->id,
        ]);

        // Notify coordinator
        $coordinator = User::where('role', 'coordinator')
            ->where('department', $user->department)
            ->first();
        
        if ($coordinator) {
            \App\Models\Notification::create([
                'user_id' => $coordinator->id,
                'type' => 'submission',
                'message' => "New submission from Group {$user->group_no}: {$request->documents}",
                'submission_id' => $submission->id,
                'read' => false
            ]);
        }

        // Notify admin
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            \App\Models\Notification::create([
                'user_id' => $admin->id,
                'type' => 'submission',
                'message' => "New submission from Group {$user->group_no} ({$user->department}): {$request->documents}",
                'submission_id' => $submission->id,
                'read' => false
            ]);
        }

        return redirect()->route('submission')
            ->with('success', 'Proposal submitted successfully!');
    }

    public function viewFile($id)
    {
        $submission = Submission::findOrFail($id);
        
        if (!$submission->file_path) {
            abort(404, 'File not found');
        }
        
        // Check if it's a URL (Google Drive link)
        if (filter_var($submission->file_path, FILTER_VALIDATE_URL)) {
            return redirect($submission->file_path);
        }
        
        $filePath = storage_path('app/public/' . $submission->file_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }
        
        return response()->file($filePath);
    }
    public function viewPanelAdviser()
    {
        $user = auth()->user();
        
        // Check if user has required fields
        if (!$user->department) {
            \Log::warning('User missing department', ['user_id' => $user->id]);
            return view('view-panel-adviser', ['assignment' => null, 'group' => null, 'error' => 'Your account is missing department information. Please contact the administrator.']);
        }
        
        if (!$user->cluster) {
            \Log::warning('User missing cluster', ['user_id' => $user->id, 'department' => $user->department]);
            return view('view-panel-adviser', ['assignment' => null, 'group' => null, 'error' => 'Your account is missing cluster information. Please contact the administrator.']);
        }
        
        // Get group data
        $group = \App\Models\Group::where('department', $user->department)
            ->where('cluster', $user->cluster)
            ->where('group_number', $user->group_no)
            ->first();
        
        // Get assignment data for the user's department and cluster
        $assignment = \App\Models\Assignment::with(['adviser', 'assignmentPanels'])
            ->where('department', $user->department)
            ->where('section', $user->cluster)
            ->first();
        
        return view('view-panel-adviser', compact('assignment', 'group'));
    }

    public function viewSchedules()
    {
        $user = auth()->user();
        $groupNumber = $user->group_no ?? null;
        $department = $user->department ?? null;
        $cluster = $user->cluster ?? $user->section_cluster ?? '4101';
        
        if (!$groupNumber || !$department) {
            return view('view-sched', ['schedules' => collect(), 'assignment' => null]);
        }
        
        // Find the actual group record to get group_id
        $group = \App\Models\Group::where('department', $department)
            ->where('cluster', $cluster)
            ->where('group_number', $groupNumber)
            ->first();
        
        if (!$group) {
            return view('view-sched', ['schedules' => collect(), 'assignment' => null]);
        }
        
        $groupId = $group->group_id ?? $group->id;
        
        // Fetch schedules for this specific group
        $schedules = \App\Models\DefenseSchedule::where('department', $department)
            ->where('section', $cluster)
            ->where('group_id', $groupId)
            ->get();
        
        // Fetch assignment data
        $assignment = \App\Models\Assignment::with(['adviser', 'assignmentPanels.panel'])
            ->where('department', $department)
            ->where('section', $cluster)
            ->first();
        
        return view('view-sched', compact('schedules', 'assignment'));
    }

public function resubmit(Request $request, $id)
{
    $submission = Submission::findOrFail($id);
    $user = auth()->user();

    // Check if document is Research Forum to allow images
    $isResearchForum = stripos($request->documents, 'research forum') !== false;
    
    $fileValidation = $isResearchForum 
        ? 'required|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120'
        : 'required|mimes:pdf,doc,docx|max:5120';

    $request->validate([
        'documents' => 'required|string|max:255',
        'file'  => $fileValidation,
    ]);

    $submission->documents = $request->documents;

    if ($request->hasFile('file')) {
        $path = $request->file('file')->store('submissions', 'public');

        if ($submission->file_path && !filter_var($submission->file_path, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($submission->file_path);
        }

        $submission->file_path = $path;
    }

    $submission->status = 'Resubmitted';
    $submission->save();

    // Notify coordinator
    $coordinator = User::where('role', 'coordinator')
        ->where('department', $user->department)
        ->first();
    
    if ($coordinator) {
        \App\Models\Notification::create([
            'user_id' => $coordinator->id,
            'type' => 'resubmission',
            'message' => "Group {$user->group_no} resubmitted: {$submission->documents}",
            'submission_id' => $submission->id,
            'read' => false
        ]);
    }

    // Notify admin
    $admin = User::where('role', 'admin')->first();
    if ($admin) {
        \App\Models\Notification::create([
            'user_id' => $admin->id,
            'type' => 'resubmission',
            'message' => "Group {$user->group_no} ({$user->department}) resubmitted: {$submission->documents}",
            'submission_id' => $submission->id,
            'read' => false
        ]);
    }

    return redirect()->back()->with('success', 'Submission resubmitted successfully.');
}

}
