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
     * Display control lines dashboard (view user-created controls)
     */
    public function index(Request $request)
    {
        $query = ControlLine::with(['truck', 'assignedUser', 'createdBy', 'tasks', 'controlTemplate']);

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

        if ($request->filled('date_from')) {
            $query->whereDate('assigned_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('assigned_at', '<=', $request->date_to);
        }

        $controlLines = $query->latest()->paginate(15);
        
        // For filters
        $trucks = Truck::all();
        $users = User::where('role', 'user')->get();

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
     * Show specific control line
     */
    public function show(ControlLine $controlLine)
    {
        $controlLine->load([
            'truck', 
            'assignedUser', 
            'createdBy',
            'controlTemplate',
            'tasks.completions.completedBy',
            'damageReports.reportedBy'
        ]);

        return view('admin.control.show', compact('controlLine'));
    }

    /**
     * Compare START vs EXIT checks
     */
    public function compareChecks(ControlLine $controlLine)
    {
        $controlLine->load([
            'truck',
            'assignedUser',
            'controlTemplate',
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
     * Show all damage reports across all controls
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
     * Show individual damage report
     */
    public function showDamage(DamageReport $damage)
    {
        $damage->load(['controlLine.truck', 'reportedBy', 'controlTask']);
        
        return view('admin.damages.show', compact('damage'));
    }

    /**
     * Delete damage report
     */
    public function deleteDamage(DamageReport $damage)
    {
        // Delete associated photos from storage
        if ($damage->damage_photos) {
            foreach ($damage->damage_photos as $photo) {
                \Storage::disk('public')->delete($photo['path']);
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