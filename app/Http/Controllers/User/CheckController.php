<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ControlLine;
use App\Models\ControlTask;
use App\Models\TaskCompletion;
use App\Models\DamageReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CheckController extends Controller
{
    /**
     * User dashboard showing assigned controls
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        // Active controls assigned to user
        $activeControls = ControlLine::where('assigned_user_id', $user->id)
            ->where('status', 'active')
            ->with(['truck', 'tasks'])
            ->latest('assigned_at')
            ->get();

        // Recent completed controls
        $recentCompleted = ControlLine::where('assigned_user_id', $user->id)
            ->where('status', 'completed')
            ->with(['truck'])
            ->latest('updated_at')
            ->take(5)
            ->get();

        $stats = [
            'active_controls' => $activeControls->count(),
            'pending_start_checks' => $activeControls->where('start_check_at', null)->count(),
            'pending_exit_checks' => $activeControls->where('start_check_at', '!=', null)
                ->where('exit_check_at', null)->count(),
            'completed_today' => ControlLine::where('assigned_user_id', $user->id)
                ->where('status', 'completed')
                ->whereDate('updated_at', today())
                ->count(),
        ];

        return view('user.dashboard', compact('activeControls', 'recentCompleted', 'stats'));
    }

    /**
     * Show all controls assigned to user
     */
    public function myControls()
    {
        $user = auth()->user();
        
        $controls = ControlLine::where('assigned_user_id', $user->id)
            ->with(['truck', 'tasks', 'completions'])
            ->latest('assigned_at')
            ->paginate(10);

        return view('user.controls.index', compact('controls'));
    }

    /**
     * Show START check form
     */
    public function startCheck(ControlLine $controlLine)
    {
        // Verify user is assigned to this control
        if ($controlLine->assigned_user_id !== auth()->id()) {
            abort(403, 'You are not assigned to this control.');
        }

        // Check if START check already completed
        if ($controlLine->start_check_at) {
            return redirect()->route('user.controls')
                ->with('info', 'START check already completed for this control.');
        }

        $controlLine->load(['truck', 'tasks']);

        return view('user.controls.start-check', compact('controlLine'));
    }

/**
 * Submit START check (Updated)
 */
public function submitStartCheck(Request $request, ControlLine $controlLine)
{
    // Verify user is assigned
    if ($controlLine->assigned_user_id !== auth()->id()) {
        abort(403);
    }

    $validated = $request->validate([
        'tasks' => 'required|array',
        'tasks.*.task_id' => 'required|exists:control_tasks,id',
        'tasks.*.status' => 'required|in:ok,issue,missing,damaged',
        'tasks.*.notes' => 'nullable|string',
        'tasks.*.damage_area' => 'nullable|string|max:100', // New field for damage area
        'tasks.*.attachments.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf',
    ]);

    // Process each task completion
    foreach ($validated['tasks'] as $taskData) {
        $task = ControlTask::findOrFail($taskData['task_id']);
        
        // Handle file uploads
        $attachments = [];
        if (isset($taskData['attachments'])) {
            foreach ($taskData['attachments'] as $file) {
                $path = $file->store('control-checks', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ];
            }
        }

        // Create task completion
        TaskCompletion::create([
            'control_task_id' => $task->id,
            'control_line_id' => $controlLine->id,
            'completed_by' => auth()->id(),
            'check_type' => 'start',
            'status' => $taskData['status'],
            'notes' => $taskData['notes'] ?? null,
            'damage_area' => $taskData['damage_area'] ?? null, // Store damage area
            'attachments' => $attachments,
            'completed_at' => now(),
        ]);

        // If status indicates damage, create damage report
        if (in_array($taskData['status'], ['issue', 'damaged', 'missing'])) {
            $damageDescription = $taskData['notes'] ?? 'Issue found during START check';
            
            // Include damage area in description if provided
            if (!empty($taskData['damage_area'])) {
                $damageDescription = "Area(s): {$taskData['damage_area']} - " . $damageDescription;
            }

            DamageReport::create([
                'control_line_id' => $controlLine->id,
                'control_task_id' => $task->id,
                'truck_id' => $controlLine->truck_id,
                'reported_by' => auth()->id(),
                'damage_location' => $task->title,
                'damage_description' => $damageDescription,
                'damage_area' => $taskData['damage_area'] ?? null, // Store area separately
                'severity' => $taskData['status'] === 'damaged' ? 'major' : 'minor',
                'status' => 'reported',
                'damage_photos' => $attachments,
            ]);
        }
    }

    // Mark START check as completed
    $controlLine->update(['start_check_at' => now()]);

    return redirect()->route('user.controls')
        ->with('success', 'START check completed successfully.');
}

    /**
     * Show EXIT check form
     */
    public function exitCheck(ControlLine $controlLine)
    {
        // Verify user is assigned
        if ($controlLine->assigned_user_id !== auth()->id()) {
            abort(403);
        }

        // Check if START check completed
        if (!$controlLine->start_check_at) {
            return redirect()->route('user.controls.start', $controlLine)
                ->with('info', 'Please complete START check first.');
        }

        // Check if EXIT check already completed
        if ($controlLine->exit_check_at) {
            return redirect()->route('user.controls')
                ->with('info', 'EXIT check already completed for this control.');
        }

        $controlLine->load(['truck', 'tasks.startCompletion']);

        return view('user.controls.exit-check', compact('controlLine'));
    }

/**
 * Submit EXIT check (Updated)
 */
public function submitExitCheck(Request $request, ControlLine $controlLine)
{
    // Verify user is assigned
    if ($controlLine->assigned_user_id !== auth()->id()) {
        abort(403);
    }

    $validated = $request->validate([
        'tasks' => 'required|array',
        'tasks.*.task_id' => 'required|exists:control_tasks,id',
        'tasks.*.status' => 'required|in:ok,issue,missing,damaged',
        'tasks.*.notes' => 'nullable|string',
        'tasks.*.damage_area' => 'nullable|string|max:100', // New field for damage area
        'tasks.*.attachments.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf',
    ]);

    // Process each task completion
    foreach ($validated['tasks'] as $taskData) {
        $task = ControlTask::findOrFail($taskData['task_id']);
        
        // Handle file uploads
        $attachments = [];
        if (isset($taskData['attachments'])) {
            foreach ($taskData['attachments'] as $file) {
                $path = $file->store('control-checks', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ];
            }
        }

        // Create task completion
        TaskCompletion::create([
            'control_task_id' => $task->id,
            'control_line_id' => $controlLine->id,
            'completed_by' => auth()->id(),
            'check_type' => 'exit',
            'status' => $taskData['status'],
            'notes' => $taskData['notes'] ?? null,
            'damage_area' => $taskData['damage_area'] ?? null, // Store damage area
            'attachments' => $attachments,
            'completed_at' => now(),
        ]);

        // If status indicates new damage (different from START), create damage report
        $startCompletion = $task->completions()->where('check_type', 'start')->first();
        if (in_array($taskData['status'], ['issue', 'damaged', 'missing']) && 
            (!$startCompletion || $startCompletion->status !== $taskData['status'])) {
            
            $damageDescription = $taskData['notes'] ?? 'New issue found during EXIT check';
            
            // Include damage area in description if provided
            if (!empty($taskData['damage_area'])) {
                $damageDescription = "Area(s): {$taskData['damage_area']} - " . $damageDescription;
            }
            
            DamageReport::create([
                'control_line_id' => $controlLine->id,
                'control_task_id' => $task->id,
                'truck_id' => $controlLine->truck_id,
                'reported_by' => auth()->id(),
                'damage_location' => $task->title,
                'damage_description' => $damageDescription,
                'damage_area' => $taskData['damage_area'] ?? null, // Store area separately
                'severity' => $taskData['status'] === 'damaged' ? 'major' : 'minor',
                'status' => 'reported',
                'damage_photos' => $attachments,
            ]);
        }
    }

    // Mark EXIT check as completed and control as completed
    $controlLine->update([
        'exit_check_at' => now(),
        'status' => 'completed',
    ]);

    return redirect()->route('user.controls')
        ->with('success', 'EXIT check completed successfully. Control line is now complete.');
}

    /**
     * Report damage during check
     */
    public function reportDamage(Request $request, ControlLine $controlLine)
    {
        $validated = $request->validate([
            'task_id' => 'nullable|exists:control_tasks,id',
            'damage_location' => 'required|string|max:255',
            'damage_description' => 'required|string',
            'severity' => 'required|in:minor,major,critical',
            'photos.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png',
        ]);

        // Handle photo uploads
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('damage-reports', 'public');
                $photos[] = [
                    'name' => $photo->getClientOriginalName(),
                    'path' => $path,
                ];
            }
        }

        DamageReport::create([
            'control_line_id' => $controlLine->id,
            'control_task_id' => $validated['task_id'] ?? null,
            'truck_id' => $controlLine->truck_id,
            'reported_by' => auth()->id(),
            'damage_location' => $validated['damage_location'],
            'damage_description' => $validated['damage_description'],
            'severity' => $validated['severity'],
            'status' => 'reported',
            'damage_photos' => $photos,
        ]);

        return redirect()->back()->with('success', 'Damage reported successfully.');
    }

    public function show(ControlLine $controlLine)
{
    // Verify user is assigned to this control
    if ($controlLine->assigned_user_id !== auth()->id()) {
        abort(403, 'You are not assigned to this control.');
    }

    $controlLine->load(['truck', 'tasks.completions', 'completions', 'damageReports']);

    return view('user.controls.show', compact('controlLine'));
}
}