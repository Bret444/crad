<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Adviser;
use App\Models\Assignment;
use App\Models\Cluster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class CoordinatorController extends Controller
{
    public function index()
    {
        $coordinators = User::where('role', 'coordinator')->whereNull('deleted_at')->get();
        return view('manage-coordinator', compact('coordinators'));
    }

    public function dashboard()
    {
        $coordinator = Auth::user();
        $department = $coordinator->department;
        
        // Count statistics
        $totalAdvisers = Adviser::where('department', $department)->whereNull('deleted_at')->count();
        $totalPanels = \App\Models\Panel::where('department', $department)->whereNull('deleted_at')->count();
        $totalGroups = \App\Models\Group::where('department', $department)->count();
        $totalSubmissions = \App\Models\Submission::where('department', $department)->count();
        
        // Pending submissions
        $pendingSubmissions = \App\Models\Submission::where('department', $department)
            ->where('status', 'Pending')
            ->count();
        
        // Recent submissions
        $recentSubmissions = \App\Models\Submission::with('user')
            ->where('department', $department)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Defense schedules
        $upcomingDefenses = \App\Models\DefenseSchedule::where('department', $department)
            ->where('defense_date', '>=', now())
            ->whereNotNull('defense_date')
            ->where('defense_date', '!=', '1970-01-01')
            ->orderBy('defense_date')
            ->limit(5)
            ->get();
        
        return view('coordinator-dashboard', compact(
            'totalAdvisers',
            'totalPanels',
            'totalGroups',
            'totalSubmissions',
            'pendingSubmissions',
            'recentSubmissions',
            'upcomingDefenses'
        ));
    }

    public function archivesData()
    {
        $coordinators = User::where('role', 'coordinator')->onlyTrashed()->get();
        return response()->json(['coordinators' => $coordinators]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'department' => 'required|string',
            'contact' => 'nullable|string'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'coordinator',
            'department' => $request->department,
            'contact' => $request->contact
        ]);

        return response()->json(['success' => true, 'message' => 'Coordinator created successfully']);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Coordinator archived successfully']);
    }

    public function restore($id)
    {
        User::withTrashed()->findOrFail($id)->restore();
        return response()->json(['success' => true, 'message' => 'Coordinator restored successfully']);
    }

    public function update(Request $request, $id)
    {
        $coordinator = User::findOrFail($id);
        
        // Check if password reset is requested
        if ($request->has('password')) {
            $request->validate([
                'password' => 'required|min:8|confirmed'
            ]);
            
            $coordinator->update([
                'password' => Hash::make($request->password)
            ]);
        } else {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'department' => 'required|string',
                'contact' => 'nullable|string'
            ]);
            
            $coordinator->update([
                'name' => $request->name,
                'email' => $request->email,
                'department' => $request->department,
                'contact' => $request->contact
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Coordinator updated successfully']);
    }

    public function manageAdviser()
    {
        $coordinator = Auth::user();
        $advisers = Adviser::where('department', $coordinator->department)->whereNull('deleted_at')->orderBy('id')->get();
        $assignments = Assignment::where('department', $coordinator->department)->get();
        return view('coordinator-manage-adviser', compact('advisers', 'assignments'));
    }

    public function titleProposal()
    {
        $coordinator = Auth::user();
        $groups = \App\Models\Group::where('department', $coordinator->department)
            ->orderBy('group_number')
            ->get();
        return view('coordinator-title-proposal', compact('groups'));
    }

    public function getSubmissionsByGroup($group)
    {
        $coordinator = Auth::user();
        $submissions = \App\Models\Submission::with('user')
            ->where('group_no', $group)
            ->where('department', $coordinator->department)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => $submissions->isNotEmpty(),
            'submissions' => $submissions
        ]);
    }

    public function updateSubmissionStatus(Request $request, $id)
    {
        $submission = \App\Models\Submission::findOrFail($id);
        $coordinator = Auth::user();
        
        // Coordinator can only approve/reject non-clearance documents
        if (stripos($submission->documents, 'clearance') !== false) {
            return response()->json([
                'success' => false, 
                'message' => 'Only Admin can handle clearance documents'
            ], 403);
        }
        
        if ($request->has('status')) {
            $submission->status = $request->status;
        }
        
        if ($request->has('feedback')) {
            $submission->feedback = $request->feedback;
        }
        
        $submission->save();
        
        // Notify student
        $message = match($request->status) {
            'Approved' => "Your submission '{$submission->documents}' has been approved by coordinator.",
            'Rejected' => "Your submission '{$submission->documents}' has been rejected. Please revise and resubmit.",
            default => "Feedback received for '{$submission->documents}'"
        };
        
        \App\Models\Notification::create([
            'user_id' => $submission->user_id,
            'type' => strtolower($request->status ?? 'feedback'),
            'message' => $message,
            'submission_id' => $submission->id,
            'read' => false
        ]);
        
        // Notify admin
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $adminMessage = match($request->status) {
                'Approved' => "Coordinator approved '{$submission->documents}' from Group {$submission->group_no} ({$submission->department}).",
                'Rejected' => "Coordinator rejected '{$submission->documents}' from Group {$submission->group_no} ({$submission->department}).",
                default => "Coordinator added feedback to '{$submission->documents}' from Group {$submission->group_no} ({$submission->department})."
            };
            
            \App\Models\Notification::create([
                'user_id' => $admin->id,
                'type' => strtolower($request->status ?? 'feedback'),
                'message' => $adminMessage,
                'submission_id' => $submission->id,
                'read' => false
            ]);
        }
        
        return response()->json(['success' => true]);
    }

    public function downloadSubmission($id)
    {
        $submission = \App\Models\Submission::findOrFail($id);
        $filePath = storage_path('app/public/' . $submission->file_path);
        
        if (!file_exists($filePath)) {
            abort(404);
        }
        
        return response()->download($filePath, basename($submission->file_path));
    }

    public function storeCluster(Request $request)
    {
        try {
            $request->validate([
                'cluster_number' => 'required|string'
            ]);

            $coordinator = Auth::user();
            
            // Check if cluster already exists for this department
            $existingCluster = Cluster::where('cluster_number', $request->cluster_number)
                ->where('department', $coordinator->department)
                ->first();
                
            if ($existingCluster) {
                return response()->json(['success' => false, 'message' => 'Cluster already exists for this department']);
            }
            
            Cluster::create([
                'cluster_number' => $request->cluster_number,
                'department' => $coordinator->department
            ]);

            return response()->json(['success' => true, 'message' => 'Cluster added successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getClusters()
    {
        $coordinator = Auth::user();
        $clusters = Cluster::where('department', $coordinator->department)
            ->orderBy('cluster_number')
            ->pluck('cluster_number');
        
        return response()->json(['clusters' => $clusters]);
    }

    public function getNotifications()
    {
        $notifications = \App\Models\Notification::with('submission')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => \App\Models\Notification::where('user_id', auth()->id())->where('read', false)->count()
        ]);
    }

    public function subjectLoadProxy()
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://fms3.jampzdev.com/api/subject-load-tracker/list');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode === 401) {
                return response()->json(['error' => 'API requires authentication', 'status' => 401], 401);
            }
            
            if ($httpCode !== 200) {
                return response()->json(['error' => 'API returned status ' . $httpCode, 'status' => $httpCode], 500);
            }
            
            $data = json_decode($response, true);
            return response()->json($data ?: []);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
