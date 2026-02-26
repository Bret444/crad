<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Notification;

class FeedbackController extends Controller
{
    public function sendFeedback(Request $request)
    {
        $request->validate([
            'group_id' => 'required',
            'department' => 'required',
            'cluster' => 'required',
            'feedback' => 'required|string'
        ]);

        $groupId = $request->group_id;
        $department = $request->department;
        $cluster = $request->cluster;
        $feedback = $request->feedback;

        // Find the group
        $group = Group::where('group_id', $groupId)
            ->where('department', $department)
            ->where('cluster', $cluster)
            ->first();

        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => 'Group not found'
            ], 404);
        }

        // Create notification for the group
        Notification::create([
            'user_id' => $group->id,
            'type' => 'defense_feedback',
            'message' => "Defense Feedback: {$feedback}",
            'read' => false,
            'data' => json_encode([
                'group_id' => $groupId,
                'department' => $department,
                'cluster' => $cluster,
                'feedback' => $feedback
            ])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback sent successfully'
        ]);
    }
}
