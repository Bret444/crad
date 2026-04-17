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
            $email = 's' . $request->group_id;
            
            $group = Group::create([
                'group_id' => $request->group_id,
                'email' => $email,
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
                'member6_name' => $request->member6_name,
                'member6_student_id' => $request->member6_student_id,
                'member7_name' => $request->member7_name,
                'member7_student_id' => $request->member7_student_id,
                'member8_name' => $request->member8_name,
                'member8_student_id' => $request->member8_student_id,
                'member9_name' => $request->member9_name,
                'member9_student_id' => $request->member9_student_id,
                'member10_name' => $request->member10_name,
                'member10_student_id' => $request->member10_student_id,
                'leader_member' => $request->leader_member
            ]);
            
            // Create user record for login
            \App\Models\User::create([
                'name' => 'Group ' . $request->group_id,
                'email' => $email,
                'password' => $hashedPassword,
                'role' => 'student',
                'department' => $department,
                'cluster' => $cluster,
                'group_no' => $groupNumber
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
            
            $updateData = [
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
                'member6_name' => $request->member6_name,
                'member6_student_id' => $request->member6_student_id,
                'member7_name' => $request->member7_name,
                'member7_student_id' => $request->member7_student_id,
                'member8_name' => $request->member8_name,
                'member8_student_id' => $request->member8_student_id,
                'member9_name' => $request->member9_name,
                'member9_student_id' => $request->member9_student_id,
                'member10_name' => $request->member10_name,
                'member10_student_id' => $request->member10_student_id,
                'leader_member' => $request->leader_member
            ];
            
            if ($request->filled('password')) {
                $hashedPassword = bcrypt($request->password);
                $updateData['password'] = $hashedPassword;
                
                // Update user password
                $user = \App\Models\User::where('email', $group->email)->first();
                if ($user) {
                    $user->update(['password' => $hashedPassword]);
                }
            }
            
            $group->update($updateData);
            
            // Update user record
            $user = \App\Models\User::where('email', $group->email)->first();
            if ($user) {
                $user->update([
                    'cluster' => $cluster,
                    'group_no' => $groupNumber
                ]);
            }
            
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
        $coordinatorDepartment = auth()->user()->department;
        $archivedGroups = Group::onlyTrashed()
            ->where('department', $coordinatorDepartment)
            ->orderBy('deleted_at', 'desc')
            ->get();
        return response()->json($archivedGroups);
    }
    
    public function assignedStudents()
    {
        $coordinatorDepartment = auth()->user()->department;
        $assigned = [];
        
        $groups = Group::where('department', $coordinatorDepartment)->get();
        foreach ($groups as $group) {
            for ($i = 1; $i <= 10; $i++) {
                $studentId = $group->{"member{$i}_student_id"};
                if ($studentId) {
                    $assigned[] = $studentId;
                }
            }
        }
        
        return response()->json(['assigned' => $assigned]);
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
        
        // Convert group_id (e.g., "A1") to numeric group_no
        $groupNo = (int) preg_replace('/[^0-9]/', '', $groupId);
        
        // Define required documents for each defense type
        $requiredDocs = [
            'PRE-ORAL' => ['Research Title Proposal', 'Research Forum', 'Clearance', 'Manuscript Chapter 1-3'],
            'FINAL DEFENSE' => ['Summary of Corrections', 'Clearance', 'Manuscript Chapter 1-5'],
            'REDEFENSE' => ['Summary of Corrections', 'Clearance']
        ];
        
        $required = $requiredDocs[$defenseType] ?? [];
        
        if (empty($required)) {
            return response()->json(['status' => 'Incomplete']);
        }
        
        // Get all submissions for this group with matching defense type
        $submissions = \App\Models\Submission::where('group_no', $groupNo)
            ->where('defense_type', $defenseType)
            ->get();
        
        if ($submissions->isEmpty()) {
            return response()->json(['status' => 'Incomplete']);
        }
        
        // Check each required document
        $approvedCount = 0;
        $pendingCount = 0;
        
        foreach ($required as $docName) {
            $submission = $submissions->where('documents', $docName)->first();
            
            if (!$submission) {
                // Document not submitted
                continue;
            }
            
            if ($submission->status === 'Approved') {
                $approvedCount++;
            } elseif ($submission->status === 'Pending') {
                $pendingCount++;
            }
        }
        
        // All required documents are approved
        if ($approvedCount === count($required)) {
            return response()->json(['status' => 'Complete']);
        }
        
        // Some documents are pending
        if ($pendingCount > 0 && ($approvedCount + $pendingCount) === count($required)) {
            return response()->json(['status' => 'Pending']);
        }
        
        // Not all documents submitted or some rejected
        return response()->json(['status' => 'Incomplete']);
    }
}