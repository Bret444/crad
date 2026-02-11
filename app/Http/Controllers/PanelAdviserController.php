<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Panel;
use App\Models\Adviser;

class PanelAdviserController extends Controller
{
    public function index()
    {
        $advisers = Adviser::all();
        $panels = Panel::all();
        $assignments = Assignment::with(['adviser', 'assignmentPanels'])->orderBy('created_at', 'desc')->get();
        
        return view('panel-adviser', compact('advisers', 'panels', 'assignments'));
    }

    public function showPanelAdviserPage(Request $request)
    {
        $type = $request->query('type');
        
        $advisers = Adviser::all();
        $panels = Panel::all();
        $assignments = Assignment::with(['adviser', 'assignmentPanels'])->orderBy('created_at', 'desc')->get();
        $notifications = collect();
        $clusters = \App\Models\Cluster::orderBy('cluster_number')->get();
        
        return view('panel-adviser', compact('advisers', 'panels', 'assignments', 'type', 'notifications', 'clusters'));
    }

    public function apiAssignments(Request $request)
    {
        $query = Assignment::with(['adviser', 'assignmentPanels']);
        
        if ($request->has('department')) {
            $query->where('department', $request->department);
        }
        
        if ($request->has('section')) {
            $query->where('section', $request->section);
        }
        
        $assignments = $query->orderBy('created_at', 'desc')->get();

        $payload = $assignments->map(function ($a) {
            $panels = $a->assignmentPanels->map(function ($ap) {
                return [
                    'id' => $ap->id,
                    'name' => $ap->name,
                    'role' => $ap->role,
                    'expertise' => $ap->expertise,
                ];
            });

            return [
                'id' => $a->id,
                'department' => $a->department,
                'section' => $a->section,
                'adviser' => $a->adviser ? $a->adviser->name : null,
                'panels' => $panels,
                'group_number' => $a->group_number,
                'created_at' => $a->created_at ? $a->created_at->toDateTimeString() : null,
            ];
        });

        return response()->json($payload);
    }
}

