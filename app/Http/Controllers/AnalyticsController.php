<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            ->orderBy('created_at', 'desc')
            ->limit(10);
        
        if ($selectedDepartment) {
            $recentSubmissionsQuery->where('department', $selectedDepartment);
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
}
