@extends('components.layouts.app')

@section('title', 'Add Truck Number')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.truck-numbers.index') }}" 
               class="text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Add Truck Number</h1>
                <p class="text-gray-600">Create a new truck number entry</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow">
            <form method="POST" action="{{ route('admin.truck-numbers.store') }}">
                @csrf
                
                <!-- Form Header -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Truck Number Details</h3>
                    <p class="text-sm text-gray-600">Enter the truck number information below</p>
                </div>

                <!-- Form Body -->
                <div class="px-6 py-6 space-y-6">
                    <!-- Truck Number Field -->
                    <div>
                        <label for="truck_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Truck Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="truck_number" 
                               name="truck_number" 
                               value="{{ old('truck_number') }}"
                               placeholder="Enter truck number (e.g., ABC123, TR-001)"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('truck_number') border-red-500 @enderror">
                        
                        @error('truck_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        <p class="mt-1 text-xs text-gray-500">
                            The truck number will be automatically formatted to uppercase and must be unique.
                        </p>
                    </div>

  

                </div>

                <!-- Form Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center rounded-b-lg">
                    <div class="text-sm text-gray-500">
                        <span class="text-red-500">*</span> Required fields
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.truck-numbers.index') }}" 
                           class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add Truck Number
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Quick Add Multiple -->
        <div class="mt-6 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Quick Add Multiple</h3>
                <p class="text-sm text-gray-600">Add multiple truck numbers at once</p>
            </div>
            
            <form method="POST" action="{{ route('admin.truck-numbers.bulk-import') }}">
                @csrf
                <div class="px-6 py-6">
                    <div>
                        <label for="truck_numbers" class="block text-sm font-medium text-gray-700 mb-2">
                            Truck Numbers (one per line)
                        </label>
                        <textarea id="truck_numbers" 
                                  name="truck_numbers" 
                                  rows="6" 
                                  placeholder="ABC123&#10;DEF456&#10;GHI789&#10;TR-001&#10;TR-002"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        <p class="mt-1 text-xs text-gray-500">
                            Enter each truck number on a separate line. Duplicates will be automatically skipped.
                        </p>
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end rounded-b-lg">
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                        </svg>
                        Import All
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>


// Auto-format input to uppercase
document.getElementById('truck_number').addEventListener('blur', function() {
    this.value = this.value.toUpperCase().trim();
});

// Focus on truck number input when page loads
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('truck_number').focus();
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const truckNumber = document.getElementById('truck_number').value.trim();
    
    if (!truckNumber) {
        e.preventDefault();
        alert('Please enter a truck number.');
        document.getElementById('truck_number').focus();
        return;
    }
    
    if (truckNumber.length > 50) {
        e.preventDefault();
        alert('Truck number cannot be longer than 50 characters.');
        document.getElementById('truck_number').focus();
        return;
    }
});

// Bulk import form validation
document.querySelector('form[action*="bulk-import"]').addEventListener('submit', function(e) {
    const truckNumbers = document.getElementById('truck_numbers').value.trim();
    
    if (!truckNumbers) {
        e.preventDefault();
        alert('Please enter at least one truck number.');
        document.getElementById('truck_numbers').focus();
        return;
    }
    
    const lines = truckNumbers.split('\n').filter(line => line.trim().length > 0);
    if (lines.length === 0) {
        e.preventDefault();
        alert('Please enter valid truck numbers.');
        document.getElementById('truck_numbers').focus();
        return;
    }
    
    if (!confirm(`Are you sure you want to import ${lines.length} truck number${lines.length > 1 ? 's' : ''}?`)) {
        e.preventDefault();
    }
});
</script>
@endsection