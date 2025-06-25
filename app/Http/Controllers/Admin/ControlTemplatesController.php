<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ControlTemplate;
use App\Models\TruckTemplate;
use Illuminate\Http\Request;

class ControlTemplatesController extends Controller
{
    /**
     * Display control templates list
     */
    public function index()
    {
        $templates = ControlTemplate::with(['createdBy', 'tasks'])
            ->withCount('controlLines')
            ->latest()
            ->paginate(15);

        $stats = [
            'total_templates' => ControlTemplate::count(),
            'active_templates' => ControlTemplate::where('is_active', true)->count(),
            'total_usage' => ControlTemplate::withCount('controlLines')->get()->sum('control_lines_count'),
        ];

        return view('admin.control-templates.index', compact('templates', 'stats'));
    }

    /**
     * Show form to create new control template
     */
    public function create()
    {
        $truckTemplates = TruckTemplate::all();
        
        return view('admin.control-templates.create', compact('truckTemplates'));
    }

    /**
     * Store new control template
     */
    public function store(Request $request)
    {
        // Remove empty tasks from request
        $request->merge([
            'tasks' => collect($request->tasks)->filter(function ($task) {
                return !empty($task['title']) && !empty($task['task_type']);
            })->values()->toArray()
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'tasks' => 'required|array|min:1',
            'tasks.*.title' => 'required|string|max:255',
            'tasks.*.description' => 'nullable|string',
            'tasks.*.task_type' => 'required|in:check,inspect,document,report',
            'tasks.*.is_required' => 'boolean',
            'tasks.*.truck_template_id' => 'nullable|exists:truck_templates,id',
            'tasks.*.template_reference_number' => 'nullable|integer|min:1',
        ]);

        // If setting this as active, deactivate all others (only one active template)
        if ($validated['is_active'] ?? false) {
            ControlTemplate::where('is_active', true)->update(['is_active' => false]);
        }

        // Create control template
        $template = ControlTemplate::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'is_active' => $validated['is_active'] ?? false,
            'created_by' => auth()->id(),
        ]);

        // Create tasks
        foreach ($validated['tasks'] as $index => $taskData) {
            $template->tasks()->create([
                'title' => $taskData['title'],
                'description' => $taskData['description'] ?? null,
                'task_type' => $taskData['task_type'],
                'sort_order' => $index + 1,
                'is_required' => $taskData['is_required'] ?? true,
                'truck_template_id' => $taskData['truck_template_id'] ?? null,
                'template_reference_number' => $taskData['template_reference_number'] ?? null,
            ]);
        }

        return redirect()->route('admin.control-templates.show', $template)
            ->with('success', 'Control template created successfully.');
    }

    /**
     * Show specific control template
     */
    public function show(ControlTemplate $controlTemplate)
    {
        $controlTemplate->load([
            'createdBy',
            'tasks.truckTemplate',
            'controlLines.truck',
            'controlLines.assignedUser'
        ]);

        return view('admin.control-templates.show', compact('controlTemplate'));
    }

    /**
     * Show edit form
     */
    public function edit(ControlTemplate $controlTemplate)
    {
        $controlTemplate->load('tasks');
        $truckTemplates = TruckTemplate::all();
        
        return view('admin.control-templates.edit', compact('controlTemplate', 'truckTemplates'));
    }

    /**
     * Update control template
     */
    public function update(Request $request, ControlTemplate $controlTemplate)
    {
        // Remove empty tasks from request
        $request->merge([
            'tasks' => collect($request->tasks)->filter(function ($task) {
                return !empty($task['title']) && !empty($task['task_type']);
            })->values()->toArray()
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'tasks' => 'required|array|min:1',
            'tasks.*.id' => 'nullable|exists:control_template_tasks,id',
            'tasks.*.title' => 'required|string|max:255',
            'tasks.*.description' => 'nullable|string',
            'tasks.*.task_type' => 'required|in:check,inspect,document,report',
            'tasks.*.is_required' => 'boolean',
            'tasks.*.truck_template_id' => 'nullable|exists:truck_templates,id',
            'tasks.*.template_reference_number' => 'nullable|integer|min:1',
        ]);

        // If setting this as active, deactivate all others (only one active template)
        if ($validated['is_active'] ?? false) {
            ControlTemplate::where('is_active', true)
                ->where('id', '!=', $controlTemplate->id)
                ->update(['is_active' => false]);
        }

        // Update control template
        $controlTemplate->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'is_active' => $validated['is_active'] ?? false,
        ]);

        // Handle tasks - update existing, create new, delete removed
        $existingTaskIds = collect($validated['tasks'])
            ->pluck('id')
            ->filter()
            ->values()
            ->toArray();

        // Delete tasks not in the request
        $controlTemplate->tasks()
            ->whereNotIn('id', $existingTaskIds)
            ->delete();

        // Update or create tasks
        foreach ($validated['tasks'] as $index => $taskData) {
            $taskData['sort_order'] = $index + 1;
            $taskData['is_required'] = $taskData['is_required'] ?? false;
            $taskData['truck_template_id'] = $taskData['truck_template_id'] ?? null;
            $taskData['template_reference_number'] = $taskData['template_reference_number'] ?? null;
            
            if (isset($taskData['id'])) {
                // Update existing task
                $task = $controlTemplate->tasks()->find($taskData['id']);
                if ($task) {
                    $task->update($taskData);
                }
            } else {
                // Create new task
                $controlTemplate->tasks()->create(array_merge($taskData, [
                    'control_template_id' => $controlTemplate->id
                ]));
            }
        }

        return redirect()->route('admin.control-templates.show', $controlTemplate)
            ->with('success', 'Control template updated successfully.');
    }

    /**
     * Toggle template active status
     */
    public function toggleActive(ControlTemplate $controlTemplate)
    {
        if (!$controlTemplate->is_active) {
            // Deactivate all other templates (only one active)
            ControlTemplate::where('is_active', true)->update(['is_active' => false]);
            $controlTemplate->update(['is_active' => true]);
            $message = 'Template activated successfully.';
        } else {
            $controlTemplate->update(['is_active' => false]);
            $message = 'Template deactivated successfully.';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Delete control template
     */
    public function destroy(ControlTemplate $controlTemplate)
    {
        // Check if template is being used
        if ($controlTemplate->controlLines()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete template that is being used by control lines.');
        }

        $controlTemplate->delete();

        return redirect()->route('admin.control-templates.index')
            ->with('success', 'Control template deleted successfully.');
    }
}