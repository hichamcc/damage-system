<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TruckNumber;
use Illuminate\Http\Request;

class TruckNumbersController extends Controller
{
    /**
     * Display a listing of truck numbers
     */
    public function index(Request $request)
    {
        $query = TruckNumber::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $truckNumbers = $query->latest()->paginate(15);

        return view('admin.truck-numbers.index', compact('truckNumbers'));
    }

    /**
     * Show the form for creating a new truck number
     */
    public function create()
    {
        return view('admin.truck-numbers.create');
    }

    /**
     * Store a newly created truck number
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'truck_number' => [
                'required',
                'string',
                'max:50',
                'unique:truck_numbers,truck_number'
            ],
        ], [
            'truck_number.required' => 'Truck number is required.',
            'truck_number.unique' => 'This truck number already exists.',
            'truck_number.max' => 'Truck number cannot be longer than 50 characters.',
        ]);

        TruckNumber::create($validated);

        return redirect()
            ->route('admin.truck-numbers.index')
            ->with('success', 'Truck number added successfully.');
    }

    /**
     * Show the form for editing a truck number
     */
    public function edit(TruckNumber $truckNumber)
    {
        return view('admin.truck-numbers.edit', compact('truckNumber'));
    }

    /**
     * Update the specified truck number
     */
    public function update(Request $request, TruckNumber $truckNumber)
    {
        $validated = $request->validate([
            'truck_number' => [
                'required',
                'string',
                'max:50',
                'unique:truck_numbers,truck_number,' . $truckNumber->id
            ],
        ], [
            'truck_number.required' => 'Truck number is required.',
            'truck_number.unique' => 'This truck number already exists.',
            'truck_number.max' => 'Truck number cannot be longer than 50 characters.',
        ]);

        $truckNumber->update($validated);

        return redirect()
            ->route('admin.truck-numbers.index')
            ->with('success', 'Truck number updated successfully.');
    }

    /**
     * Remove the specified truck number
     */
    public function destroy(TruckNumber $truckNumber)
    {
        $truckNumber->delete();

        return redirect()
            ->route('admin.truck-numbers.index')
            ->with('success', 'Truck number deleted successfully.');
    }

    /**
     * Bulk delete truck numbers
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'truck_number_ids' => 'required|array',
            'truck_number_ids.*' => 'exists:truck_numbers,id'
        ]);

        $count = TruckNumber::whereIn('id', $validated['truck_number_ids'])->delete();

        return redirect()
            ->route('admin.truck-numbers.index')
            ->with('success', "{$count} truck numbers deleted successfully.");
    }

    /**
     * Bulk import truck numbers
     */
    public function bulkImport(Request $request)
    {
        $validated = $request->validate([
            'truck_numbers' => 'required|string',
        ]);

        $truckNumbers = array_filter(
            array_map('trim', explode("\n", $validated['truck_numbers'])),
            function($number) {
                return !empty($number);
            }
        );

        $created = 0;
        $skipped = 0;

        foreach ($truckNumbers as $number) {
            $number = strtoupper(trim($number));
            
            if (!TruckNumber::where('truck_number', $number)->exists()) {
                TruckNumber::create(['truck_number' => $number]);
                $created++;
            } else {
                $skipped++;
            }
        }

        $message = "Import completed: {$created} created";
        if ($skipped > 0) {
            $message .= ", {$skipped} skipped (duplicates)";
        }

        return redirect()
            ->route('admin.truck-numbers.index')
            ->with('success', $message);
    }

    /**
     * API endpoint to get truck numbers for autocomplete
     */
    public function api(Request $request)
    {
        $query = TruckNumber::query();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $truckNumbers = $query->limit(10)->get(['id', 'truck_number']);

        return response()->json($truckNumbers);
    }
}