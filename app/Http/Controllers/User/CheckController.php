<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ControlLine;
use App\Models\ControlTask;
use App\Models\TaskCompletion;
use App\Models\DamageReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CheckController extends Controller
{
    /**
     * Submit start check
     */
    public function submitStartCheck(Request $request, ControlLine $controlLine)
    {
        // Verify ownership
        if ($controlLine->assigned_user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'tasks' => 'required|array',
            'tasks.*.status' => 'required|in:ok,issue,missing,damaged',
            'tasks.*.notes' => 'nullable|string|max:1000',
            'tasks.*.damage_area' => 'nullable|string|max:255',
            'tasks.*.photos' => 'nullable|array',
            'tasks.*.photos.*' => 'image|mimes:jpeg,png,jpg|max:10240', // 10MB max
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated['tasks'] as $taskId => $taskData) {
                $task = $controlLine->tasks()->findOrFail($taskId);
                
                // Check if start completion already exists
                if ($task->completions()->where('check_type', 'start')->exists()) {
                    continue; // Skip if already completed
                }

                // Handle photo uploads
                $attachments = [];
                if (isset($taskData['photos']) && is_array($taskData['photos'])) {
                    foreach ($taskData['photos'] as $photo) {
                        $path = $photo->store('task-photos/' . $controlLine->id . '/' . $taskId . '/start', 'public');
                        $attachments[] = [
                            'name' => $photo->getClientOriginalName(),
                            'path' => $path,
                            'type' => 'image',
                            'size' => $photo->getSize(),
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
                    'damage_area' => $taskData['damage_area'] ?? null,
                    'notes' => $taskData['notes'] ?? null,
                    'attachments' => $attachments ?: null,
                    'completed_at' => now(),
                ]);

                // Mark task as completed if it was pending
                if ($task->status === 'pending') {
                    $task->update(['status' => 'completed']);
                }

                // Create damage report if there are issues
                if (in_array($taskData['status'], ['issue', 'missing', 'damaged'])) {
                    $this->createDamageReport($controlLine, $task, $taskData, 'start');
                }
            }

            // Update control line status if needed
            $this->updateControlLineStatus($controlLine);

            DB::commit();

            return redirect()->route('user.control.show', $controlLine)
                ->with('success', 'Start check completed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'An error occurred while saving the check. Please try again.')
                ->withInput();
        }
    }

    /**
     * Submit exit check
     */
    public function submitExitCheck(Request $request, ControlLine $controlLine)
    {
        // Verify ownership
        if ($controlLine->assigned_user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'tasks' => 'required|array',
            'tasks.*.status' => 'required|in:ok,issue,missing,damaged',
            'tasks.*.notes' => 'nullable|string|max:1000',
            'tasks.*.damage_area' => 'nullable|string|max:255',
            'tasks.*.photos' => 'nullable|array',
            'tasks.*.photos.*' => 'image|mimes:jpeg,png,jpg|max:10240',
            'fuel_level' => 'nullable|string',
            'final_mileage' => 'nullable|integer|min:0',
            'overall_notes' => 'nullable|string|max:2000',
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated['tasks'] as $taskId => $taskData) {
                $task = $controlLine->tasks()->findOrFail($taskId);
                
                // Check if exit completion already exists
                if ($task->completions()->where('check_type', 'exit')->exists()) {
                    continue; // Skip if already completed
                }

                // Handle photo uploads
                $attachments = [];
                if (isset($taskData['photos']) && is_array($taskData['photos'])) {
                    foreach ($taskData['photos'] as $photo) {
                        $path = $photo->store('task-photos/' . $controlLine->id . '/' . $taskId . '/exit', 'public');
                        $attachments[] = [
                            'name' => $photo->getClientOriginalName(),
                            'path' => $path,
                            'type' => 'image',
                            'size' => $photo->getSize(),
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
                    'damage_area' => $taskData['damage_area'] ?? null,
                    'notes' => $taskData['notes'] ?? null,
                    'attachments' => $attachments ?: null,
                    'completed_at' => now(),
                ]);

                // Create damage report if there are issues
                if (in_array($taskData['status'], ['issue', 'missing', 'damaged'])) {
                    $this->createDamageReport($controlLine, $task, $taskData, 'exit');
                }
            }

            // Update control line with final information
            $updateData = [
                'completed_at' => now(),
                'status' => 'completed',
            ];

            if (!empty($validated['overall_notes'])) {
                $currentNotes = $controlLine->notes ?? '';
                $updateData['notes'] = $currentNotes . "\n\nExit Notes: " . $validated['overall_notes'];
            }

            $controlLine->update($updateData);

            DB::commit();

            return redirect()->route('user.control.show', $controlLine)
                ->with('success', 'Exit check completed successfully! Control has been marked as completed.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'An error occurred while saving the check. Please try again.')
                ->withInput();
        }
    }

    /**
     * Complete individual task (AJAX endpoint)
     */
    public function completeTask(Request $request, ControlTask $task)
    {
        // Verify ownership
        if ($task->controlLine->assigned_user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'check_type' => 'required|in:start,exit',
            'status' => 'required|in:ok,issue,missing,damaged',
            'notes' => 'nullable|string|max:1000',
            'damage_area' => 'nullable|string|max:255',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg|max:10240',
        ]);

        try {
            // Check if completion already exists
            if ($task->completions()->where('check_type', $validated['check_type'])->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Task already completed for this check type.'
                ], 400);
            }

            // Handle photo uploads
            $attachments = [];
            if (isset($validated['photos'])) {
                foreach ($validated['photos'] as $photo) {
                    $path = $photo->store('task-photos/' . $task->control_line_id . '/' . $task->id . '/' . $validated['check_type'], 'public');
                    $attachments[] = [
                        'name' => $photo->getClientOriginalName(),
                        'path' => $path,
                        'type' => 'image',
                        'size' => $photo->getSize(),
                    ];
                }
            }

            // Create completion
            $completion = TaskCompletion::create([
                'control_task_id' => $task->id,
                'control_line_id' => $task->control_line_id,
                'completed_by' => auth()->id(),
                'check_type' => $validated['check_type'],
                'status' => $validated['status'],
                'damage_area' => $validated['damage_area'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'attachments' => $attachments ?: null,
                'completed_at' => now(),
            ]);

            // Mark task as completed
            if ($task->status === 'pending') {
                $task->update(['status' => 'completed']);
            }

            // Create damage report if needed
            if (in_array($validated['status'], ['issue', 'missing', 'damaged'])) {
                $this->createDamageReport($task->controlLine, $task, $validated, $validated['check_type']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Task completed successfully!',
                'completion' => $completion->load('completedBy')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while completing the task.'
            ], 500);
        }
    }

    /**
     * Report damage manually
     */
    public function reportDamage(Request $request, ControlLine $controlLine)
    {
        // Verify ownership
        if ($controlLine->assigned_user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'control_task_id' => 'nullable|exists:control_tasks,id',
            'damage_location' => 'required|string|max:255',
            'damage_description' => 'required|string|max:1000',
            'severity' => 'required|in:minor,major,critical',
            'damage_area' => 'nullable|string|max:255',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg|max:10240',
        ]);

        try {
            // Handle photo uploads
            $damagePhotos = [];
            if (isset($validated['photos'])) {
                foreach ($validated['photos'] as $photo) {
                    $path = $photo->store('damage-photos/' . $controlLine->id, 'public');
                    $damagePhotos[] = [
                        'name' => $photo->getClientOriginalName(),
                        'path' => $path,
                    ];
                }
            }

            // Create damage report
            DamageReport::create([
                'control_line_id' => $controlLine->id,
                'control_task_id' => $validated['control_task_id'] ?? null,
                'truck_id' => $controlLine->truck_id,
                'reported_by' => auth()->id(),
                'damage_location' => $validated['damage_location'],
                'damage_description' => $validated['damage_description'],
                'severity' => $validated['severity'],
                'damage_area' => $validated['damage_area'],
                'damage_photos' => $damagePhotos ?: null,
                'status' => 'reported',
            ]);

            return redirect()->back()
                ->with('success', 'Damage report submitted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while submitting the damage report.')
                ->withInput();
        }
    }

    /**
     * Create damage report from task completion
     */
    private function createDamageReport(ControlLine $controlLine, ControlTask $task, array $taskData, string $checkType)
    {
        $severity = match($taskData['status']) {
            'damaged' => 'major',
            'missing' => 'critical',
            'issue' => 'minor',
            default => 'minor'
        };
        
        //add damage photos from task completions
        $damagePhotos = TaskCompletion::where('control_line_id', $controlLine->id)
            ->where('control_task_id', $task->id)
            ->get()
            ->pluck('attachments');
           
            

        DamageReport::create([
            'control_line_id' => $controlLine->id,
            'control_task_id' => $task->id,
            'truck_id' => $controlLine->truck_id,
            'reported_by' => auth()->id(),
            'damage_location' => $task->title,
            'damage_description' => $taskData['notes'] ?? "Issue found during {$checkType} check: {$task->title}",
            'severity' => $severity,
            'damage_area' => $taskData['damage_area'] ?? null,
            'status' => 'reported',
            'damage_photos' => $damagePhotos,
        ]);

    }

    /**
     * Update control line status based on task completions
     */
    private function updateControlLineStatus(ControlLine $controlLine)
    {
        $totalTasks = $controlLine->tasks()->count();
        $completedTasks = $controlLine->tasks()
            ->whereHas('completions')
            ->count();

        // If all tasks have at least one completion, check for completion
        if ($completedTasks === $totalTasks) {
            // Check if this was an exit check (has exit completions)
            $hasExitCompletions = $controlLine->tasks()
                ->whereHas('completions', function($query) {
                    $query->where('check_type', 'exit');
                })->exists();

            if ($hasExitCompletions) {
                $controlLine->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);
            }
        }
    }
}