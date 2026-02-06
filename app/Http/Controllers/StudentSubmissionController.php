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
        $request->validate([
            'documents' => 'required|string|max:255',
            'file'  => 'nullable|mimes:pdf,doc,docx|max:2048',
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
        return response()->file(storage_path('app/public/' . $submission->file_path));
    }
    public function viewPanelAdviser()
    {
        $user = auth()->user();
        
        // Get assignment data for the user's department and cluster
        // User cluster field maps to Assignment section field
        $assignment = \App\Models\Assignment::with(['adviser', 'assignmentPanels'])
            ->where('department', $user->department)
            ->where('section', $user->cluster ?? '4101')
            ->first();
        
        return view('view-panel-adviser', compact('assignment'));
    }

    public function viewSchedules()
    {
        $user = auth()->user();
        $groupNumber = $user->group_no ?? null;
        $department = $user->department ?? null;
        $cluster = $user->cluster ?? '4101';
        
        if (!$groupNumber || !$department) {
            return view('view-sched', ['schedules' => collect(), 'assignment' => null]);
        }
        
        // Calculate offset like in def-sched.blade.php
        $clusterNum = intval($cluster ?? '4101');
        $groupOffset = ($clusterNum - 4101) * 10;
        
        // Remove offset to get base group number (1-10)
        $baseGroupNumber = $groupNumber - $groupOffset;
        
        // Map to group_id format used in def-sched
        if ($baseGroupNumber >= 1 && $baseGroupNumber <= 5) {
            $groupId = 'A' . $baseGroupNumber;
        } elseif ($baseGroupNumber >= 6 && $baseGroupNumber <= 10) {
            $groupId = 'B' . ($baseGroupNumber - 5);
        } else {
            // Fallback for invalid group numbers
            $groupId = 'A1';
        }
        
        // Fetch schedules - try exact match first, then fallback to any group in department
        $schedules = \App\Models\DefenseSchedule::where('department', $department)
            ->where('section', $cluster)
            ->where('group_id', $groupId)
            ->get();
            
        // If no schedules found, try without section filter
        if ($schedules->isEmpty()) {
            $schedules = \App\Models\DefenseSchedule::where('department', $department)
                ->where('group_id', $groupId)
                ->get();
        }
        
        // Fetch assignment data - try with section first, fallback to department only
        $assignment = \App\Models\Assignment::with(['adviser', 'assignmentPanels.panel'])
            ->where('department', $department)
            ->where('section', $cluster)
            ->first();
            
        if (!$assignment) {
            $assignment = \App\Models\Assignment::with(['adviser', 'assignmentPanels.panel'])
                ->where('department', $department)
                ->first();
        }
        
        return view('view-sched', compact('schedules', 'assignment'));
    }

public function resubmit(Request $request, $id)
{
    $submission = Submission::findOrFail($id);
    $user = auth()->user();

    $request->validate([
        'documents' => 'required|string|max:255',
        'file'  => 'required|mimes:pdf,doc,docx|max:2048',
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
