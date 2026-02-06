<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function getByGroupNumber($groupNumber, Request $request)
    {
        $department = $request->get('department', 'BSIT');
        
        $group = Group::where('group_number', $groupNumber)
                    ->where('department', $department)
                    ->first();
        
        if ($group) {
            return response()->json([
                'success' => true,
                'group' => $group
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Group not found'
        ], 404);
    }
    
    public function getGroups(Request $request)
    {
        $department = $request->get('department');
        $cluster = $request->get('cluster');
        
        if (!$department || !$cluster) {
            return response()->json([
                'success' => false,
                'message' => 'Department and cluster are required',
                'groups' => []
            ], 400);
        }
        
        $groups = Group::where('department', $department)
                    ->where('cluster', $cluster)
                    ->orderBy('group_id')
                    ->get();
        
        return response()->json([
            'success' => true,
            'groups' => $groups
        ]);
    }
    
    public function index()
    {
        $coordinatorDepartment = auth()->user()->department;
        $groups = Group::where('department', $coordinatorDepartment)->orderBy('group_number')->get();
        return view('coordinator-manage-groups', compact('coordinatorDepartment', 'groups'));
    }
    
    public function store(Request $request)
    {
        try {
            $request->validate([
                'group_id' => 'required|string|unique:groups,group_id',
                'password' => 'required|string',
                'group_number' => 'required|integer'
            ]);
            
            $department = auth()->user()->department;
            $groupNumber = $request->group_number;
            
            // Calculate cluster based on group number (1-10 = section 1, 11-20 = section 2, etc.)
            if ($department === 'BSIT') {
                $cluster = 4100 + ceil($groupNumber / 10);
            } else {
                $cluster = 'Section ' . ceil($groupNumber / 10);
            }
            
            $hashedPassword = bcrypt($request->password);
            
            $group = Group::create([
                'group_id' => $request->group_id,
                'password' => $hashedPassword,
                'department' => $department,
                'cluster' => $cluster,
                'group_number' => $groupNumber,
                'member1_name' => $request->member1_name,
                'member1_student_id' => $request->member1_student_id,
                'member2_name' => $request->member2_name,
                'member2_student_id' => $request->member2_student_id,
                'member3_name' => $request->member3_name,
                'member3_student_id' => $request->member3_student_id,
                'member4_name' => $request->member4_name,
                'member4_student_id' => $request->member4_student_id,
                'member5_name' => $request->member5_name,
                'member5_student_id' => $request->member5_student_id,
                'leader_member' => $request->leader_member
            ]);
            
            // Create user account for group login
            \App\Models\User::create([
                'name' => $request->group_id,
                'email' => 's' . $request->group_id,
                'password' => $hashedPassword,
                'role' => 'student',
                'department' => $department,
                'cluster' => $cluster,
                'group_no' => $groupNumber,
                'group_id' => $request->group_id
            ]);
            
            return redirect()->route('coordinator-manage-groups')->with('success', 'Group created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    
    public function update($id, Request $request)
    {
        try {
            $request->validate([
                'group_number' => 'required|integer'
            ]);
            
            $group = Group::findOrFail($id);
            $department = auth()->user()->department;
            $groupNumber = $request->group_number;
            
            // Calculate cluster based on group number (1-10 = section 1, 11-20 = section 2, etc.)
            if ($department === 'BSIT') {
                $cluster = 4100 + ceil($groupNumber / 10);
            } else {
                $cluster = 'Section ' . ceil($groupNumber / 10);
            }
            
            $group->update([
                'cluster' => $cluster,
                'group_number' => $groupNumber,
                'member1_name' => $request->member1_name,
                'member1_student_id' => $request->member1_student_id,
                'member2_name' => $request->member2_name,
                'member2_student_id' => $request->member2_student_id,
                'member3_name' => $request->member3_name,
                'member3_student_id' => $request->member3_student_id,
                'member4_name' => $request->member4_name,
                'member4_student_id' => $request->member4_student_id,
                'member5_name' => $request->member5_name,
                'member5_student_id' => $request->member5_student_id,
                'leader_member' => $request->leader_member
            ]);
            
            return redirect()->route('coordinator-manage-groups')->with('success', 'Group updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    
    public function destroy($id)
    {
        try {
            $group = Group::findOrFail($id);
            $group->delete();
            return response()->json(['success' => true, 'message' => 'Group archived successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function archived()
    {
        // Implementation for archived groups
    }
    
    public function restore($id)
    {
        // Implementation for restoring groups
    }
    
    public function getDocumentStatus(Request $request)
    {
        $groupId = $request->get('group_id');
        $defenseType = $request->get('defense_type');
        
        if (!$groupId || !$defenseType) {
            return response()->json(['status' => 'Incomplete']);
        }
        
        // Get all submissions for this group and defense type
        $submissions = \App\Models\Submission::where('group_no', $groupId)
            ->where('defense_type', $defenseType)
            ->get();
        
        if ($submissions->isEmpty()) {
            return response()->json(['status' => 'Incomplete']);
        }
        
        // Check if all submissions are approved by both coordinator and admin
        $allApproved = true;
        $hasPending = false;
        
        foreach ($submissions as $submission) {
            // Check if document needs admin approval (clearance, research forum)
            $needsAdmin = stripos($submission->documents, 'clearance') !== false || 
                         stripos($submission->documents, 'research forum') !== false;
            
            if ($submission->status === 'Pending') {
                $hasPending = true;
                $allApproved = false;
            } elseif ($submission->status !== 'Approved') {
                $allApproved = false;
            }
        }
        
        if ($allApproved) {
            return response()->json(['status' => 'Complete']);
        } elseif ($hasPending) {
            return response()->json(['status' => 'Pending']);
        } else {
            return response()->json(['status' => 'Incomplete']);
        }
    }
}