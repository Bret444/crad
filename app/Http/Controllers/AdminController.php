<?php

namespace App\Http\Controllers;
use App\Models\Submission;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\DefenseSchedule;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Key statistics
        $totalSubmissions = Submission::count();
        $pendingSubmissions = Submission::where('status', 'Pending')->count();
        $approvedSubmissions = Submission::where('status', 'Approved')->count();
        $rejectedSubmissions = Submission::where('status', 'Rejected')->count();
        
        // Recent submissions
        $recentSubmissions = Submission::with('user')->latest()->take(5)->get();
        
        // Department breakdown
        $departmentStats = Submission::selectRaw('department, COUNT(*) as count')
            ->groupBy('department')
            ->get();
            
        // Monthly submission trends (last 6 months)
        $monthlyStats = Submission::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
            
        // Real-time notifications for admin
        $notifications = collect();
        
        // Pending submissions notifications
        if ($pendingSubmissions > 0) {
            $notifications->push([
                'type' => 'warning',
                'icon' => 'bi-clock',
                'message' => "{$pendingSubmissions} submission(s) awaiting review",
                'time' => 'now',
                'action' => route('admin.track-proposal')
            ]);
        }
        
        // Recent submissions in last 24 hours
        $recentCount = Submission::where('created_at', '>=', now()->subDay())->count();
        if ($recentCount > 0) {
            $notifications->push([
                'type' => 'info',
                'icon' => 'bi-file-earmark-plus',
                'message' => "{$recentCount} new submission(s) in the last 24 hours",
                'time' => '24h',
                'action' => route('admin.track-proposal')
            ]);
        }
        
        // Defense scheduling notifications
        $pendingDefenses = DefenseSchedule::where('status', 'Pending')->count();
        if ($pendingDefenses > 0) {
            $notifications->push([
                'type' => 'primary',
                'icon' => 'bi-calendar-check',
                'message' => "{$pendingDefenses} defense(s) need scheduling",
                'time' => 'now',
                'action' => url('/def-sched')
            ]);
        }
        
        // Upcoming defenses (next 7 days)
        $upcomingDefenses = DefenseSchedule::where('defense_date', '>=', now())
            ->where('defense_date', '<=', now()->addDays(7))
            ->where('status', 'Scheduled')
            ->count();
        if ($upcomingDefenses > 0) {
            $notifications->push([
                'type' => 'success',
                'icon' => 'bi-calendar-event',
                'message' => "{$upcomingDefenses} defense(s) scheduled this week",
                'time' => '7d',
                'action' => url('/def-sched')
            ]);
        }
        
        // Overdue defenses (past defense date but still scheduled)
        $overdueDefenses = DefenseSchedule::where('defense_date', '<', now())
            ->where('status', 'Scheduled')
            ->count();
        if ($overdueDefenses > 0) {
            $notifications->push([
                'type' => 'danger',
                'icon' => 'bi-exclamation-triangle',
                'message' => "{$overdueDefenses} defense(s) are overdue",
                'time' => 'overdue',
                'action' => url('/def-sched')
            ]);
        }
        
        // Failed defenses needing redefense
        $failedDefenses = DefenseSchedule::where('status', 'Failed')
            ->whereNotExists(function($query) {
                $query->select('id')
                      ->from('defense_schedules as ds2')
                      ->whereColumn('ds2.group_id', 'defense_schedules.group_id')
                      ->where('ds2.defense_type', 'REDEFENSE');
            })
            ->count();
        if ($failedDefenses > 0) {
            $notifications->push([
                'type' => 'warning',
                'icon' => 'bi-arrow-repeat',
                'message' => "{$failedDefenses} group(s) need redefense scheduling",
                'time' => 'action needed',
                'action' => url('/def-sched')
            ]);
        }
        
        // Analytics insights
        $lowPerformingDepts = Submission::selectRaw('department, 
            COUNT(*) as total,
            SUM(CASE WHEN status = "Approved" THEN 1 ELSE 0 END) as approved')
            ->groupBy('department')
            ->havingRaw('COUNT(*) > 5 AND (SUM(CASE WHEN status = "Approved" THEN 1 ELSE 0 END) / COUNT(*)) < 0.5')
            ->count();
        if ($lowPerformingDepts > 0) {
            $notifications->push([
                'type' => 'info',
                'icon' => 'bi-graph-down',
                'message' => "{$lowPerformingDepts} department(s) have low approval rates",
                'time' => 'analytics',
                'action' => route('admin.analytics')
            ]);
        }
        
        // Defense statistics for dashboard cards
        $defenseStats = [
            'total' => DefenseSchedule::count(),
            'scheduled' => DefenseSchedule::where('status', 'Scheduled')->count(),
            'passed' => DefenseSchedule::where('status', 'Passed')->count(),
            'pending' => DefenseSchedule::where('status', 'Pending')->count()
        ];
            
        return view('admin-dashboard', compact(
            'totalSubmissions', 'pendingSubmissions', 'approvedSubmissions', 'rejectedSubmissions',
            'recentSubmissions', 'departmentStats', 'monthlyStats', 'notifications', 'defenseStats'
        ));
    }

    public function analytics(Request $request)
    {
        $selectedDepartment = $request->get('department');
        
        // Get all departments for filter
        $departments = Submission::distinct()->pluck('department')->filter();
        
        // Base query
        $query = Submission::with('user');
        if ($selectedDepartment) {
            $query->where('department', $selectedDepartment);
        }
        
        // Key metrics
        $totalSubmissions = $query->count();
        $approvedSubmissions = (clone $query)->where('status', 'Approved')->count();
        $rejectedSubmissions = (clone $query)->where('status', 'Rejected')->count();
        $pendingSubmissions = (clone $query)->where('status', 'Pending')->count();
        
        // Completion rate
        $completionRate = $totalSubmissions > 0 ? round(($approvedSubmissions / $totalSubmissions) * 100, 1) : 0;
        
        // Department statistics
        $departmentStats = Submission::selectRaw('department, 
            COUNT(*) as total,
            SUM(CASE WHEN status = "Approved" THEN 1 ELSE 0 END) as approved,
            SUM(CASE WHEN status = "Rejected" THEN 1 ELSE 0 END) as rejected,
            SUM(CASE WHEN status = "Pending" THEN 1 ELSE 0 END) as pending')
            ->groupBy('department')
            ->get();
        
        // Recent submissions for table
        $recentSubmissions = $query->latest()->take(10)->get();
        
        // Defense scheduling analytics
        $defenseQuery = DefenseSchedule::query();
        if ($selectedDepartment) {
            $defenseQuery->where('department', $selectedDepartment);
        }
        
        $totalDefenseSchedules = $defenseQuery->count();
        $scheduledDefenses = (clone $defenseQuery)->where('status', 'Scheduled')->count();
        $passedDefenses = (clone $defenseQuery)->where('status', 'Passed')->count();
        $failedDefenses = (clone $defenseQuery)->where('status', 'Failed')->count();
        $pendingDefenses = (clone $defenseQuery)->where('status', 'Pending')->count();
        
        // Defense type breakdown
        $defenseTypeStats = DefenseSchedule::selectRaw('defense_type, 
            COUNT(*) as total,
            SUM(CASE WHEN status = "Passed" THEN 1 ELSE 0 END) as passed,
            SUM(CASE WHEN status = "Failed" THEN 1 ELSE 0 END) as failed,
            SUM(CASE WHEN status = "Scheduled" THEN 1 ELSE 0 END) as scheduled,
            SUM(CASE WHEN status = "Pending" THEN 1 ELSE 0 END) as pending')
            ->when($selectedDepartment, function($q) use ($selectedDepartment) {
                return $q->where('department', $selectedDepartment);
            })
            ->groupBy('defense_type')
            ->get();
        
        // Recent defense schedules
        $recentDefenseSchedules = $defenseQuery->latest()->take(10)->get();
        
        return view('analytics', compact(
            'departments', 'selectedDepartment', 'totalSubmissions', 
            'approvedSubmissions', 'rejectedSubmissions', 'pendingSubmissions',
            'completionRate', 'departmentStats', 'recentSubmissions',
            'totalDefenseSchedules', 'scheduledDefenses', 'passedDefenses', 
            'failedDefenses', 'pendingDefenses', 'defenseTypeStats', 'recentDefenseSchedules'
        ));
    }

    public function trackProposals()
{
    $submissions = Submission::with(['user', 'committee'])->latest()->get();

    return view('track-proposal', compact('submissions'));
}

public function updateStatus(Request $request, $submissionId)
{
    $submission = Submission::findOrFail($submissionId);
    $submission->status = $request->status;
    $submission->feedback = $request->feedback ?? $submission->feedback;
    $submission->save();

    // Create notification
    $message = match($request->status) {
        'Approved' => "Your proposal '{$submission->documents}' has been approved.",
        'Rejected' => "Your proposal '{$submission->documents}' has been rejected. Please revise and resubmit.",
        default => "Feedback received for '{$submission->documents}': {$submission->feedback}",
    };

    Notification::create([
        'user_id' => $submission->user_id,
        'type' => $request->status,
        'message' => $message,
    ]);

    return back()->with('success', 'Status updated and notification sent.');
}

}

