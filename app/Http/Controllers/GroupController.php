<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GroupController extends Controller
{
    public function index()
    {
        $coordinatorDepartment = auth()->user()->department;
        $groups = Group::where('department', $coordinatorDepartment)
                      ->orderBy('created_at', 'desc')
                      ->get();
        return view('coordinator-manage-groups', compact('groups', 'coordinatorDepartment'));
    }

    public function store(Request $request)
    {
        $coordinatorDepartment = auth()->user()->department;
        
        $request->validate([
            'group_id' => 'required|unique:groups,group_id',
            'group_number' => 'required',
            'password' => 'required|min:6',
            'leader_member' => 'required|integer|min:1|max:5',
        ]);

        $hashedPassword = Hash::make($request->password);

        $group = Group::create([
            'group_id' => $request->group_id,
            'group_number' => $request->group_number,
            'password' => $hashedPassword,
            'department' => $coordinatorDepartment,
            'leader_member' => $request->leader_member,
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
        ]);

        $this->syncStudentAccounts($group, $hashedPassword);

        return redirect()->back()->with('success', 'Group created successfully!');
    }

    public function update(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        $coordinatorDepartment = auth()->user()->department;

        $request->validate([
            'group_id' => 'required|unique:groups,group_id,' . $id,
            'group_number' => 'required',
            'leader_member' => 'required|integer|min:1|max:5',
        ]);

        $data = [
            'group_id' => $request->group_id,
            'group_number' => $request->group_number,
            'department' => $coordinatorDepartment,
            'leader_member' => $request->leader_member,
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
        ];

        $hashedPassword = $group->password;
        if ($request->filled('password')) {
            $hashedPassword = Hash::make($request->password);
            $data['password'] = $hashedPassword;
        }

        $group->update($data);
        $this->syncStudentAccounts($group, $hashedPassword);

        return redirect()->back()->with('success', 'Group updated successfully!');
    }

    public function destroy($id)
    {
        $group = Group::findOrFail($id);
        $group->delete();

        return response()->json(['success' => true]);
    }

    public function archived()
    {
        $coordinatorDepartment = auth()->user()->department;
        $groups = Group::onlyTrashed()
                      ->where('department', $coordinatorDepartment)
                      ->get();
        return response()->json($groups);
    }

    public function restore($id)
    {
        $group = Group::withTrashed()->findOrFail($id);
        $group->restore();

        return response()->json(['success' => true]);
    }

    public function getByGroupNumber(Request $request, $groupNumber)
    {
        $department = $request->query('department');
        
        $group = Group::where('group_number', $groupNumber)
            ->where('department', $department)
            ->first();
        
        if ($group) {
            return response()->json(['success' => true, 'group' => $group]);
        }
        
        return response()->json(['success' => false, 'message' => 'Group not found'], 404);
    }

    private function syncStudentAccounts($group, $hashedPassword)
    {
        $loginUsername = 's' . $group->group_id;
        
        User::updateOrCreate(
            ['email' => $loginUsername],
            [
                'name' => $group->group_id,
                'password' => $hashedPassword,
                'role' => 'student',
                'department' => $group->department,
                'group_no' => $group->group_number,
            ]
        );
    }
}
