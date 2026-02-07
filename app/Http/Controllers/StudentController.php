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
    // Check if group login
    if (session('is_group_login')) {
        $groupId = session('group_id');
        $department = session('group_department');
        $cluster = session('group_cluster');
        $groupNo = session('group_number');
        
        $groupData = Group::where('group_id', $groupId)->first();
    } else {
        $userId = Auth::id();
        $user = Auth::user();
        $department = $user->department;
        $cluster = $user->cluster;
        $groupNo = $user->group_no;
        
        $groupData = Group::where('department', $department)
            ->where('cluster', $cluster)
            ->where('group_number', $groupNo)
            ->first();
    }

    // Get adviser assigned to this group
    $adviser = \App\Models\Adviser::where('department', $department)
        ->whereJsonContains('sections', (int)$groupNo)
        ->first();

    // Get panel assignment
    $assignment = Assignment::with('assignmentPanels')
        ->where('department', $department)
        ->where('section', $cluster)
        ->first();

    $panelsList = $assignment ? $assignment->assignmentPanels : collect();

    // Get defense schedules
    $defenseSchedules = \App\Models\DefenseSchedule::where('department', $department)
        ->where('section', $cluster)
        ->where('group_id', $groupData ? $groupData->id : null)
        ->get();

    // Create group object with all data
    $group = (object) [
        'group_no' => $groupNo ?? 'N/A',
        'department' => $department ?? 'N/A',
        'cluster' => $cluster ?? 'N/A',
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
    $userId = session('is_group_login') ? null : Auth::id();
    $upcomingSubmissions = $userId ? Submission::where('user_id', $userId)
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
        }) : collect();

    // Chapters progress
    $chapters = $userId ? [
        'Chapter 1: The Problem' => Submission::where('user_id', $userId)->where('chapter', 1)->first(),
        'Chapter 2: Review of Literature' => Submission::where('user_id', $userId)->where('chapter', 2)->first(),
        'Chapter 3: Methodology' => Submission::where('user_id', $userId)->where('chapter', 3)->first(),
        'Final Defense' => Submission::where('user_id', $userId)->where('chapter', 'final')->first(),
    ] : [];

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

    $submissions = $userId ? Submission::where('user_id', $userId)->get() : collect();
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
    
    $notifications = $userId ? Notification::where('user_id', $userId)->orderBy('created_at', 'desc')->get() : collect();

    return view('std-dashboard', compact('upcomingSubmissions', 'chapters', 'submissionStatus', 'progress', 'progressSteps', 'group', 'defenseSchedules', 'notifications'));
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

    $submission = Submission::create([
        'user_id' => auth()->id(),
        'submitted_by' => auth()->user()->name,
        'documents' => $request->documents,
        'department' => $request->department,
        'cluster' => $request->cluster,
        'group_no' => $request->group_no,
        'file_path' => $path,
        'status' => 'Pending',
    ]);
    
    // Notify coordinator
    $coordinator = \App\Models\User::where('role', 'coordinator')
        ->where('department', $request->department)
        ->first();
    
    if ($coordinator) {
        Notification::create([
            'user_id' => $coordinator->id,
            'type' => 'submission',
            'message' => "New submission from Group {$request->group_no}: {$request->documents}",
            'submission_id' => $submission->id,
            'read' => false
        ]);
    }

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

public function getNotifications()
{
    $notifications = Notification::with('submission')
        ->where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();
    
    return response()->json([
        'notifications' => $notifications,
        'unread_count' => Notification::where('user_id', auth()->id())->where('read', false)->count()
    ]);
}

}
