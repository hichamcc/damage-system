@extends('components.layouts.app')

@section('title', 'Edit Truck')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">Edit Truck: {{ $truck->truck_number }}</h3>
            <div class="flex space-x-3">
                <a href="{{ route('admin.trucks.show', $truck) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    View Truck
                </a>
                <a href="{{ route('admin.trucks.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Trucks
                </a>
            </div>
        </div>
        
        <div class="p-6">
            <form action="{{ route('admin.trucks.update', $truck->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Basic Information -->
                    <div class="space-y-6">
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-4">Basic Information</h4>
                            
                            <!-- Truck Number -->
                            <div class="mb-4">
                                <label for="truck_number" class="block text-sm font-medium text-gray-700 mb-2">
                                    Truck Number <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('truck_number') border-red-500 @enderror" 
                                       id="truck_number" 
                                       name="truck_number" 
                                       value="{{ old('truck_number', $truck->truck_number) }}" 
                                       required>
                                @error('truck_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- License Plate -->
                            <div class="mb-4">
                                <label for="license_plate" class="block text-sm font-medium text-gray-700 mb-2">
                                    License Plate <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('license_plate') border-red-500 @enderror" 
                                       id="license_plate" 
                                       name="license_plate" 
                                       value="{{ old('license_plate', $truck->license_plate) }}" 
                                       required>
                                @error('license_plate')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Make -->
                            <div class="mb-4">
                                <label for="make" class="block text-sm font-medium text-gray-700 mb-2">
                                    Make <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('make') border-red-500 @enderror" 
                                       id="make" 
                                       name="make" 
                                       value="{{ old('make', $truck->make) }}" 
                                       placeholder="e.g., Volvo, Mercedes, Scania"
                                       required>
                                @error('make')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Model -->
                            <div class="mb-4">
                                <label for="model" class="block text-sm font-medium text-gray-700 mb-2">
                                    Model <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('model') border-red-500 @enderror" 
                                       id="model" 
                                       name="model" 
                                       value="{{ old('model', $truck->model) }}" 
                                       required>
                                @error('model')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror" 
                                        id="status" 
                                        name="status" 
                                        required>
                                    <option value="">Select Status</option>
                                    <option value="active" {{ old('status', $truck->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="maintenance" {{ old('status', $truck->status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="out_of_service" {{ old('status', $truck->status) === 'out_of_service' ? 'selected' : '' }}>Out of Service</option>
                                    <option value="retired" {{ old('status', $truck->status) === 'retired' ? 'selected' : '' }}>Retired</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Truck Info Card -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Truck Information</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Truck ID:</span>
                                        <span class="text-gray-900">#{{ $truck->id }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Created:</span>
                                        <span class="text-gray-900">{{ $truck->created_at->format('M d, Y g:i A') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Last Updated:</span>
                                        <span class="text-gray-900">{{ $truck->updated_at->format('M d, Y g:i A') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Service & Document Information -->
                    <div class="space-y-6">
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-4">Additional Information</h4>

                            <!-- Add New Attachments -->
                            <div class="mb-4">
                                <label for="attachments" class="block text-sm font-medium text-gray-700 mb-2">
                                    Add New Attachments
                                    <span class="text-xs text-gray-500">(PDF, Images, Documents)</span>
                                </label>
                                <input type="file" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('attachments.*') border-red-500 @enderror" 
                                       id="attachments" 
                                       name="attachments[]" 
                                       multiple
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                @error('attachments.*')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Maximum file size: 10MB. Supported formats: PDF, JPG, PNG, DOC, DOCX</p>
                            </div>

                            <!-- Notes -->
                            <div class="mb-4">
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror" 
                                          id="notes" 
                                          name="notes" 
                                          rows="4"
                                          placeholder="Additional notes about this truck...">{{ old('notes', $truck->notes) }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.trucks.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out" id="submit-button">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            Update Truck
                        </button>
                    </div>
                </div>
            </form>
            
            <!-- Current Attachments -->
            @if($truck->attachments && count($truck->attachments) > 0)
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Attachments</label>
                <div class="space-y-2">
                    @foreach($truck->getAttachmentUrls() as $index => $attachment)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    @if(str_contains($attachment['type'], 'image'))
                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    @elseif(str_contains($attachment['type'], 'pdf'))
                                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $attachment['name'] }}</p>
                                    <p class="text-xs text-gray-500">{{ number_format($attachment['size'] / 1024, 1) }} KB</p>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.trucks.attachments.download', [$truck, $index]) }}" 
                                    class="text-blue-600 hover:text-blue-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.trucks.attachments.remove', [$truck, $index]) }}" 
                                        method="POST" 
                                        class="inline remove-attachment-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                            class="text-red-600 hover:text-red-800 remove-attachment-button">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚛 Truck edit script loaded');
    
    // Remove attachment confirmation
    document.querySelectorAll('.remove-attachment-button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (confirm('Are you sure you want to remove this attachment? This action cannot be undone.')) {
                this.closest('.remove-attachment-form').submit();
            }
        });
    });

    // Debug form submission
    const form = document.querySelector('form');
    const submitButton = document.getElementById('submit-button');
    
    console.log('Form found:', !!form);
    console.log('Submit button found:', !!submitButton);
    
    if (form && submitButton) {
        // Debug button click
        submitButton.addEventListener('click', function(e) {
            console.log('Submit button clicked');
            
            // Check if form is valid
            if (!form.checkValidity()) {
                console.log('Form is not valid');
                form.reportValidity();
                return;
            }
            
            console.log('Form should submit now');
        });

        // Debug form submission
        form.addEventListener('submit', function(e) {
            console.log('Form submitting...');
            console.log('Form action:', this.action);
            console.log('Form method:', this.method);
            
            // Disable button to prevent double submission
            submitButton.disabled = true;
            submitButton.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Updating...';
        });
    }
});
</script>
@endpush