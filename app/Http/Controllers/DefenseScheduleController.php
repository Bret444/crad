<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\DefenseSchedule;
use App\Models\Panel;
use Illuminate\Http\Request;

class DefenseScheduleController extends Controller
{
    public function index()
    {
        try {
            $assignments = Assignment::with(['adviser', 'assignmentPanels'])->get();
            $defenseSchedules = DefenseSchedule::with('assignment.adviser', 'assignment.assignmentPanels')->get();
            
            return view('def-sched', compact('assignments', 'defenseSchedules'));
        } catch (\Exception $e) {
            \Log::error('DefenseScheduleController index error: ' . $e->getMessage());
            return view('def-sched', ['assignments' => collect(), 'defenseSchedules' => collect()]);
        }
    }
    


    public function store(Request $request)
    {
        try {
            \Log::info('Defense Schedule Store Request', $request->all());
            
            if (!\Schema::hasTable('defense_schedules')) {
                \Log::error('defense_schedules table does not exist');
                return response()->json(['success' => false, 'error' => 'Database table does not exist. Please run migrations.'], 500);
            }
            
            $validated = $request->validate([
                'department' => 'required|string',
                'section' => 'required|string', 
                'group_id' => 'required|string',
                'defense_type' => 'required|in:PRE-ORAL,FINAL DEFENSE,REDEFENSE',
                'original_defense_type' => 'nullable|string',
                'assignment_id' => 'nullable|integer',
                'defense_date' => 'nullable|date',
                'start_time' => 'nullable',
                'end_time' => 'nullable',
                'set_letter' => 'nullable|string|max:1',
                'status' => 'nullable|string',
                'panel_data' => 'nullable|array'
            ]);
            
            // Check for time conflicts
            if ($validated['defense_date'] && $validated['start_time'] && $validated['end_time'] && isset($validated['panel_data'])) {
                $panelData = $validated['panel_data'];
                $panelNames = array_filter([
                    $panelData['adviser'] ?? null,
                    $panelData['chairperson'] ?? null
                ]);
                if (isset($panelData['members'])) {
                    $members = explode(',', $panelData['members']);
                    $panelNames = array_merge($panelNames, array_map('trim', $members));
                }
                
                $conflicts = $this->checkTimeConflicts(
                    $validated['defense_date'],
                    $validated['start_time'],
                    $validated['end_time'],
                    $panelNames,
                    $validated['group_id'],
                    $validated['defense_type']
                );
                
                if (!empty($conflicts)) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Schedule conflict detected',
                        'conflicts' => $conflicts
                    ], 422);
                }
            }
            
            if (!$validated['assignment_id']) {
                $validated['assignment_id'] = 1;
            }
            
            if (!isset($validated['status'])) {
                $validated['status'] = 'scheduled';
            }

            // Check if schedule already exists
            $existingSchedule = DefenseSchedule::where('department', $validated['department'])
                ->where('section', $validated['section'])
                ->where('group_id', $validated['group_id'])
                ->where('defense_type', $validated['defense_type'])
                ->first();
            
            if ($existingSchedule) {
                // Update existing schedule
                $existingSchedule->update($validated);
                $schedule = $existingSchedule;
            } else {
                // Create new schedule
                $schedule = DefenseSchedule::create($validated);
            }

            return response()->json(['success' => true, 'schedule' => $schedule]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'error' => 'Validation failed', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            \Log::error('Schedule Save Error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
    
    private function checkTimeConflicts($date, $startTime, $endTime, $panelNames, $excludeGroupId, $excludeDefenseType)
    {
        $conflicts = [];
        
        foreach ($panelNames as $panelName) {
            $query = DefenseSchedule::where('defense_date', $date)
                ->whereNotNull('start_time')
                ->whereNotNull('end_time')
                ->where(function($q) use ($startTime, $endTime) {
                    $q->where(function($sub) use ($startTime, $endTime) {
                        $sub->where('start_time', '<=', $startTime)
                            ->where('end_time', '>', $startTime);
                    })->orWhere(function($sub) use ($startTime, $endTime) {
                        $sub->where('start_time', '<', $endTime)
                            ->where('end_time', '>=', $endTime);
                    })->orWhere(function($sub) use ($startTime, $endTime) {
                        $sub->where('start_time', '>', $startTime)
                            ->where('end_time', '<', $endTime);
                    });
                })
                ->where(function($q) use ($excludeGroupId, $excludeDefenseType) {
                    $q->where('group_id', '!=', $excludeGroupId)
                      ->orWhere('defense_type', '!=', $excludeDefenseType);
                })
                ->get();
            
            foreach ($query as $schedule) {
                if ($schedule->panel_data) {
                    $panelData = is_string($schedule->panel_data) ? json_decode($schedule->panel_data, true) : $schedule->panel_data;
                    
                    if ((isset($panelData['adviser']) && $panelData['adviser'] === $panelName) ||
                        (isset($panelData['chairperson']) && $panelData['chairperson'] === $panelName) ||
                        (isset($panelData['members']) && strpos($panelData['members'], $panelName) !== false)) {
                        $conflicts[] = "$panelName is already scheduled for {$schedule->defense_type} Group {$schedule->group_id} at {$schedule->start_time}-{$schedule->end_time}";
                    }
                }
            }
        }
        
        return $conflicts;
    }

    public function update(Request $request, DefenseSchedule $defenseSchedule)
    {
        $request->validate([
            'status' => 'required|in:Pending,Scheduled,Passed,Failed,Re-defense'
        ]);

        $defenseSchedule->update($request->only('status'));

        // Handle status changes
        if ($request->status === 'Passed' && $defenseSchedule->defense_type === 'PRE-ORAL') {
            // Update cluster for BSIT groups after passing pre-oral
            $group = \App\Models\Group::where('group_number', $defenseSchedule->group_id)
                ->where('department', $defenseSchedule->department)
                ->first();
            
            if ($group && $group->department === 'BSIT') {
                $newCluster = $group->updateClusterAfterPreOral();
                $defenseSchedule->section = $newCluster;
                $defenseSchedule->save();
            }
            
            // Check if FINAL DEFENSE already exists for this group
            $existingFinalDefense = DefenseSchedule::where([
                'department' => $defenseSchedule->department,
                'section' => $defenseSchedule->section,
                'group_id' => $defenseSchedule->group_id,
                'defense_type' => 'FINAL DEFENSE'
            ])->first();
            
            // Create FINAL DEFENSE only if it doesn't exist
            if (!$existingFinalDefense) {
                DefenseSchedule::create([
                    'department' => $defenseSchedule->department,
                    'section' => $defenseSchedule->section,
                    'group_id' => $defenseSchedule->group_id,
                    'defense_type' => 'FINAL DEFENSE',
                    'assignment_id' => $defenseSchedule->assignment_id,
                    'panel_data' => $defenseSchedule->panel_data,
                    'set_letter' => $defenseSchedule->set_letter,
                    'status' => 'Pending'
                ]);
            }
        } elseif ($request->status === 'Re-defense') {
            // Move to REDEFENSE
            DefenseSchedule::create([
                'department' => $defenseSchedule->department,
                'section' => $defenseSchedule->section,
                'group_id' => $defenseSchedule->group_id,
                'defense_type' => 'REDEFENSE',
                'original_defense_type' => $defenseSchedule->defense_type,
                'assignment_id' => $defenseSchedule->assignment_id,
                'panel_data' => $defenseSchedule->panel_data,
                'set_letter' => $defenseSchedule->set_letter,
                'status' => 'Pending'
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function getByType(Request $request)
    {
        \Log::info('getByType called', $request->all());
        
        $query = DefenseSchedule::with('assignment.adviser', 'assignment.assignmentPanels')
            ->where('defense_type', $request->defense_type);

        if ($request->department) {
            $query->where('department', $request->department);
        }

        if ($request->section) {
            $query->where('section', $request->section);
        }

        if ($request->defense_type === 'REDEFENSE' && $request->original_defense_type) {
            $query->where('original_defense_type', $request->original_defense_type);
        }

        $schedules = $query->get();
        
        \Log::info('getByType result', ['count' => $schedules->count(), 'schedules' => $schedules->toArray()]);

        return response()->json($schedules);
    }
    
    public function checkEligibility(Request $request)
    {
        $department = $request->get('department');
        $section = $request->get('section');
        $groupId = $request->get('group_id');
        $defenseType = $request->get('defense_type');
        
        if ($defenseType === 'FINAL DEFENSE') {
            // ONLY groups with "Passed" pre-oral status can schedule final defense
            // Final defense schedule is created only when pre-oral is marked as "Passed"
            $exists = DefenseSchedule::where('department', $department)
                ->where('section', $section)
                ->where('group_id', $groupId)
                ->where('defense_type', 'FINAL DEFENSE')
                ->exists();
                
            if (!$exists) {
                return response()->json([
                    'eligible' => false,
                    'message' => 'Only groups with "Passed" status on Pre-oral Defense can schedule Final Defense.'
                ]);
            }
        }
        
        return response()->json(['eligible' => true]);
    }
    
    public function check(Request $request)
    {
        $schedule = DefenseSchedule::where('department', $request->get('department'))
            ->where('section', $request->get('section'))
            ->where('group_id', $request->get('group_id'))
            ->where('defense_type', $request->get('defense_type'))
            ->first();
        
        return response()->json([
            'exists' => $schedule ? true : false,
            'id' => $schedule ? $schedule->id : null
        ]);
    }
    
    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'department' => 'required|string',
            'section' => 'required|string',
            'group_id' => 'required|string',
            'defense_type' => 'required|string',
            'status' => 'required|string'
        ]);
        
        DefenseSchedule::where('department', $validated['department'])
            ->where('section', $validated['section'])
            ->where('group_id', $validated['group_id'])
            ->where('defense_type', $validated['defense_type'])
            ->update(['status' => $validated['status']]);
            
        return response()->json(['success' => true]);
    }
    
    public function checkPanelAvailability(Request $request)
    {
        $validated = $request->validate([
            'panel_names' => 'required|array',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'department' => 'required|string',
            'section' => 'required|string',
            'group_id' => 'nullable|string',
            'defense_type' => 'nullable|string'
        ]);
        
        \Log::info('Checking availability', $validated);
        
        $conflicts = [];
        
        foreach ($validated['panel_names'] as $panelName) {
            // Check for existing schedules that have TIME conflicts on the same date
            $query = DefenseSchedule::where('defense_date', $validated['date'])
                ->whereNotNull('start_time')
                ->whereNotNull('end_time')
                ->where(function($q) use ($validated) {
                    $q->where(function($sub) use ($validated) {
                        // New schedule starts during existing schedule (exclusive end)
                        $sub->where('start_time', '<=', $validated['start_time'])
                            ->where('end_time', '>', $validated['start_time']);
                    })->orWhere(function($sub) use ($validated) {
                        // New schedule ends during existing schedule (exclusive start)
                        $sub->where('start_time', '<', $validated['end_time'])
                            ->where('end_time', '>=', $validated['end_time']);
                    })->orWhere(function($sub) use ($validated) {
                        // New schedule completely contains existing schedule
                        $sub->where('start_time', '>', $validated['start_time'])
                            ->where('end_time', '<', $validated['end_time']);
                    });
                });
            
            // Exclude current group being edited
            if (isset($validated['group_id']) && isset($validated['defense_type'])) {
                $query->where(function($q) use ($validated) {
                    $q->where('group_id', '!=', $validated['group_id'])
                      ->orWhere('defense_type', '!=', $validated['defense_type']);
                });
            }
            
            $existingSchedules = $query->get();
            
            \Log::info('Found schedules for ' . $panelName, ['count' => $existingSchedules->count()]);
                
            foreach ($existingSchedules as $schedule) {
                if ($schedule->panel_data) {
                    $panelData = is_string($schedule->panel_data) ? json_decode($schedule->panel_data, true) : $schedule->panel_data;
                    
                    if (isset($panelData['adviser']) && $panelData['adviser'] === $panelName) {
                        $conflicts[] = [
                            'panel_name' => $panelName,
                            'conflict_type' => 'Defense Schedule (Adviser)',
                            'conflict_time' => $schedule->start_time . ' - ' . $schedule->end_time,
                            'conflict_details' => $schedule->defense_type . ' for ' . $schedule->group_id
                        ];
                    }
                    
                    if (isset($panelData['chairperson']) && $panelData['chairperson'] === $panelName) {
                        $conflicts[] = [
                            'panel_name' => $panelName,
                            'conflict_type' => 'Defense Schedule (Chairperson)',
                            'conflict_time' => $schedule->start_time . ' - ' . $schedule->end_time,
                            'conflict_details' => $schedule->defense_type . ' for ' . $schedule->group_id
                        ];
                    }
                    
                    if (isset($panelData['members']) && strpos($panelData['members'], $panelName) !== false) {
                        $conflicts[] = [
                            'panel_name' => $panelName,
                            'conflict_type' => 'Defense Schedule (Member)',
                            'conflict_time' => $schedule->start_time . ' - ' . $schedule->end_time,
                            'conflict_details' => $schedule->defense_type . ' for ' . $schedule->group_id
                        ];
                    }
                }
            }
        }
        
        \Log::info('Total conflicts found', ['count' => count($conflicts)]);
        
        return response()->json([
            'available' => empty($conflicts),
            'conflicts' => $conflicts
        ]);
    }
    
    public function getPanelAvailabilitySchedule(Request $request)
    {
        $validated = $request->validate([
            'panel_names' => 'required|array',
            'dates' => 'required|array',
            'department' => 'required|string'
        ]);
        
        $availability = [];
        
        foreach ($validated['panel_names'] as $panelName) {
            $availability[$panelName] = [];
            
            // Get panel availability from Panel model
            $panel = Panel::where('name', $panelName)
                ->where('department', $validated['department'])
                ->whereNull('deleted_at')
                ->first();
            
            $panelAvailability = [];
            if ($panel && $panel->availability) {
                $panelAvailability = is_string($panel->availability) ? json_decode($panel->availability, true) : $panel->availability;
            }
            
            foreach ($validated['dates'] as $date) {
                $conflicts = [];
                $isAvailable = false;
                
                // Check panel's set availability
                if (!empty($panelAvailability)) {
                    foreach ($panelAvailability as $avail) {
                        if (isset($avail['date']) && $avail['date'] === $date) {
                            $isAvailable = true;
                            break;
                        }
                    }
                }
                
                // Check for existing defense schedules on this date
                $existingSchedules = DefenseSchedule::where('defense_date', $date)
                    ->whereNotNull('start_time')
                    ->whereNotNull('end_time')
                    ->get();
                    
                foreach ($existingSchedules as $schedule) {
                    if ($schedule->panel_data) {
                        $panelData = is_string($schedule->panel_data) ? json_decode($schedule->panel_data, true) : $schedule->panel_data;
                        
                        $isInvolved = false;
                        $role = '';
                        
                        if (isset($panelData['adviser']) && $panelData['adviser'] === $panelName) {
                            $isInvolved = true;
                            $role = 'Adviser';
                        } elseif (isset($panelData['chairperson']) && $panelData['chairperson'] === $panelName) {
                            $isInvolved = true;
                            $role = 'Chairperson';
                        } elseif (isset($panelData['members']) && strpos($panelData['members'], $panelName) !== false) {
                            $isInvolved = true;
                            $role = 'Member';
                        }
                        
                        if ($isInvolved) {
                            $conflicts[] = 'Scheduled: ' . $schedule->defense_type . ' (' . $role . ') ' . $schedule->start_time . '-' . $schedule->end_time . ' Group ' . $schedule->group_id;
                        }
                    }
                }
                
                $availability[$panelName][$date] = [
                    'available' => $isAvailable,
                    'conflicts' => $conflicts
                ];
            }
        }
        
        return response()->json([
            'availability' => $availability
        ]);
    }

    public function resetSchedules(Request $request)
    {
        $validated = $request->validate([
            'department' => 'required|string',
            'section' => 'required|string',
            'defense_type' => 'required|string'
        ]);

        $deleted = DefenseSchedule::where('department', $validated['department'])
            ->where('section', $validated['section'])
            ->where('defense_type', $validated['defense_type'])
            ->delete();

        \Log::info('Reset schedules', ['deleted' => $deleted, 'filters' => $validated]);

        return response()->json(['success' => true, 'deleted' => $deleted]);
    }
    
    public function resetGroup(Request $request)
    {
        $department = $request->query('department') ?? $request->input('department');
        $section = $request->query('section') ?? $request->input('section');
        $groupId = $request->query('group_id') ?? $request->input('group_id');
        $defenseType = $request->query('defense_type') ?? $request->input('defense_type');

        \Log::info('Reset Group Request', [
            'department' => $department,
            'section' => $section,
            'group_id' => $groupId,
            'defense_type' => $defenseType
        ]);

        $deleted = DefenseSchedule::where('department', $department)
            ->where('section', $section)
            ->where('group_id', $groupId)
            ->where('defense_type', $defenseType)
            ->delete();

        \Log::info('Deleted rows: ' . $deleted);

        return response()->json(['success' => true, 'deleted' => $deleted]);
    }

    public function getCalendarData()
    {
        \Log::info('getCalendarData called');
        
        $schedules = \DB::table('defense_schedules')
            ->whereNotNull('defense_date')
            ->whereNotNull('start_time')
            ->whereNotNull('end_time')
            ->whereNull('deleted_at')
            ->orderBy('defense_date')
            ->orderBy('start_time')
            ->get()
            ->map(function($schedule) {
                return [
                    'id' => $schedule->id,
                    'department' => $schedule->department,
                    'section' => $schedule->section,
                    'group_id' => $schedule->group_id,
                    'defense_type' => $schedule->defense_type,
                    'defense_date' => $schedule->defense_date,
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'status' => $schedule->status
                ];
            });

        \Log::info('Calendar schedules', ['count' => count($schedules), 'first_date' => $schedules->first()->defense_date ?? null]);

        return response()->json($schedules->values());
    }
}