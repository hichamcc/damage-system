@extends('components.layouts.app')

@section('title', 'View Truck')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">Truck Details: {{ $truck->truck_number }}</h3>
            <div class="flex space-x-3">
                <a href="{{ route('admin.trucks.edit', $truck) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Truck
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
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Truck Overview -->
                <div class="lg:col-span-1">
                    <div class="text-center">
                        <!-- Truck Icon/Number Display -->
                        <div class="mx-auto h-32 w-32 rounded-lg bg-blue-500 flex items-center justify-center text-white text-2xl font-bold mb-4">
                            <div class="text-center">
                                <svg class="w-12 h-12 mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13.5-9l1.96 2.5H17V9.5h2.5zm-1.5 9c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                                </svg>
                                <div class="text-sm font-medium">#{{ $truck->truck_number }}</div>
                            </div>
                        </div>
                        
                        <!-- Truck Name and Status -->
                        <h2 class="text-xl font-semibold text-gray-900">{{ $truck->make }} {{ $truck->model }}</h2>
                        <p class="text-sm text-gray-500 mb-3">{{ $truck->year }} • {{ $truck->license_plate }}</p>
                        
                        <div class="mb-4">
                            <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full {{ $truck->status_badge_color }}">
                                {{ $truck->formatted_status }}
                            </span>
                        </div>
                        
                        <!-- Status Alerts -->
                        <div class="space-y-2 mb-6">
                            <!-- No service or expiry alerts since those fields are removed -->
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            <form action="{{ route('admin.trucks.destroy', $truck) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out delete-button">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete Truck
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Detailed Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Truck Number</label>
                                <p class="mt-1 text-sm text-gray-900">#{{ $truck->truck_number }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Type</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $truck->type === 'truck' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($truck->type) }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">License Plate</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $truck->license_plate }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Make & Model</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $truck->make }} {{ $truck->model }}</p>
                            </div>

                        </div>
                    </div>
                    
                    
                    <!-- Attachments -->
                    @if($truck->attachments && count($truck->attachments) > 0)
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Attachments</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($truck->getAttachmentUrls() as $index => $attachment)
                                    <div class="flex items-center p-3 bg-white rounded-lg border border-gray-200">
                                        <div class="flex-shrink-0 mr-3">
                                            @if(str_contains($attachment['type'], 'image'))
                                                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            @elseif(str_contains($attachment['type'], 'pdf'))
                                                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                </svg>
                                            @else
                                                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $attachment['name'] }}</p>
                                            <p class="text-xs text-gray-500">{{ number_format($attachment['size'] / 1024, 1) }} KB</p>
                                        </div>
                                        <div class="ml-3">
                                            <a href="{{ route('admin.trucks.attachments.download', [$truck, $index]) }}" 
                                               class="text-blue-600 hover:text-blue-800" 
                                               title="Download">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- Notes -->
                    @if($truck->notes)
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Notes</h3>
                            <div class="prose text-sm text-gray-600">
                                {!! nl2br(e($truck->notes)) !!}
                            </div>
                        </div>
                    @endif
                    
                    <!-- Truck History -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Truck History</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Added to System</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $truck->created_at->format('F d, Y g:i A') }}</p>
                                <p class="text-xs text-gray-500">{{ $truck->created_at->diffForHumans() }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Last Updated</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $truck->updated_at->format('F d, Y g:i A') }}</p>
                                <p class="text-xs text-gray-500">{{ $truck->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete confirmation
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (confirm('Are you sure you want to delete this truck? This action cannot be undone and will also delete all associated files.')) {
                this.closest('.delete-form').submit();
            }
        });
    });
});
</script>
@endpush