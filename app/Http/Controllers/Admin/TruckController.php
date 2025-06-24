<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TruckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Truck::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('truck_number', 'like', "%{$search}%")
                  ->orWhere('license_plate', 'like', "%{$search}%")
                  ->orWhere('make', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Make filter
        if ($request->filled('make')) {
            $query->where('make', $request->get('make'));
        }

        $trucks = $query->latest()->paginate(15)->withQueryString();
        
        // Get unique makes for filter dropdown
        $makes = Truck::distinct()->pluck('make')->sort()->values();

        return view('admin.trucks.index', compact('trucks', 'makes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.trucks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'truck_number' => ['required', 'string', 'max:255', 'unique:trucks'],
            'license_plate' => ['required', 'string', 'max:255', 'unique:trucks'],
            'make' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:active,maintenance,out_of_service,retired'],
            'notes' => ['nullable', 'string'],
            'attachments.*' => ['nullable', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
        ]);

        // Handle file uploads
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('trucks/attachments', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ];
            }
        }

        $validated['attachments'] = $attachments;

        Truck::create($validated);

        return redirect()->route('admin.trucks.index')
            ->with('success', 'Truck created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Truck $truck)
    {
        return view('admin.trucks.show', compact('truck'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Truck $truck)
    {
        return view('admin.trucks.edit', compact('truck'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Truck $truck)
    {
        $validated = $request->validate([
            'truck_number' => ['required', 'string', 'max:255', Rule::unique('trucks')->ignore($truck->id)],
            'license_plate' => ['required', 'string', 'max:255', Rule::unique('trucks')->ignore($truck->id)],
            'make' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:active,maintenance,out_of_service,retired'],
            'notes' => ['nullable', 'string'],
            'attachments.*' => ['nullable', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
        ]);

        // IMPORTANT: Keep existing attachments and ADD new ones (don't replace)
        $existingAttachments = $truck->attachments ?? [];
        
        // Only add new attachments if files were uploaded
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('trucks/attachments', 'public');
                $existingAttachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ];
            }
        }

        // Update validated data with the merged attachments
        $validated['attachments'] = $existingAttachments;

        // Update the truck
        $truck->update($validated);

        return redirect()->route('admin.trucks.index')
            ->with('success', 'Truck updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Truck $truck)
    {
        // Delete associated files
        if ($truck->attachments) {
            foreach ($truck->attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }

        $truck->delete();

        return redirect()->route('admin.trucks.index')
            ->with('success', 'Truck deleted successfully.');
    }

    /**
     * Remove attachment from truck
     */
    public function removeAttachment(Truck $truck, $index)
    {
        $attachments = $truck->attachments ?? [];
        
        if (isset($attachments[$index])) {
            // Delete file from storage
            Storage::disk('public')->delete($attachments[$index]['path']);
            
            // Remove from array
            unset($attachments[$index]);
            $attachments = array_values($attachments); // Re-index array
            
            $truck->update(['attachments' => $attachments]);
        }

        return redirect()->back()->with('success', 'Attachment removed successfully.');
    }

    /**
     * Download attachment
     */
    public function downloadAttachment(Truck $truck, $index)
    {
        $attachments = $truck->attachments ?? [];
        
        if (isset($attachments[$index])) {
            $attachment = $attachments[$index];
            $path = storage_path('app/public/' . $attachment['path']);
            
            if (file_exists($path)) {
                return response()->download($path, $attachment['name']);
            }
        }

        return redirect()->back()->with('error', 'File not found.');
    }
}