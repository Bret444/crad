<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Assignment;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class StudentController extends Controller
{

    public function index()
{
    $upcomingSubmissions = Submission::where('user_id', auth()->id())
        ->where('due_date', '>=', now())
        ->orderBy('due_date')
        ->get()
        ->map(function($submission){
            $submission->status_color = match($submission->status) {
                'Pending' => 'orange',
                'Submitted' => 'blue',
                'Resubmitted' => 'green',
                default => 'gray',
            };
            return $submission;
        });

    $chapters = [
        'Chapter 1: The Problem' => Submission::where('user_id', auth()->id())->where('chapter', 1)->first(),
        'Chapter 2: Review of Literature' => Submission::where('user_id', auth()->id())->where('chapter', 2)->first(),
        'Chapter 3: Methodology' => Submission::where('user_id', auth()->id())->where('chapter', 3)->first(),
        'Final Defense' => Submission::where('user_id', auth()->id())->where('chapter', 'final')->first(),
    ];

    // Optional: assign colors if needed
    foreach($chapters as $key => $chapter){
        if($chapter){
            $chapter->color = match($chapter->status) {
                'Pending' => '#ffc107',
                'Submitted' => '#0d6efd',
                'Resubmitted' => '#198754',
                default => '#6c757d',
            };
        }
    }

    return view('std-dashboard', compact('upcomingSubmissions', 'chapters'));
}


public function dashboard()
{
    $userId = Auth::id();
    $user = Auth::user();

    // Get group information
    $groupData = Group::where('department', $user->department)
        ->where('cluster', $user->cluster)
        ->where('group_number', $user->group_no)
        ->first();

    // Get adviser assigned to this group
    $adviser = \App\Models\Adviser::where('department', $user->department)
        ->whereJsonContains('sections', (int)$user->group_no)
        ->first();

    // Get panel assignment
    $assignment = Assignment::with('assignmentPanels')
        ->where('department', $user->department)
        ->where('section', $user->cluster)
        ->first();

    $panelsList = $assignment ? $assignment->assignmentPanels : collect();

    // Get defense schedules
    $defenseSchedules = \App\Models\DefenseSchedule::where('department', $user->department)
        ->where('section', $user->cluster)
        ->where('group_id', $groupData ? $groupData->id : null)
        ->get();

    // Create group object with all data
    $group = (object) [
        'group_no' => $user->group_no ?? 'N/A',
        'department' => $user->department ?? 'N/A',
        'cluster' => $user->cluster ?? 'N/A',
        'adviser' => $adviser,
        'panels' => $panelsList,
        'members' => $groupData ? collect([
            ['name' => $groupData->member1_name, 'student_id' => $groupData->member1_student_id, 'is_leader' => $groupData->leader_member == 1],
            ['name' => $groupData->member2_name, 'student_id' => $groupData->member2_student_id, 'is_leader' => $groupData->leader_member == 2],
            ['name' => $groupData->member3_name, 'student_id' => $groupData->member3_student_id, 'is_leader' => $groupData->leader_member == 3],
            ['name' => $groupData->member4_name, 'student_id' => $groupData->member4_student_id, 'is_leader' => $groupData->leader_member == 4],
            ['name' => $groupData->member5_name, 'student_id' => $groupData->member5_student_id, 'is_leader' => $groupData->leader_member == 5],
        ])->filter(fn($m) => $m['name']) : collect()
    ];

    // Upcoming submissions
    $upcomingSubmissions = Submission::where('user_id', $userId)
        ->where('due_date', '>=', now())
        ->orderBy('due_date')
        ->get()
        ->map(function ($submission) {
            $submission->status_color = match($submission->status) {
                'Pending' => 'orange',
                'Submitted' => 'blue',
                'Resubmitted' => 'green',
                default => 'gray',
            };
            return $submission;
        });

    // Chapters progress
    $chapters = [
        'Chapter 1: The Problem' => Submission::where('user_id', $userId)->where('chapter', 1)->first(),
        'Chapter 2: Review of Literature' => Submission::where('user_id', $userId)->where('chapter', 2)->first(),
        'Chapter 3: Methodology' => Submission::where('user_id', $userId)->where('chapter', 3)->first(),
        'Final Defense' => Submission::where('user_id', $userId)->where('chapter', 'final')->first(),
    ];

    foreach ($chapters as $key => $chapter) {
        if ($chapter) {
            $chapter->color = match($chapter->status) {
                'Pending' => '#ffc107',
                'Submitted' => '#0d6efd',
                'Resubmitted' => '#198754',
                default => '#6c757d',
            };
        }
    }

    $submissions = Submission::where('user_id', $userId)->get();
    $latestSubmission = $submissions->last();
    $submissionStatus = $latestSubmission ? $latestSubmission->status : 'PENDING';
    
    $progressSteps = [
        1 => $submissions->where('status', 'Approved')->count() > 0,
        2 => false,
        3 => false,
        4 => false,
        5 => false,
        6 => false,
    ];
    
    $progress = collect($progressSteps)->filter()->count();

    return view('std-dashboard', compact('upcomingSubmissions', 'chapters', 'submissionStatus', 'progress', 'progressSteps', 'group', 'defenseSchedules'));
}

    public function submission()
    {
         // Fetch student's submissions
        $submissions = Submission::where('user_id', auth()->id())->get();

        // Fetch notifications safely
        $notifications = Notification::where('user_id', auth()->id())
                                     ->orderBy('created_at', 'desc')
                                     ->get() ?? collect(); // Ensure it's always a collection

        // Pass data to the view
        return view('submission', compact('submissions', 'notifications'));
    }
    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'file' => 'required|mimes:pdf,doc,docx|max:2048',
        'department' => 'required|string',
        'cluster' => 'required|integer',
        'group_no' => 'required|integer',
    ]);

    $path = $request->file('file')->store('submissions', 'public');

    Submission::create([
        'user_id' => auth()->id(),
        'submitted_by' => auth()->user()->name, // or whichever field you want
        'documents' => $request->documents,
        'department' => $request->department,
        'cluster' => $request->cluster,
        'group_no' => $request->group_no,
        'file_path' => $path,
        'status' => 'Pending',
    ]);
    

    return back()->with('success', 'Submission uploaded successfully!');
}

public function notifications()
{
    $notifications = Notification::where('user_id', auth()->id())
                                 ->orderBy('created_at', 'desc')
                                 ->get();
    
    // Mark all notifications as read
    Notification::where('user_id', auth()->id())
                ->where('read', false)
                ->update(['read' => true]);
    
    return redirect()->back()->with('success', 'All notifications marked as read!');
}

public function notificationDetails($id)
{
    $notification = Notification::with('submission')
                                ->where('id', $id)
                                ->where('user_id', auth()->id())
                                ->firstOrFail();
    
    // Mark as read
    if (!$notification->read) {
        $notification->update(['read' => true]);
    }
    
    return view('notification-details', compact('notification'));
}

}
