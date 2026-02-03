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
        
        return view('panel-adviser', compact('advisers', 'panels', 'assignments', 'type', 'notifications'));
    }

    public function apiAssignments(Request $request)
    {
        $assignments = Assignment::with(['adviser', 'assignmentPanels'])->orderBy('created_at', 'desc')->get();

        $payload = $assignments->map(function ($a) {
            // Get panels from assignmentPanels relationship
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
                'created_at' => $a->created_at ? $a->created_at->toDateTimeString() : null,
            ];
        });

        return response()->json($payload);
    }
}

