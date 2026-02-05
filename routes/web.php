<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpAuthController;
use App\Http\Controllers\AdminSubmissionController;
use App\Http\Controllers\CommitteeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentSubmissionController;
use App\Http\Controllers\AdminController;
use App\Models\File;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AdviserController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\PanelAdviserController;
use App\Http\Controllers\DefenseScheduleController;
use App\Http\Controllers\CoordinatorController;

// Redirect root to login page
Route::get('/', fn() => redirect()->route('login'));

// Login & Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/check-lockout', [LoginController::class, 'checkLockout']);

// One-Time Password (OTP) Routes
Route::get('/otp-verify', [OtpAuthController::class, 'showVerifyForm'])->name('otp.verify.form');
Route::post('/otp-verify', [OtpAuthController::class, 'verifyOtp'])->name('otp.verify.submit');

// Dashboard
Route::get('/admin-dashboard', fn() => view('admin-dashboard'))
    ->middleware(['auth' , 'admin'])
    ->name('admin-dashboard');

    
// Proposals
Route::get('/track-proposal', action: fn() => view('track-proposal'));

// Adviser & Panel Assignment
Route::get('/view-panel-adviser', fn() => view('view-panel-adviser'));



// Analytics & Reporting
Route::get('/analytics', fn() => view('analytics'));







// Student routes
Route::middleware(['auth', 'student'])->group(function () {
    Route::get('/std-dashboard', [StudentController::class, 'dashboard'])->name('std-dashboard');
    Route::get('/submission', [StudentSubmissionController::class, 'submission'])->name('submission');
    Route::post('/files', [StudentController::class, 'store'])->name('student.files.store');
    Route::post('/submission', [StudentSubmissionController::class, 'store'])->name('student.submission.store');
    Route::post('/submissions', [StudentSubmissionController::class, 'store'])->name('student.submissions.store');
    Route::get('/notifications', [StudentController::class, 'notifications'])->name('student.notifications');
});

// Admin routes
Route::middleware(['auth' , 'admin'])->group(function () {
    Route::get('/admin-dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('admin.analytics');

    Route::get('/track-proposal', [AdminSubmissionController::class, 'trackProposal'])->name('admin.track-proposal');
    Route::get('/admin/submissions', [AdminSubmissionController::class, 'trackProposal'])->name('admin.submissions.index');
    Route::get('/admin/submissions/group/{group}', [AdminSubmissionController::class, 'getSubmissionsByGroup'])->name('admin.submissions.group');
    Route::post('/admin/submissions/{id}/update-status', [AdminSubmissionController::class, 'updateStatus'])->name('admin.submissions.updateStatus');
    Route::get('/admin/submissions/{id}/download', [AdminSubmissionController::class, 'downloadFile'])->name('admin.submissions.download');
    Route::post('/submissions/{id}/update-status', [AdminSubmissionController::class, 'updateStatus'])->name('admin.submissions.updateStatus');
    Route::post('/submission/{id}/approve', [AdminSubmissionController::class, 'approve'])->name('admin.submission.approve');
    Route::post('/submission/{id}/reject', [AdminSubmissionController::class, 'reject'])->name('admin.submission.reject');
    Route::post('/submission/{id}/feedback', [AdminSubmissionController::class, 'feedback'])->name('admin.submission.feedback');
});

// Track proposal page
Route::get('/track-proposal', [AdminSubmissionController::class, 'trackProposal'])
    ->name('admin.track-proposal')
    ->middleware('auth', 'admin');

// Update submission status
Route::post('/submissions/{id}/update-status', [AdminSubmissionController::class, 'updateStatus'])
    ->name('admin.submissions.updateStatus')
    ->middleware('auth', 'admin');

    Route::prefix('student')->middleware(['auth' , 'student'])->group(function () {
        // Route for viewing a single submission file
        Route::get('/submission/file/{submission}', [StudentSubmissionController::class, 'viewFile'])->name('submission.file');
    });

// For admins
Route::get('/admin/track-proposal/view/{submission}', [AdminSubmissionController::class, 'viewFile'])
->middleware('auth' , 'admin')
->name('admin.track-proposal.view');




// Committee Management
Route::post('/committee/store', [CommitteeController::class, 'store'])->middleware('auth', 'admin')->name('committee.store');
Route::post('/committee/{id}/assign', [CommitteeController::class, 'assign'])->middleware('auth', 'admin')->name('committee.assign');
Route::put('/committee/{id}/update', [CommitteeController::class, 'update'])->middleware('auth', 'admin')->name('committee.update');
Route::delete('/committee/{id}/delete', [CommitteeController::class, 'destroy'])->middleware('auth', 'admin')->name('committee.destroy');

//route-std
Route::get('/view-panel-adviser', [StudentSubmissionController::class, 'viewPanelAdviser'])
    ->name('student.view-panel-adviser')
    ->middleware('auth', 'student');

Route::get('/view-sched', [StudentSubmissionController::class, 'viewSchedules'])
    ->name('student.view-sched')
    ->middleware('auth', 'student');

Route::get('/notification/{id}', [StudentController::class, 'notificationDetails'])
    ->name('student.notification.details')
    ->middleware('auth', 'student');
// Panel-Adviser page (admin)
Route::get('/panel-adviser', [PanelAdviserController::class, 'showPanelAdviserPage'])
    ->middleware('auth', 'admin')
    ->name('panel-adviser');
   
//resubmit
Route::put('/student/files/{id}/resubmit', [StudentSubmissionController::class, 'resubmit'])
    ->middleware('auth', 'student')
    ->name('student.files.resubmit');
     

    //Adviser & Panel
Route::post('/advisers', [AdviserController::class, 'store'])->middleware('auth')->name('advisers.store');
Route::put('/advisers/{adviser}', [AdviserController::class, 'update'])->middleware('auth')->name('advisers.update');
Route::post('/advisers/{adviser}/manage-advisory', [AdviserController::class, 'manageAdvisory'])->middleware('auth')->name('advisers.manageAdvisory');
Route::delete('/advisers/{adviser}', [AdviserController::class, 'destroy'])->middleware('auth')->name('advisers.destroy');
Route::get('/advisers/archived', [AdviserController::class, 'archived'])->middleware('auth')->name('advisers.archived');
Route::post('/advisers/{id}/restore', [AdviserController::class, 'restore'])->middleware('auth')->name('advisers.restore');
Route::delete('/advisers/{id}/force-delete', [AdviserController::class, 'forceDelete'])->middleware('auth')->name('advisers.force-delete');

Route::post('/panels', [PanelController::class, 'store'])->middleware('auth', 'admin')->name('panels.store');
Route::put('/panels/{panel}', [PanelController::class, 'update'])->middleware('auth', 'admin')->name('panels.update');
Route::delete('/panels/{panel}', [PanelController::class, 'destroy'])->middleware('auth', 'admin')->name('panels.destroy');
Route::get('/panels/archived', [PanelController::class, 'archived'])->middleware('auth', 'admin')->name('panels.archived');
Route::post('/panels/{id}/restore', [PanelController::class, 'restore'])->middleware('auth', 'admin')->name('panels.restore');
Route::delete('/panels/{id}/force-delete', [PanelController::class, 'forceDelete'])->middleware('auth', 'admin')->name('panels.force-delete');

Route::post('/assignments/store', [AssignmentController::class, 'store'])->middleware('auth', 'admin')->name('assignments.store');

Route::prefix('api')->middleware('auth', 'admin')->group(function () {
    Route::get('/advisers/{department}', [AssignmentController::class, 'getAdvisers']);
    Route::get('/panel-members/{department}', [AssignmentController::class, 'getPanelMembers']);

    Route::post('/save-adviser', [AssignmentController::class, 'saveAdviser']);
    Route::post('/save-panel', [AssignmentController::class, 'savePanel']);
    Route::post('/finalize/{department}/{section}', [AssignmentController::class, 'finalizeAssignment']);
});

Route::get('/api/advisers/{clusterId}', [AdviserController::class, 'getAdvisersByCluster'])->middleware('auth', 'admin');
Route::get('/api/panels/{clusterId}', [PanelController::class, 'getPanelsByCluster'])->middleware('auth', 'admin');
Route::get('/api/panels', [PanelController::class, 'getPanels'])->middleware('auth', 'admin');


Route::post('/adviser', [AdviserController::class, 'store'])->middleware('auth', 'admin')->name('adviser.store');
Route::post('/panel', [PanelController::class, 'store'])->middleware('auth', 'admin')->name('panel.store');
Route::post('/assignment/finalize', [AssignmentController::class, 'finalize'])->middleware('auth', 'admin')->name('assignment.finalize');






Route::post('/panel-adviser/finalize', [PanelAdviserController::class, 'finalize'])->middleware('auth', 'admin')->name('panel.adviser.finalize');

// add availability (calls controller method)
Route::post('/panels/{panel}/availability', [PanelAdviserController::class, 'addPanelAvailability'])->middleware('auth', 'admin')->name('panels.availability.add');

// web.php
Route::get('/panel-adviser/filter', [PanelAdviserController::class, 'filter'])->middleware('auth', 'admin')->name('panel.adviser.filter');

//  ajax
Route::post('/submissions/{submission}/adviser/ajax', [PanelAdviserController::class, 'updateAdviserAjax'])->middleware('auth', 'admin')->name('adviser.update.ajax');
Route::post('/submissions/{submission}/panels/ajax', [PanelAdviserController::class, 'updatePanelsAjax'])->middleware('auth', 'admin')->name('panels.update.ajax');

//api-assigns
Route::get('/api/assignments', [PanelAdviserController::class, 'apiAssignments'])
    ->middleware('auth', 'admin')
    ->name('api.assignments');

Route::get('/api/advisers', [AdviserController::class, 'apiAdvisers'])
    ->middleware('auth', 'admin')
    ->name('api.advisers');

    Route::post('/assignments/store', [AssignmentController::class, 'store'])->middleware('auth', 'admin')->name('assignments.store');
    Route::put('/assignments/{assignment}', [AssignmentController::class, 'update'])->middleware('auth', 'admin')->name('assignments.update');
    Route::delete('/assignments/{assignment}', [AssignmentController::class, 'destroy'])->middleware('auth', 'admin')->name('assignments.destroy');
 
// Defense Schedule Routes
Route::get('/def-sched', function () {
    return view('def-sched');
})->middleware('auth' ,'admin')->name('def-sched');

Route::post('/defense-schedules', [DefenseScheduleController::class, 'store'])->middleware('auth');
Route::get('/defense-schedules/by-type', [DefenseScheduleController::class, 'getByType'])->middleware('auth');
Route::get('/defense-schedules/calendar', [DefenseScheduleController::class, 'getCalendarData'])->middleware('auth');
Route::put('/defense-schedules/{id}/status', [DefenseScheduleController::class, 'updateStatus'])->middleware('auth');
Route::get('/defense-schedules/check-eligibility', [DefenseScheduleController::class, 'checkEligibility'])->middleware('auth');
Route::delete('/defense-schedules/reset', [DefenseScheduleController::class, 'resetSchedules'])->middleware('auth');

// Defense Evaluation Routes  
Route::get('/def-eval', function () {
    return view('def-eval');
})->middleware('auth', 'admin')->name('def-eval');

// Panel Availability API Routes
Route::post('/api/panel-availability', [DefenseScheduleController::class, 'checkPanelAvailability'])->middleware('auth');
Route::post('/api/panel-availability-schedule', [DefenseScheduleController::class, 'getPanelAvailabilitySchedule'])->middleware('auth');

// Defense Evaluation API Routes
Route::post('/api/evaluations', [\App\Http\Controllers\DefenseEvaluationController::class, 'saveEvaluation'])->middleware('auth');
Route::get('/api/group-status', [\App\Http\Controllers\DefenseEvaluationController::class, 'getGroupStatus'])->middleware('auth');

// Manage Coordinator Routes
Route::middleware('auth' , 'admin')->group(function () {
    Route::get('/manage-coordinator', [CoordinatorController::class, 'index'])->name('manage-coordinator');
    Route::get('/coordinators/archives-data', [CoordinatorController::class, 'archivesData'])->name('coordinators.archives.data');
    Route::post('/coordinators', [CoordinatorController::class, 'store'])->name('coordinators.store');
    Route::put('/coordinators/{id}', [CoordinatorController::class, 'update'])->name('coordinators.update');
    Route::delete('/coordinators/{id}', [CoordinatorController::class, 'destroy'])->name('coordinators.destroy');
    Route::post('/coordinators/{id}/restore', [CoordinatorController::class, 'restore'])->name('coordinators.restore');
});

// Coordinator Dashboard
Route::get('/coordinator-dashboard', [CoordinatorController::class, 'dashboard'])
    ->middleware('auth', 'coordinator')
    ->name('coordinator-dashboard');

Route::get('/coordinator-title-proposal', [CoordinatorController::class, 'titleProposal'])
    ->middleware('auth', 'coordinator')
    ->name('coordinator-title-proposal');

Route::get('/coordinator/submissions/group/{group}', [CoordinatorController::class, 'getSubmissionsByGroup'])
    ->middleware('auth', 'coordinator')
    ->name('coordinator.submissions.group');

Route::post('/coordinator/submissions/{id}/update-status', [CoordinatorController::class, 'updateSubmissionStatus'])
    ->middleware('auth', 'coordinator')
    ->name('coordinator.submissions.updateStatus');

Route::get('/coordinator/submissions/{id}/download', [CoordinatorController::class, 'downloadSubmission'])
    ->middleware('auth', 'coordinator')
    ->name('coordinator.submissions.download');

Route::get('/coordinator-manage-adviser', [CoordinatorController::class, 'manageAdviser'])
    ->middleware('auth', 'coordinator')
    ->name('coordinator-manage-adviser');

Route::get('/coordinator-manage-groups', [\App\Http\Controllers\GroupController::class, 'index'])
    ->middleware('auth', 'coordinator')
    ->name('coordinator-manage-groups');

Route::post('/groups', [\App\Http\Controllers\GroupController::class, 'store'])->middleware('auth', 'coordinator')->name('groups.store');
Route::put('/groups/{id}', [\App\Http\Controllers\GroupController::class, 'update'])->middleware('auth', 'coordinator')->name('groups.update');
Route::delete('/groups/{id}', [\App\Http\Controllers\GroupController::class, 'destroy'])->middleware('auth', 'coordinator')->name('groups.destroy');
Route::get('/groups/archived', [\App\Http\Controllers\GroupController::class, 'archived'])->middleware('auth', 'coordinator')->name('groups.archived');
Route::post('/groups/{id}/restore', [\App\Http\Controllers\GroupController::class, 'restore'])->middleware('auth', 'coordinator')->name('groups.restore');
Route::get('/api/groups/by-number/{groupNumber}', [\App\Http\Controllers\GroupController::class, 'getByGroupNumber'])->middleware('auth')->name('groups.getByNumber');
Route::get('/api/groups', [\App\Http\Controllers\GroupController::class, 'getGroups'])->middleware('auth')->name('groups.get');

// Cluster Management Routes
Route::post('/clusters', [CoordinatorController::class, 'storeCluster'])->middleware('auth', 'coordinator')->name('clusters.store');
Route::get('/clusters', [CoordinatorController::class, 'getClusters'])->middleware('auth', 'coordinator')->name('clusters.get');
