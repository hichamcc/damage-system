@extends('components.layouts.app')

@section('title', 'Edit Truck Number')

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
                <h1 class="text-2xl font-bold text-gray-900">Edit Truck Number</h1>
                <p class="text-gray-600">Update truck number information</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow">
            <form method="POST" action="{{ route('admin.truck-numbers.update', $truckNumber) }}">
                @csrf
                @method('PUT')
                
                <!-- Form Header -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Truck Number Details</h3>
                            <p class="text-sm text-gray-600">Update the truck number information below</p>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>ID: {{ $truckNumber->id }}</span>
                        </div>
                    </div>
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
                               value="{{ old('truck_number', $truckNumber->truck_number) }}"
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
                                id="save-button"
                                class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Update Truck Number
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Danger Zone -->
        <div class="mt-6 bg-white rounded-lg shadow border border-red-200">
            <div class="px-6 py-4 border-b border-red-200 bg-red-50">
                <h3 class="text-lg font-medium text-red-900">Danger Zone</h3>
                <p class="text-sm text-red-700">Irreversible and destructive actions</p>
            </div>
            
            <div class="px-6 py-6">
                <div class="flex items-start justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Delete this truck number</h4>
                        <p class="text-sm text-gray-600 mt-1">
                            Permanently remove this truck number from the system. This action cannot be undone.
                        </p>
                    </div>
                    <button onclick="confirmDelete()" 
                            class="ml-4 px-4 py-2 bg-red-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form" method="POST" action="{{ route('admin.truck-numbers.destroy', $truckNumber) }}" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
const originalTruckNumber = @json($truckNumber->truck_number);

// Live preview of truck number formatting
document.getElementById('truck_number').addEventListener('input', function() {
    const value = this.value.toUpperCase().trim();
    const preview = document.getElementById('preview-number');
    const changeIndicator = document.getElementById('change-indicator');
    const saveButton = document.getElementById('save-button');
    
    if (value) {
        preview.textContent = value;
        preview.className = 'text-sm font-medium text-gray-900';
        
        // Show change indicator if different from original
        if (value !== originalTruckNumber) {
            changeIndicator.classList.remove('hidden');
            saveButton.innerHTML = `
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Save Changes
            `;
        } else {
            changeIndicator.classList.add('hidden');
            saveButton.innerHTML = `
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Update Truck Number
            `;
        }
    } else {
        preview.textContent = 'Enter truck number above';
        preview.className = 'text-sm font-medium text-gray-500';
        changeIndicator.classList.add('hidden');
    }
});

// Auto-format input to uppercase
document.getElementById('truck_number').addEventListener('blur', function() {
    this.value = this.value.toUpperCase().trim();
    // Trigger input event to update preview
    this.dispatchEvent(new Event('input'));
});

// Focus on truck number input when page loads
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('truck_number').focus();
    // Select all text for easy editing
    document.getElementById('truck_number').select();
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
    
    // Confirm if there are changes
    if (truckNumber.toUpperCase() !== originalTruckNumber) {
        if (!confirm(`Are you sure you want to change the truck number from "${originalTruckNumber}" to "${truckNumber.toUpperCase()}"?\n\nThis will update all references in the system.`)) {
            e.preventDefault();
            return;
        }
    }
});

// Delete confirmation
function confirmDelete() {
    if (confirm(`Are you sure you want to delete truck number "${originalTruckNumber}"?\n\nThis action cannot be undone and will remove all references to this truck number.`)) {
        if (confirm('This is a permanent action. Are you absolutely sure?')) {
            document.getElementById('delete-form').submit();
        }
    }
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl+S or Cmd+S to save
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        document.querySelector('form').submit();
    }
    
    // Escape to cancel
    if (e.key === 'Escape') {
        window.location.href = '{{ route("admin.truck-numbers.index") }}';
    }
});
</script>
@endsection