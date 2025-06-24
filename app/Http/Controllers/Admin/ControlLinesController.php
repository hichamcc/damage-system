<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ControlLine;
use App\Models\ControlTask;
use App\Models\Truck;
use App\Models\User;
use App\Models\DamageReport;
use Illuminate\Http\Request;

class ControlLinesController extends Controller
{
    /**
     * Display control lines dashboard
     */
    public function index(Request $request)
    {
        $query = ControlLine::with(['truck', 'assignedUser', 'createdBy', 'tasks']);

        // Filters
        if ($request->filled('truck_id')) {
            $query->where('truck_id', $request->truck_id);
        }

        if ($request->filled('user_id')) {
            $query->where('assigned_user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $controlLines = $query->latest()->paginate(15);
        
        // For filters
        $trucks = Truck::all();
        $users = User::all();

        // Stats
        $stats = [
            'total_controls' => ControlLine::count(),
            'active_controls' => ControlLine::where('status', 'active')->count(),
            'completed_today' => ControlLine::where('status', 'completed')
                ->whereDate('updated_at', today())->count(),
            'pending_damages' => DamageReport::whereIn('status', ['reported', 'in_repair'])->count(),
        ];

        return view('admin.control.index', compact('controlLines', 'trucks', 'users', 'stats'));
    }

    /**
     * Show form to create new control line
     */
    public function create()
    {
        $trucks = Truck::where('status', 'active')->get();
        $users = User::where('role', 'user')->get();
        
        return view('admin.control.create', compact('trucks', 'users'));
    }

 /**
 * Store new control line
 */
public function store(Request $request)
{
   
    //remove empty tasks from request

    //correct the request
    $request->merge([
        'tasks' => collect($request->tasks)->filter(function ($task) {
            return !empty($task['title']) && !empty($task['task_type']);
        })->values()->toArray()
    ]);
    
  

    $validated = $request->validate([
        'truck_id' => 'required|exists:trucks,id',
        'assigned_user_id' => 'required|exists:users,id',
        'assigned_at' => 'required|date',
        'notes' => 'nullable|string',
        'tasks' => 'required|array|min:1',
        'tasks.*.title' => 'required|string|max:255',
        'tasks.*.description' => 'nullable|string',
        'tasks.*.task_type' => 'required|in:check,inspect,document,report',
        'tasks.*.is_required' => 'boolean',
        'tasks.*.truck_template_id' => 'nullable|exists:truck_templates,id',           
        'tasks.*.template_reference_number' => 'nullable|integer|min:1',              
    ]);

    
    // Create control line
    $controlLine = ControlLine::create([
        'truck_id' => $validated['truck_id'],
        'assigned_user_id' => $validated['assigned_user_id'],
        'created_by' => auth()->id(),
        'assigned_at' => $validated['assigned_at'],
        'notes' => $validated['notes'],
        'status' => 'active',
    ]);

    // Create tasks
    foreach ($validated['tasks'] as $index => $taskData) {
       
        ControlTask::create([
            'control_line_id' => $controlLine->id,
            'title' => $taskData['title'],
            'description' => $taskData['description'] ?? null,
            'task_type' => $taskData['task_type'],
            'sort_order' => $index + 1,
            'is_required' => $taskData['is_required'] ?? true,
            'truck_template_id' => $taskData['truck_template_id'] ?? null,           
            'template_reference_number' => $taskData['template_reference_number'] ?? null, 
            'status' => 'pending',
        ]);

    }

    return redirect()->route('admin.control.show', $controlLine)
        ->with('success', 'Control line created successfully.');
}

    /**
     * Show specific control line
     */
    public function show(ControlLine $controlLine)
    {
        $controlLine->load([
            'truck', 
            'assignedUser', 
            'createdBy', 
            'tasks.completions.completedBy',
            'damageReports.reportedBy'
        ]);

        return view('admin.control.show', compact('controlLine'));
    }

    /**
     * Show edit form
     */
    public function edit(ControlLine $controlLine)
    {
        $controlLine->load('tasks');
        $trucks = Truck::all();
        $users = User::where('role', 'user')->get();
        
        return view('admin.control.edit', compact('controlLine', 'trucks', 'users'));
    }

    public function update(Request $request, ControlLine $controlLine)
    {

        //remove empty tasks from request
        $request->merge([
            'tasks' => collect($request->tasks)->filter(function ($task) {
                return !empty($task['title']) && !empty($task['task_type']);
            })->values()->toArray()
        ]);

        $validated = $request->validate([
            'truck_id' => 'required|exists:trucks,id',
            'assigned_user_id' => 'required|exists:users,id',
            'assigned_at' => 'required|date',
            'status' => 'nullable|in:active,completed,cancelled',
            'completed_at' => 'nullable|date',
            'notes' => 'nullable|string',
            'tasks' => 'required|array|min:1',
            'tasks.*.id' => 'nullable|exists:control_tasks,id',
            'tasks.*.title' => 'required|string|max:255',
            'tasks.*.description' => 'nullable|string',
            'tasks.*.task_type' => 'required|in:check,inspect,document,report',
            'tasks.*.is_required' => 'boolean',
            'tasks.*.status' => 'nullable|string',
            'tasks.*.notes' => 'nullable|string',
            'tasks.*.truck_template_id' => 'nullable|exists:truck_templates,id',           // Add this
            'tasks.*.template_reference_number' => 'nullable|integer|min:1',              // Add this
        ]);
    
        // Update control line
        $controlLine->update([
            'truck_id' => $validated['truck_id'],
            'assigned_user_id' => $validated['assigned_user_id'],
            'assigned_at' => $validated['assigned_at'],
            'status' => $validated['status'] ?? $controlLine->status,
            'completed_at' => $validated['completed_at'] ?? $controlLine->completed_at,
            'notes' => $validated['notes'],
        ]);
    
        // Handle tasks - update existing, create new, delete removed
        $existingTaskIds = collect($validated['tasks'])
            ->pluck('id')
            ->filter()
            ->values()
            ->toArray();
    
        // Delete tasks not in the request
        $controlLine->tasks()
            ->whereNotIn('id', $existingTaskIds)
            ->delete();
    
        // Update or create tasks
        foreach ($validated['tasks'] as $index => $taskData) {
            $taskData['sort_order'] = $index + 1;
            $taskData['is_required'] = $taskData['is_required'] ?? false;
            $taskData['truck_template_id'] = $taskData['truck_template_id'] ?? null;              // Add this
            $taskData['template_reference_number'] = $taskData['template_reference_number'] ?? null; // Add this
            
            if (isset($taskData['id'])) {
                // Update existing task
                $task = $controlLine->tasks()->find($taskData['id']);
                if ($task) {
                    $task->update($taskData);
                }
            } else {
                // Create new task
                $controlLine->tasks()->create(array_merge($taskData, [
                    'control_line_id' => $controlLine->id
                ]));
            }
        }
    
        return redirect()->route('admin.control.show', $controlLine)
            ->with('success', 'Control line updated successfully.');
    }
    public function addTask(Request $request, ControlLine $controlLine)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'task_type' => 'required|in:check,inspect,document,report',
            'is_required' => 'boolean',
            'truck_template_id' => 'nullable|exists:truck_templates,id',           
            'template_reference_number' => 'nullable|integer|min:1',  
        ]);
    
        $controlLine->tasks()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'task_type' => $validated['task_type'],
            'is_required' => $validated['is_required'] ?? false,
            'truck_template_id' => $validated['truck_template_id'] ?? null,       
            'template_reference_number' => $validated['template_reference_number'] ?? null, 
            'sort_order' => $controlLine->tasks()->count() + 1,
            'status' => 'pending',
        ]);
    
        return redirect()->back()->with('success', 'Task added successfully.');
    }

    /**
     * Remove task from control line
     */
    public function removeTask(ControlLine $controlLine, ControlTask $task)
    {
        if ($task->control_line_id !== $controlLine->id) {
            abort(404);
        }

        $task->delete();

        return redirect()->back()->with('success', 'Task removed successfully.');
    }

    /**
     * Compare START vs EXIT checks
     */
    public function compareChecks(ControlLine $controlLine)
    {
        $controlLine->load([
            'truck',
            'assignedUser',
            'tasks.completions' => function($query) {
                $query->with('completedBy');
            }
        ]);

        $comparison = [];
        
        foreach ($controlLine->tasks as $task) {
            $startCompletion = $task->completions->where('check_type', 'start')->first();
            $exitCompletion = $task->completions->where('check_type', 'exit')->first();
            
            $comparison[] = [
                'task' => $task,
                'start' => $startCompletion,
                'exit' => $exitCompletion,
                'has_changes' => $this->hasChanges($startCompletion, $exitCompletion),
            ];
        }

        return view('admin.control.compare', compact('controlLine', 'comparison'));
    }

    /**
     * Damage reports for control line
     */
    public function damageReports(ControlLine $controlLine)
    {
        $damages = $controlLine->damageReports()
            ->with(['reportedBy', 'controlTask'])
            ->latest()
            ->get();

        return view('admin.control.damages', compact('controlLine', 'damages'));
    }

    /**
     * Mark damage as fixed
     */
    public function markDamageFixed(DamageReport $damage, Request $request)
    {
        $validated = $request->validate([
            'fixed_date' => 'required|date',
            'repair_notes' => 'nullable|string',
        ]);

        $damage->update([
            'status' => 'fixed',
            'fixed_date' => $validated['fixed_date'],
            'repair_notes' => $validated['repair_notes'],
        ]);

        return redirect()->back()->with('success', 'Damage marked as fixed.');
    }


    /**
 * Update damage status (for marking as in repair, etc.)
 */
public function updateDamageStatus(DamageReport $damage, Request $request)
{
    $validated = $request->validate([
        'status' => 'required|in:reported,in_repair,fixed',
    ]);

    $damage->update([
        'status' => $validated['status'],
    ]);

    $statusText = str_replace('_', ' ', $validated['status']);
    return redirect()->back()->with('success', "Damage marked as {$statusText}.");
}

/**
 * Show all damage reports across all controls (optional)
 */
public function allDamages()
{
    $damages = DamageReport::with(['controlLine.truck', 'reportedBy', 'controlTask'])
        ->latest()
        ->paginate(20);

    $stats = [
        'total' => DamageReport::count(),
        'reported' => DamageReport::where('status', 'reported')->count(),
        'in_repair' => DamageReport::where('status', 'in_repair')->count(),
        'fixed' => DamageReport::where('status', 'fixed')->count(),
    ];

    return view('admin.damages.index', compact('damages', 'stats'));
}

    /**
     * Show individual damage report (optional)
     */
    public function showDamage(DamageReport $damage)
    {
        $damage->load(['controlLine.truck', 'reportedBy', 'controlTask']);
        
        return view('admin.damages.show', compact('damage'));
    }

    /**
     * Delete damage report (optional)
     */
    public function deleteDamage(DamageReport $damage)
    {
        // Delete associated photos from storage
        if ($damage->damage_photos) {
            foreach ($damage->damage_photos as $photo) {
                Storage::disk('public')->delete($photo['path']);
            }
        }

        $damage->delete();

        return redirect()->back()->with('success', 'Damage report deleted successfully.');
    }


    /**
     * Helper method to detect changes between checks
     */
    private function hasChanges($startCompletion, $exitCompletion)
    {
        if (!$startCompletion || !$exitCompletion) {
            return false;
        }

        return $startCompletion->status !== $exitCompletion->status ||
               $startCompletion->notes !== $exitCompletion->notes;
    }
}