@extends('components.layouts.app')

@section('title', 'Damage Report Details')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Damage Report #{{ $damage->id }}</h3>
                <p class="text-sm text-gray-500">{{ $damage->controlLine->truck->license_plate }} - Control #{{ $damage->controlLine->id }}</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.control.damages', $damage->controlLine) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md">
                    Back to Control Damages
                </a>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Damage Information -->
                <div class="space-y-6">
                    <!-- Basic Info -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-md font-medium text-gray-900 mb-4">Damage Information</h4>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Location</label>
                                <p class="text-sm font-medium text-gray-900">{{ $damage->damage_location }}</p>
                            </div>
                            
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Description</label>
                                <p class="text-sm text-gray-900">{{ $damage->damage_description }}</p>
                            </div>
                            
                            @if($damage->damage_area)
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Damage Areas</label>
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $damage->damage_area_display }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Reference template areas: {{ implode(', ', $damage->parsed_damage_areas) }}</p>
                            </div>
                            @endif
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Severity</label>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($damage->severity === 'critical') bg-red-100 text-red-800
                                        @elseif($damage->severity === 'major') bg-orange-100 text-orange-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ ucfirst($damage->severity) }}
                                    </span>
                                </div>
                                
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status</label>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($damage->status === 'reported') bg-red-100 text-red-800
                                        @elseif($damage->status === 'in_repair') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $damage->status)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reporter Info -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-md font-medium text-gray-900 mb-4">Report Details</h4>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Reported By</label>
                                <p class="text-sm text-gray-900">{{ $damage->reportedBy->name }}</p>
                                <p class="text-xs text-gray-500">{{ $damage->reportedBy->email }}</p>
                            </div>
                            
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Report Date</label>
                                <p class="text-sm text-gray-900">{{ $damage->created_at->format('M d, Y H:i') }}</p>
                            </div>
                            
                            @if($damage->controlTask)
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Related Task</label>
                                <p class="text-sm text-gray-900">{{ $damage->controlTask->title }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Repair Info -->
                    @if($damage->status === 'fixed')
                    <div class="bg-green-50 rounded-lg p-4">
                        <h4 class="text-md font-medium text-green-900 mb-4">Repair Information</h4>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs font-medium text-green-600 uppercase tracking-wide">Fixed Date</label>
                                <p class="text-sm text-green-800">{{ $damage->fixed_date?->format('M d, Y') }}</p>
                            </div>
                            
                            @if($damage->repair_notes)
                            <div>
                                <label class="text-xs font-medium text-green-600 uppercase tracking-wide">Repair Notes</label>
                                <p class="text-sm text-green-800">{{ $damage->repair_notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Photos and Templates -->
                <div class="space-y-6">
                    <!-- Damage Photos -->
                    @if($damage->damage_photos && count($damage->damage_photos) > 0)
                    <div>
                        <h4 class="text-md font-medium text-gray-900 mb-4">Damage Photos</h4>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($damage->damage_photos as $photo)
                                <div class="aspect-w-16 aspect-h-9">
                                    <img src="{{ Storage::url($photo['path']) }}" 
                                         alt="Damage photo"
                                         class="w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-80"
                                         onclick="openPhotoModal('{{ Storage::url($photo['path']) }}')">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Template Reference (if damage area specified) -->
                    @if($damage->damage_area)
                    <div>
                        <h4 class="text-md font-medium text-gray-900 mb-4">Template Reference</h4>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-sm text-blue-800 mb-3">
                                <strong>Damaged Areas:</strong> {{ $damage->damage_area }}
                            </p>
                            <p class="text-xs text-blue-600">
                                Refer to truck templates to locate the specific numbered areas mentioned above.
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Photo Modal -->
<div id="photo-modal" class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Damage Photo</h3>
            <button onclick="closePhotoModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="text-center">
            <img id="modal-photo" class="max-w-full max-h-96 mx-auto rounded">
        </div>
    </div>
</div>

<script>
function openPhotoModal(photoSrc) {
    document.getElementById('modal-photo').src = photoSrc;
    document.getElementById('photo-modal').classList.remove('hidden');
}

function closePhotoModal() {
    document.getElementById('photo-modal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('photo-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePhotoModal();
    }
});
</script>
@endsection