<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ControlLine;
use App\Models\ControlTemplate;
use App\Models\Truck;
use Illuminate\Http\Request;
use App\Models\DamageReport;

class ControlController extends Controller
{
    /**
     * User dashboard - show their controls and create new ones
     */
    public function index()
    {
        $activeTemplates = ControlTemplate::where('is_active', true)->get();
        
        if (!$activeTemplates) {
            return view('user.control.no-template');
        }

        // Get user's recent controls
        $userControls = ControlLine::with(['truck', 'controlTemplate'])
            ->where('assigned_user_id', auth()->id())
            ->latest()
            ->paginate(10);

        $trucks =Truck::where('status', 'active')->get(); 

        $stats = [
            'total_controls' => ControlLine::where('assigned_user_id', auth()->id())->count(),
            'active_controls' => ControlLine::where('assigned_user_id', auth()->id())
                ->where('status', 'active')->count(),
            'completed_today' => ControlLine::where('assigned_user_id', auth()->id())
                ->where('status', 'completed')
                ->whereDate('updated_at', today())->count(),
        ];

        return view('user.control.index', compact('activeTemplates', 'userControls', 'trucks', 'stats'));
    }

    /**
     * Create new control line from template
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'truck_id' => 'required|exists:trucks,id',
            'check_type' => 'nullable|in:start,exit',
            'notes' => 'nullable|string',
        ]);

        $type = Truck::find($validated['truck_id'])->type;

        $activeTemplate = ControlTemplate::getActiveTemplate($type);
        
        if (!$activeTemplate) {
            return redirect()->back()->with('error', 'No active control template available.');
        }

        // Check if any user already has an active control for this truck
        $existingControl = ControlLine::where('truck_id', $validated['truck_id'])
            ->where('status', 'active')
            ->first();

        if ($existingControl) {
            return redirect()->route('user.control.index')
                ->with('info', 'Other user already on this case.');
        }

        // Create control line
        $controlLine = ControlLine::create([
            'truck_id' => $validated['truck_id'],
            'control_template_id' => $activeTemplate->id,
            'assigned_user_id' => auth()->id(),
            'created_by' => auth()->id(),
            'assigned_at' => now(),
            'notes' => $validated['notes'],
            'status' => 'active',
        ]);

        // Create tasks from template
        foreach ($activeTemplate->tasks as $templateTask) {
            $controlLine->tasks()->create([
                'title' => $templateTask->title,
                'description' => $templateTask->description,
                'task_type' => $templateTask->task_type,
                'sort_order' => $templateTask->sort_order,
                'is_required' => $templateTask->is_required,
                'truck_template_id' => $templateTask->truck_template_id,
                'template_reference_number' => $templateTask->template_reference_number,
                'status' => 'pending',
            ]);
        }

        // Redirect to check form based on type
        return redirect()->route('user.control.start', $controlLine)
        ->with('success', 'Control created successfully. Complete your start check below.');
        
    }

    /**
     * Show specific control line
     */
    public function show(ControlLine $controlLine)
    {
        // Check if user owns this control
        if ($controlLine->assigned_user_id !== auth()->id()) {
            abort(403);
        }

        $controlLine->load([
            'truck',
            'controlTemplate',
            'tasks.completions.completedBy',
            'damageReports'
        ]);

        return view('user.control.show', compact('controlLine'));
    }

    /**
     * Start check form
     */
    public function start(ControlLine $controlLine)
    {
        // Check if user owns this control
        if ($controlLine->assigned_user_id !== auth()->id()) {
            abort(403);
        }

        $controlLine->load(['truck', 'controlTemplate', 'tasks']);

        // Check if start check already completed
        $hasStartCheck = $controlLine->tasks()
            ->whereHas('completions', function($query) {
                $query->where('check_type', 'start');
            })->exists();

        if ($hasStartCheck) {
            return redirect()->route('user.control.show', $controlLine)
                ->with('info', 'Start check already completed for this control.');
        }

        // show old damage reports related to this truck that are not completed
        $damageReports = DamageReport::where('truck_id', $controlLine->truck_id)
        ->whereIn('status', ['reported', 'in_repair'])
        ->with(['reportedBy', 'controlLine']) // Load relationships for better display
        ->orderBy('created_at', 'desc')
        ->get();

        return view('user.control.start', compact('controlLine', 'damageReports'));
    }

    /**
     * Exit check form
     */
    public function exit(ControlLine $controlLine)
    {
        // Check if user owns this control
        if ($controlLine->assigned_user_id !== auth()->id()) {
            abort(403);
        }

        $controlLine->load(['truck', 'controlTemplate', 'tasks']);

        // Check if exit check already completed
        $hasExitCheck = $controlLine->tasks()
            ->whereHas('completions', function($query) {
                $query->where('check_type', 'exit');
            })->exists();

        if ($hasExitCheck) {
            return redirect()->route('user.control.show', $controlLine)
                ->with('info', 'Exit check already completed for this control.');
        }

        // show old damage reports related to this truck that are not completed
        $damageReports = DamageReport::where('truck_id', $controlLine->truck_id)
        ->whereIn('status', ['reported', 'in_repair'])
        ->with(['reportedBy', 'controlLine']) // Load relationships for better display
        ->orderBy('created_at', 'desc')
        ->get();

        return view('user.control.exit', compact('controlLine', 'damageReports'));
    }

    /**
     * Get active template via API (for AJAX calls)
     */
    public function getActiveTemplate()
    {
        $template = ControlTemplate::getActiveTemplate();
        
        return response()->json($template);
    }
}