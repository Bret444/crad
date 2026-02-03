<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Panel;

class PanelController extends Controller
{
    public function store(Request $request) {
        $availability = json_decode($request->availability, true) ?? [];
        $expertise = $request->expertise === 'Others' && $request->others_expertise ? $request->others_expertise : $request->expertise;
        
        Panel::create([
            'department' => $request->department,
            'name' => $request->name,
            'expertise' => $expertise,
            'availability' => $availability,
            'role' => $request->role,
            'contact_number' => $request->contact_number
        ]);
        return back()->with('success', 'Panel member added successfully.');
    }

    public function update(Request $request, $id)
    {
        try {
            $panel = Panel::findOrFail($id);
            
            $expertise = $request->expertise === 'Others' && $request->others_expertise ? $request->others_expertise : $request->expertise;
            
            $panel->department = $request->department;
            $panel->name = $request->name;
            $panel->expertise = $expertise;
            
            if ($request->availability) {
                $decoded = json_decode($request->availability, true);
                $panel->availability = is_array($decoded) ? $decoded : [];
            } else {
                $panel->availability = [];
            }
            
            $panel->role = $request->role;
            $panel->contact_number = $request->contact_number;
            $panel->save();
        
            return redirect()->route('panel-adviser')->with('success', 'Panel member updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Panel update error: ' . $e->getMessage());
            return back()->with('error', 'Error updating panel: ' . $e->getMessage());
        }
    }

    public function destroy(Panel $panel)
    {
        try {
            $panel->delete();
            return response()->json([
                'success' => true,
                'message' => 'Panel member deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete panel member: ' . $e->getMessage()
            ], 500);
        }
    }

    public function archived()
    {
        $archivedPanels = Panel::onlyTrashed()->get();
        return response()->json($archivedPanels);
    }

    public function restore($id)
    {
        try {
            $panel = Panel::onlyTrashed()->findOrFail($id);
            $panel->restore();
            return response()->json([
                'success' => true,
                'message' => 'Panel member restored successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore panel member: ' . $e->getMessage()
            ], 500);
        }
    }

    public function forceDelete($id)
    {
        try {
            $panel = Panel::onlyTrashed()->findOrFail($id);
            $panel->forceDelete();
            return response()->json([
                'success' => true,
                'message' => 'Panel member permanently deleted'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to permanently delete panel member: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPanels(Request $request)
    {
        $query = Panel::whereNull('deleted_at');
        
        if ($request->has('department')) {
            $query->where('department', $request->department);
        }
        
        return response()->json($query->get());
    }

    public function getPanelsByCluster($clusterId)
{
    $panels = Panel::with('cluster')->get(); 
    return $panels->first()->cluster->name ?? null; }
}
