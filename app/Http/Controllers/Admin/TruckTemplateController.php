<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TruckTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TruckTemplateController extends Controller
{
    /**
     * Display templates index
     */
    public function index()
    {
        $templates = TruckTemplate::latest()->get();
        
        return view('admin.truck-templates.index', compact('templates'));
    }

    /**
     * Store a new template
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'view_type' => 'required|in:front,back,left,right,top,interior',
            'truck_type' => 'nullable|string|max:255',
            'number_points' => 'required|integer|min:1|max:50',
            'description' => 'nullable|string|max:1000',
            'template_image' => 'required|image|mimes:jpeg,png,jpg|max:10240', // 10MB max
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('template_image')) {
            $image = $request->file('template_image');
            $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('truck-templates', $imageName, 'public');
        }

        TruckTemplate::create([
            'name' => $validated['name'],
            'view_type' => $validated['view_type'],
            'truck_type' => $validated['truck_type'],
            'number_points' => $validated['number_points'],
            'description' => $validated['description'],
            'image_path' => $imagePath,
            'is_active' => $request->boolean('is_active', true),
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.truck-templates.index')
            ->with('success', 'Truck template uploaded successfully.');
    }

    /**
     * Show template details
     */
    public function show(TruckTemplate $truckTemplate)
    {
        return view('admin.truck-templates.show', compact('truckTemplate'));
    }

    /**
     * Show edit form
     */
    public function edit(TruckTemplate $truckTemplate)
    {
        return view('admin.truck-templates.edit', compact('truckTemplate'));
    }

    /**
     * Update template
     */
    public function update(Request $request, TruckTemplate $truckTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'view_type' => 'required|in:front,back,left,right,top,interior',
            'truck_type' => 'nullable|string|max:255',
            'number_points' => 'required|integer|min:1|max:50',
            'description' => 'nullable|string|max:1000',
            'template_image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'is_active' => 'boolean',
        ]);

        // Handle image upload if new image provided
        if ($request->hasFile('template_image')) {
            // Delete old image
            if ($truckTemplate->image_path) {
                Storage::disk('public')->delete($truckTemplate->image_path);
            }
            
            $image = $request->file('template_image');
            $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $validated['image_path'] = $image->storeAs('truck-templates', $imageName, 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        
        $truckTemplate->update($validated);

        return redirect()->route('admin.truck-templates.index')
            ->with('success', 'Truck template updated successfully.');
    }

    /**
     * Delete template
     */
    public function destroy(TruckTemplate $truckTemplate)
    {
        // Delete associated image
        if ($truckTemplate->image_path) {
            Storage::disk('public')->delete($truckTemplate->image_path);
        }

        $truckTemplate->delete();

        return redirect()->route('admin.truck-templates.index')
            ->with('success', 'Truck template deleted successfully.');
    }

    /**
     * Toggle template active status
     */
    public function toggleStatus(TruckTemplate $truckTemplate)
    {
        $truckTemplate->update([
            'is_active' => !$truckTemplate->is_active
        ]);

        $status = $truckTemplate->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()
            ->with('success', "Template {$status} successfully.");
    }

    /**
     * Get templates for AJAX requests (for control creation)
     */
    public function getTemplates(Request $request)
    {
        $templates = TruckTemplate::where('is_active', true)
            ->when($request->view_type, function ($query, $viewType) {
                return $query->where('view_type', $viewType);
            })
            ->when($request->truck_type, function ($query, $truckType) {
                return $query->where('truck_type', $truckType)
                    ->orWhereNull('truck_type');
            })
            ->select('id', 'name', 'view_type', 'image_path', 'number_points')
            ->get();

        return response()->json($templates);
    }
}