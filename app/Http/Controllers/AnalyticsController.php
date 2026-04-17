<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class AnalyticsController extends Controller
{
    public function create(Request $request)
    {
        $selectedDepartment = $request->get('department', '');
        
        // Base query for submissions
        $submissionsQuery = DB::table('submissions');
        if ($selectedDepartment) {
            $submissionsQuery->where('department', $selectedDepartment);
        }
        
        // Submission metrics
        $totalSubmissions = $submissionsQuery->count();
        $approvedSubmissions = (clone $submissionsQuery)->where('status', 'Approved')->count();
        $rejectedSubmissions = (clone $submissionsQuery)->where('status', 'Rejected')->count();
        $pendingSubmissions = (clone $submissionsQuery)->where('status', 'Pending')->count();
        $completionRate = $totalSubmissions > 0 ? round(($approvedSubmissions / $totalSubmissions) * 100, 1) : 0;
        
        // Base query for defense schedules
        $defenseQuery = DB::table('defense_schedules');
        if ($selectedDepartment) {
            $defenseQuery->where('department', $selectedDepartment);
        }
        
        // Defense metrics
        $totalDefenseSchedules = $defenseQuery->count();
        $passedDefenses = (clone $defenseQuery)->where('status', 'Passed')->count();
        $failedDefenses = (clone $defenseQuery)->where('status', 'Failed')->count();
        $redefenseDefenses = (clone $defenseQuery)->where('status', 'Re-defense')->count();
        $scheduledDefenses = (clone $defenseQuery)->where('status', 'Scheduled')->count();
        $pendingDefenses = (clone $defenseQuery)->where('status', 'Pending')->count();
        
        \Log::info('Analytics Defense Counts', [
            'department' => $selectedDepartment,
            'total' => $totalDefenseSchedules,
            'passed' => $passedDefenses,
            'failed' => $failedDefenses,
            'redefense' => $redefenseDefenses,
            'scheduled' => $scheduledDefenses,
            'pending' => $pendingDefenses
        ]);
        
        // Defense type statistics with Re-defense
        $defenseTypeStatsQuery = DB::table('defense_schedules')
            ->select(
                'defense_type',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "Passed" THEN 1 ELSE 0 END) as passed'),
                DB::raw('SUM(CASE WHEN status = "Failed" THEN 1 ELSE 0 END) as failed'),
                DB::raw('SUM(CASE WHEN status = "Scheduled" THEN 1 ELSE 0 END) as scheduled'),
                DB::raw('SUM(CASE WHEN status = "Pending" THEN 1 ELSE 0 END) as pending'),
                DB::raw('SUM(CASE WHEN status = "Re-defense" THEN 1 ELSE 0 END) as redefense')
            )
            ->groupBy('defense_type');
        
        if ($selectedDepartment) {
            $defenseTypeStatsQuery->where('department', $selectedDepartment);
        }
        
        $defenseTypeStats = $defenseTypeStatsQuery->get();
        
        // Department statistics
        $departmentStatsQuery = DB::table('submissions')
            ->select(
                'department',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "Approved" THEN 1 ELSE 0 END) as approved'),
                DB::raw('SUM(CASE WHEN status = "Rejected" THEN 1 ELSE 0 END) as rejected'),
                DB::raw('SUM(CASE WHEN status = "Pending" THEN 1 ELSE 0 END) as pending')
            )
            ->groupBy('department');
        
        if ($selectedDepartment) {
            $departmentStatsQuery->where('department', $selectedDepartment);
        }
        
        $departmentStats = $departmentStatsQuery->get();
        
        // Recent submissions
        $recentSubmissionsQuery = DB::table('submissions')
            ->join('users', 'submissions.user_id', '=', 'users.id')
            ->select('submissions.*', 'users.name as user_name')
            ->orderBy('submissions.created_at', 'desc')
            ->limit(10);
        
        if ($selectedDepartment) {
            $recentSubmissionsQuery->where('submissions.department', $selectedDepartment);
        }
        
        $recentSubmissions = $recentSubmissionsQuery->get()->map(function($submission) {
            $submission->created_at = \Carbon\Carbon::parse($submission->created_at);
            return $submission;
        });
        
        // Recent defense schedules
        $recentDefenseSchedulesQuery = DB::table('defense_schedules')
            ->orderBy('created_at', 'desc')
            ->limit(10);
        
        if ($selectedDepartment) {
            $recentDefenseSchedulesQuery->where('department', $selectedDepartment);
        }
        
        $recentDefenseSchedules = $recentDefenseSchedulesQuery->get()->map(function($schedule) {
            if ($schedule->created_at) {
                $schedule->created_at = \Carbon\Carbon::parse($schedule->created_at);
            }
            return $schedule;
        });
        
        // Get all departments for filter
        $departments = DB::table('submissions')
            ->select('department')
            ->distinct()
            ->pluck('department');
        
        return view('analytics', [
            'pageTitle' => 'Analytics Dashboard',
            'selectedDepartment' => $selectedDepartment,
            'departments' => $departments,
            'totalSubmissions' => $totalSubmissions,
            'approvedSubmissions' => $approvedSubmissions,
            'rejectedSubmissions' => $rejectedSubmissions,
            'pendingSubmissions' => $pendingSubmissions,
            'completionRate' => $completionRate,
            'totalDefenseSchedules' => $totalDefenseSchedules,
            'passedDefenses' => $passedDefenses,
            'failedDefenses' => $failedDefenses,
            'redefenseDefenses' => $redefenseDefenses,
            'scheduledDefenses' => $scheduledDefenses,
            'pendingDefenses' => $pendingDefenses,
            'defenseTypeStats' => $defenseTypeStats,
            'departmentStats' => $departmentStats,
            'recentSubmissions' => $recentSubmissions,
            'recentDefenseSchedules' => $recentDefenseSchedules
        ]);
    }

    public function export(Request $request)
    {
        $selectedDepartment = $request->get('department', '');
        
        $submissionsQuery = DB::table('submissions');
        $defenseQuery = DB::table('defense_schedules');
        
        if ($selectedDepartment) {
            $submissionsQuery->where('department', $selectedDepartment);
            $defenseQuery->where('department', $selectedDepartment);
        }
        
        $totalSubmissions = $submissionsQuery->count();
        $approvedSubmissions = (clone $submissionsQuery)->where('status', 'Approved')->count();
        $rejectedSubmissions = (clone $submissionsQuery)->where('status', 'Rejected')->count();
        $pendingSubmissions = (clone $submissionsQuery)->where('status', 'Pending')->count();
        $completionRate = $totalSubmissions > 0 ? round(($approvedSubmissions / $totalSubmissions) * 100, 1) : 0;
        
        $totalDefenseSchedules = $defenseQuery->count();
        $passedDefenses = (clone $defenseQuery)->where('status', 'Passed')->count();
        $failedDefenses = (clone $defenseQuery)->where('status', 'Failed')->count();
        $redefenseDefenses = (clone $defenseQuery)->where('status', 'Re-defense')->count();
        $scheduledDefenses = (clone $defenseQuery)->where('status', 'Scheduled')->count();
        $pendingDefenses = (clone $defenseQuery)->where('status', 'Pending')->count();
        
        $defenseTypeStatsQuery = DB::table('defense_schedules')
            ->select(
                'defense_type',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "Passed" THEN 1 ELSE 0 END) as passed'),
                DB::raw('SUM(CASE WHEN status = "Failed" THEN 1 ELSE 0 END) as failed'),
                DB::raw('SUM(CASE WHEN status = "Scheduled" THEN 1 ELSE 0 END) as scheduled'),
                DB::raw('SUM(CASE WHEN status = "Pending" THEN 1 ELSE 0 END) as pending'),
                DB::raw('SUM(CASE WHEN status = "Re-defense" THEN 1 ELSE 0 END) as redefense')
            )
            ->groupBy('defense_type');
        
        if ($selectedDepartment) {
            $defenseTypeStatsQuery->where('department', $selectedDepartment);
        }
        
        $defenseTypeStats = $defenseTypeStatsQuery->get();
        
        $departmentStatsQuery = DB::table('submissions')
            ->select(
                'department',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "Approved" THEN 1 ELSE 0 END) as approved'),
                DB::raw('SUM(CASE WHEN status = "Rejected" THEN 1 ELSE 0 END) as rejected'),
                DB::raw('SUM(CASE WHEN status = "Pending" THEN 1 ELSE 0 END) as pending')
            )
            ->groupBy('department');
        
        if ($selectedDepartment) {
            $departmentStatsQuery->where('department', $selectedDepartment);
        }
        
        $departmentStats = $departmentStatsQuery->get();
        
        // Recent submissions
        $recentSubmissionsQuery = DB::table('submissions')
            ->join('users', 'submissions.user_id', '=', 'users.id')
            ->select('submissions.*', 'users.name as user_name')
            ->orderBy('submissions.created_at', 'desc')
            ->limit(10);
        
        if ($selectedDepartment) {
            $recentSubmissionsQuery->where('submissions.department', $selectedDepartment);
        }
        
        $recentSubmissions = $recentSubmissionsQuery->get();
        
        // Recent defense schedules
        $recentDefenseSchedulesQuery = DB::table('defense_schedules')
            ->orderBy('created_at', 'desc')
            ->limit(10);
        
        if ($selectedDepartment) {
            $recentDefenseSchedulesQuery->where('department', $selectedDepartment);
        }
        
        $recentDefenseSchedules = $recentDefenseSchedulesQuery->get();
        
        $filename = 'analytics_report_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($selectedDepartment, $totalSubmissions, $approvedSubmissions, $rejectedSubmissions, $pendingSubmissions, $completionRate, $totalDefenseSchedules, $passedDefenses, $failedDefenses, $redefenseDefenses, $scheduledDefenses, $pendingDefenses, $defenseTypeStats, $departmentStats, $recentSubmissions, $recentDefenseSchedules) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['CRAD Analytics Report']);
            fputcsv($file, ['Generated:', date('Y-m-d H:i:s')]);
            if ($selectedDepartment) {
                fputcsv($file, ['Department Filter:', $selectedDepartment]);
            } else {
                fputcsv($file, ['Department Filter:', 'All Departments']);
            }
            fputcsv($file, []);
            
            fputcsv($file, ['KEY METRICS']);
            fputcsv($file, []);
            
            fputcsv($file, ['SUBMISSION METRICS']);
            fputcsv($file, ['Metric', 'Count']);
            fputcsv($file, ['Total Submissions', $totalSubmissions]);
            fputcsv($file, ['Approved', $approvedSubmissions]);
            fputcsv($file, ['Rejected', $rejectedSubmissions]);
            fputcsv($file, ['Pending', $pendingSubmissions]);
            fputcsv($file, ['Project Completion Rate', $completionRate . '%']);
            fputcsv($file, []);
            
            fputcsv($file, ['DEFENSE METRICS']);
            fputcsv($file, ['Metric', 'Count']);
            fputcsv($file, ['Total Defenses', $totalDefenseSchedules]);
            fputcsv($file, ['Passed', $passedDefenses]);
            fputcsv($file, ['Failed', $failedDefenses]);
            fputcsv($file, ['Re-defense', $redefenseDefenses]);
            fputcsv($file, ['Scheduled', $scheduledDefenses]);
            fputcsv($file, ['Pending', $pendingDefenses]);
            fputcsv($file, []);
            fputcsv($file, []);
            
            fputcsv($file, ['DEFENSE TYPE STATISTICS']);
            fputcsv($file, ['Defense Type', 'Total', 'Passed', 'Failed', 'Re-defense', 'Scheduled', 'Pending', 'Success Rate']);
            foreach ($defenseTypeStats as $stat) {
                $evaluated = $stat->passed + $stat->failed;
                $successRate = $evaluated > 0 ? round(($stat->passed / $evaluated) * 100, 1) : 0;
                fputcsv($file, [
                    $stat->defense_type,
                    $stat->total,
                    $stat->passed,
                    $stat->failed,
                    $stat->redefense,
                    $stat->scheduled,
                    $stat->pending,
                    $successRate . '%'
                ]);
            }
            fputcsv($file, []);
            fputcsv($file, []);
            
            fputcsv($file, ['DEPARTMENT STATISTICS']);
            fputcsv($file, ['Department', 'Total', 'Approved', 'Rejected', 'Pending', 'Success Rate']);
            foreach ($departmentStats as $stat) {
                $rate = $stat->total > 0 ? round(($stat->approved / $stat->total) * 100, 1) : 0;
                fputcsv($file, [
                    $stat->department,
                    $stat->total,
                    $stat->approved,
                    $stat->rejected,
                    $stat->pending,
                    $rate . '%'
                ]);
            }
            fputcsv($file, []);
            fputcsv($file, []);
            
            fputcsv($file, ['RECENT SUBMISSIONS (Last 10)']);
            fputcsv($file, ['Student', 'Documents', 'Department', 'Status', 'Date']);
            foreach ($recentSubmissions as $submission) {
                $date = $submission->created_at ? \Carbon\Carbon::parse($submission->created_at)->format('M d, Y') : 'N/A';
                fputcsv($file, [
                    $submission->user_name ?? 'Unknown',
                    $submission->documents ?? 'N/A',
                    $submission->department,
                    $submission->status,
                    $date
                ]);
            }
            fputcsv($file, []);
            fputcsv($file, []);
            
            fputcsv($file, ['RECENT DEFENSE SCHEDULES (Last 10)']);
            fputcsv($file, ['Group', 'Defense Type', 'Department', 'Section', 'Date', 'Time', 'Status']);
            foreach ($recentDefenseSchedules as $schedule) {
                $date = 'Not set';
                if ($schedule->defense_date && $schedule->defense_date != '1970-01-01') {
                    $date = \Carbon\Carbon::parse($schedule->defense_date)->format('M d, Y');
                }
                
                $time = 'Not set';
                if ($schedule->start_time && $schedule->end_time) {
                    $time = $schedule->start_time . ' - ' . $schedule->end_time;
                }
                
                fputcsv($file, [
                    $schedule->group_id,
                    $schedule->defense_type,
                    $schedule->department,
                    $schedule->section,
                    $date,
                    $time,
                    $schedule->status
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
}
