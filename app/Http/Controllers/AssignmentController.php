<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\AssignmentPanel;
use App\Models\Panel;
use App\Models\Adviser;

class AssignmentController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'department' => 'required|string',
                'section' => 'required|string',
                'adviser_id' => 'required|integer',
                'chairperson_id' => 'required|integer',
                'panel_ids' => 'required|array',
                'panel_ids.*' => 'integer',
            ]);

            // Create the main assignment
            $assignment = Assignment::create([
                'department' => $validated['department'],
                'section' => $validated['section'],
                'adviser_id' => $validated['adviser_id'],
            ]);

            // Add chairperson
            $chairperson = Panel::find($validated['chairperson_id']);
            if ($chairperson) {
                AssignmentPanel::create([
                    'assignment_id' => $assignment->id,
                    'panel_id' => $validated['chairperson_id'],
                    'name' => $chairperson->name,
                    'availability' => is_array($chairperson->availability)
                        ? json_encode($chairperson->availability)
                        : $chairperson->availability,
                    'role' => 'Chairperson',
                    'expertise' => $chairperson->expertise,
                    'department' => $validated['department'],
                    'section' => $validated['section'],
                ]);
            }

            // Add panel members
            foreach ($validated['panel_ids'] as $panelId) {
                $panel = Panel::find($panelId);
                if ($panel) {
                    AssignmentPanel::create([
                        'assignment_id' => $assignment->id,
                        'panel_id' => $panelId,
                        'name' => $panel->name,
                        'availability' => is_array($panel->availability)
                            ? json_encode($panel->availability)
                            : $panel->availability,
                        'role' => 'Member',
                        'expertise' => $panel->expertise,
                        'department' => $validated['department'],
                        'section' => $validated['section'],
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Assignment finalized successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }

    public function destroy(Assignment $assignment)
    {
        try {
            // Delete related assignment panels first
            AssignmentPanel::where('assignment_id', $assignment->id)->delete();
            
            // Delete the assignment
            $assignment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Assignment deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Assignment $assignment)
    {
        try {
            $validated = $request->validate([
                'department' => 'required|string',
                'section' => 'required|string',
                'adviser_id' => 'required|integer',
                'chairperson_id' => 'required|integer',
                'panel_ids' => 'required|array',
                'panel_ids.*' => 'integer',
            ]);

            $assignment->update([
                'department' => $validated['department'],
                'section' => $validated['section'],
                'adviser_id' => $validated['adviser_id'],
            ]);

            AssignmentPanel::where('assignment_id', $assignment->id)->delete();

            // Add chairperson
            $chairperson = Panel::find($validated['chairperson_id']);
            if ($chairperson) {
                AssignmentPanel::create([
                    'assignment_id' => $assignment->id,
                    'panel_id' => $validated['chairperson_id'],
                    'name' => $chairperson->name,
                    'availability' => is_array($chairperson->availability) ? json_encode($chairperson->availability) : $chairperson->availability,
                    'role' => 'Chairperson',
                    'expertise' => $chairperson->expertise,
                    'department' => $validated['department'],
                    'section' => $validated['section'],
                ]);
            }

            // Add panel members
            foreach ($validated['panel_ids'] as $panelId) {
                $panel = Panel::find($panelId);
                if ($panel) {
                    AssignmentPanel::create([
                        'assignment_id' => $assignment->id,
                        'panel_id' => $panelId,
                        'name' => $panel->name,
                        'availability' => is_array($panel->availability) ? json_encode($panel->availability) : $panel->availability,
                        'role' => 'Member',
                        'expertise' => $panel->expertise,
                        'department' => $validated['department'],
                        'section' => $validated['section'],
                    ]);
                }
            }

            return redirect()->route('panel-adviser')->with('success', 'Assignment updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updatePanels(Request $request, $id)
    {
        try {
            $assignment = Assignment::findOrFail($id);
            
            // Update chairperson if provided
            if ($request->has('chairperson_id')) {
                $chairperson = Panel::find($request->chairperson_id);
                if ($chairperson) {
                    AssignmentPanel::where('assignment_id', $assignment->id)
                        ->where('role', 'Chairperson')
                        ->delete();
                    
                    AssignmentPanel::create([
                        'assignment_id' => $assignment->id,
                        'panel_id' => $chairperson->id,
                        'name' => $chairperson->name,
                        'availability' => is_array($chairperson->availability) ? json_encode($chairperson->availability) : $chairperson->availability,
                        'role' => 'Chairperson',
                        'expertise' => $chairperson->expertise,
                        'department' => $assignment->department,
                        'section' => $assignment->section,
                    ]);
                }
            }
            
            // Update members if provided
            if ($request->has('member_ids') && is_array($request->member_ids)) {
                AssignmentPanel::where('assignment_id', $assignment->id)
                    ->where('role', 'Member')
                    ->delete();
                
                foreach ($request->member_ids as $panelId) {
                    $panel = Panel::find($panelId);
                    if ($panel) {
                        AssignmentPanel::create([
                            'assignment_id' => $assignment->id,
                            'panel_id' => $panel->id,
                            'name' => $panel->name,
                            'availability' => is_array($panel->availability) ? json_encode($panel->availability) : $panel->availability,
                            'role' => 'Member',
                            'expertise' => $panel->expertise,
                            'department' => $assignment->department,
                            'section' => $assignment->section,
                        ]);
                    }
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Panel assignment updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
