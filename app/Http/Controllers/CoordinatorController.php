<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Adviser;
use App\Models\Assignment;
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
        $totalGroups = \App\Models\Group::where('department', $department)->whereNull('deleted_at')->count();
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
            ->whereNull('deleted_at')
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
        
        if ($request->has('status')) {
            $submission->status = $request->status;
        }
        
        if ($request->has('feedback')) {
            $submission->feedback = $request->feedback;
        }
        
        $submission->save();
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
}
