@extends('components.layouts.app')

@section('title', 'Damage Report Details')

@section('content')
<div class="p-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">Damage Report Details</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $damage->truck->license_plate }} - {{ $damage->truck->make }} {{ $damage->truck->model }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $damage->status_color }}">
                        @if($damage->status === 'reported')
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        @elseif($damage->status === 'in_repair')
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                            </svg>
                        @elseif($damage->status === 'fixed')
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                        {{ ucfirst(str_replace('_', ' ', $damage->status)) }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $damage->severity_color }}">
                        @if($damage->severity === 'critical')
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        @elseif($damage->severity === 'major')
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        @else
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 011 1v4a1 1 0 11-2 0V6a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                        {{ ucfirst($damage->severity) }}
                    </span>
                </div>
            </div>
            <div class="flex space-x-3">
                @if($damage->status !== 'fixed')
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Update Status
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                            @if($damage->status === 'reported')
                                <form method="POST" action="{{ route('admin.damages.update-status', $damage) }}" class="block">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="in_repair">
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Mark as In Repair
                                    </button>
                                </form>
                            @endif
                         
                                <button onclick="openFixedModal()" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Mark as Fixed
                                </button>
                  

                        </div>
                    </div>
                @endif

                @if($damage->controlLine)
                    <a href="{{ route('admin.control.show', $damage->controlLine) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        View Control
                    </a>
                @endif

                <a href="{{ route('admin.damages.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Damages
                </a>
            </div>
        </div>

        <!-- Damage Info -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Damage Details -->
                <div class="lg:col-span-2">
                    <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Damage Location</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $damage->damage_location ?? 'Not specified' }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="text-gray-900">{{ $damage->damage_description }}</dd>
                        </div>

                        @if($damage->damage_area)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Damage Area(s)</dt>
                                <dd class="text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        Area: {{ $damage->damage_area }}
                                    </span>
                                    @if(count($damage->parsed_damage_areas) > 0)
                                        <div class="text-xs text-gray-500 mt-1">
                                            Reference points: {{ implode(', ', $damage->parsed_damage_areas) }}
                                        </div>
                                    @endif
                                </dd>
                            </div>
                        @endif
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Reported By</dt>
                                <dd class="text-gray-900">{{ $damage->reportedBy->name }}</dd>
                                <dd class="text-sm text-gray-600">{{ $damage->reportedBy->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Reported Date</dt>
                                <dd class="text-gray-900">{{ $damage->created_at->format('M j, Y \a\t g:i A') }}</dd>
                                <dd class="text-sm text-gray-600">{{ $damage->created_at->diffForHumans() }}</dd>
                            </div>
                        </div>

                        @if($damage->repair_notes)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Repair Notes</dt>
                                <dd class="text-gray-900">{{ $damage->repair_notes }}</dd>
                            </div>
                        @endif

                        @if($damage->fixed_date)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fixed Date</dt>
                                <dd class="text-gray-900">{{ $damage->fixed_date->format('M j, Y') }}</dd>
                                <dd class="text-sm text-green-600">
                                    Repair took {{ $damage->created_at->diffInDays($damage->fixed_date) }} days
                                </dd>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Context Info -->
                <div>
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Context Information</h4>
                    <div class="space-y-4">
                        <!-- Truck Info -->
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 011 1v1a1 1 0 01-1 1v9a1 1 0 01-1 1H4a1 1 0 01-1-1V7a1 1 0 01-1-1V5a1 1 0 011-1h4zM9 3v1h6V3H9z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-blue-900">{{ $damage->truck->license_plate }}</div>
                                    <div class="text-sm text-blue-800">{{ $damage->truck->make }} {{ $damage->truck->model }}</div>
                                    <div class="text-xs text-blue-700">Year: {{ $damage->truck->year }}</div>
                                </div>
                            </div>
                        </div>

                        @if($damage->controlLine)
                            <!-- Control Info -->
                            <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-green-900">Control #{{ $damage->controlLine->id }}</div>
                                        <div class="text-sm text-green-800">{{ $damage->controlLine->controlTemplate->name }}</div>
                                        <div class="text-xs text-green-700">
                                            Assigned to: {{ $damage->controlLine->assignedUser->name }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($damage->controlTask)
                            <!-- Task Info -->
                            <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-purple-900">Task Related</div>
                                        <div class="text-sm text-purple-800">{{ $damage->controlTask->title }}</div>
                                        <div class="text-xs text-purple-700">
                                            {{ ucfirst($damage->controlTask->task_type) }} task
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Status Timeline -->
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <h5 class="text-sm font-medium text-gray-900 mb-3">Status Timeline</h5>
                            <div class="space-y-2">
                                <div class="flex items-center text-sm">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                                    <div>
                                        <span class="font-medium">Reported</span>
                                        <div class="text-xs text-gray-500">{{ $damage->created_at->format('M j, Y g:i A') }}</div>
                                    </div>
                                </div>
                                @if($damage->status === 'in_repair' || $damage->status === 'fixed')
                                    <div class="flex items-center text-sm">
                                        <div class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></div>
                                        <div>
                                            <span class="font-medium">In Repair</span>
                                            <div class="text-xs text-gray-500">{{ $damage->updated_at->format('M j, Y g:i A') }}</div>
                                        </div>
                                    </div>
                                @endif
                                @if($damage->status === 'fixed')
                                    <div class="flex items-center text-sm">
                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                        <div>
                                            <span class="font-medium">Fixed</span>
                                            <div class="text-xs text-gray-500">{{ $damage->fixed_date->format('M j, Y') }}</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Photos Section -->
    @if($damage->damage_photos && count($damage->getPhotoUrls()) > 0)
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h4 class="text-lg font-medium text-gray-900">Damage Photos ({{ count($damage->getPhotoUrls()) }})</h4>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($damage->getPhotoUrls() as $index => $photo)
                        <div class="relative group">
                            <img src="{{ $photo['url'] }}" 
                                 alt="{{ $photo['name'] }}"
                                 class="w-full h-48 object-cover rounded-lg border border-gray-200 cursor-pointer hover:scale-105 transition-transform shadow-sm"
                                 onclick="openImagePreview('{{ $photo['url'] }}', '{{ $photo['name'] }}', {{ $index }})">
                            <!-- Overlay with filename -->
                            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-2 rounded-b-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                {{ Str::limit($photo['name'], 30) }}
                            </div>
                            <!-- Photo number -->
                            <div class="absolute top-2 left-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                                {{ $index + 1 }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Related Actions -->
    <div class="bg-white rounded-lg shadow hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h4 class="text-lg font-medium text-gray-900">Administrative Actions</h4>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Export Report -->
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div>
                            <h5 class="font-medium text-blue-900">Export Report</h5>
                            <p class="text-sm text-blue-700">Download damage report</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button onclick="exportReport()" 
                                class="text-xs bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition-colors">
                            Export PDF
                        </button>
                    </div>
                </div>

                <!-- Add Note -->
                <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <div>
                            <h5 class="font-medium text-yellow-900">Add Note</h5>
                            <p class="text-sm text-yellow-700">Add administrative note</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button onclick="openNoteModal()" 
                                class="text-xs bg-yellow-600 text-white px-3 py-1 rounded hover:bg-yellow-700 transition-colors">
                            Add Note
                        </button>
                    </div>
                </div>

                <!-- Delete Report -->
                <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <div>
                            <h5 class="font-medium text-red-900">Delete Report</h5>
                            <p class="text-sm text-red-700">Permanently remove</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button onclick="confirmDelete()" 
                                class="text-xs bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition-colors">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fixed Modal -->
<div id="fixedModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Mark Damage as Fixed</h3>
            <form method="POST" action="{{ route('admin.damages.mark-fixed', $damage) }}">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fixed Date</label>
                    <input type="date" name="fixed_date" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md" 
                           value="{{ date('Y-m-d') }}">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Repair Notes</label>
                    <textarea name="repair_notes" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md"
                              placeholder="Optional repair details..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeFixedModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Mark as Fixed
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openImagePreview(imageSrc, title, index) {
        const photos = @json($damage->getPhotoUrls());
        let currentIndex = index;
        
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-gray-900 bg-opacity-90 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4';
        modal.onclick = function(e) {
            if (e.target === this) {
                document.body.removeChild(this);
            }
        };
        
        function updateImage() {
            const photo = photos[currentIndex];
            modal.innerHTML = `
                <div class="relative bg-white p-6 rounded-lg max-w-5xl max-h-full overflow-auto shadow-xl">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-gray-900">Damage Photo ${currentIndex + 1} of ${photos.length}</h3>
                        <button onclick="document.body.removeChild(this.closest('.fixed'))" class="text-gray-400 hover:text-gray-600 p-2 rounded-full hover:bg-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="text-center">
                        <img src="${photo.url}" alt="${photo.name}" class="max-w-full max-h-96 mx-auto rounded-lg shadow-lg">
                        <p class="text-center text-sm text-gray-600 mt-4">${photo.name}</p>
                    </div>
                    ${photos.length > 1 ? `
                        <div class="flex justify-between items-center mt-4">
                            <button onclick="previousImage()" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 ${currentIndex === 0 ? 'opacity-50 cursor-not-allowed' : ''}">
                                Previous
                            </button>
                            <span class="text-sm text-gray-600">${currentIndex + 1} of ${photos.length}</span>
                            <button onclick="nextImage()" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 ${currentIndex === photos.length - 1 ? 'opacity-50 cursor-not-allowed' : ''}">
                                Next
                            </button>
                        </div>
                    ` : ''}
                </div>
            `;
        }
        
        window.previousImage = function() {
            if (currentIndex > 0) {
                currentIndex--;
                updateImage();
            }
        };
        
        window.nextImage = function() {
            if (currentIndex < photos.length - 1) {
                currentIndex++;
                updateImage();
            }
        };
        
        updateImage();
        document.body.appendChild(modal);
    }

    function openFixedModal() {
        document.getElementById('fixedModal').classList.remove('hidden');
    }

    function closeFixedModal() {
        document.getElementById('fixedModal').classList.add('hidden');
    }

    function exportReport() {
        // Implementation for exporting damage report
        alert('Export functionality would be implemented here');
    }

    function openNoteModal() {
        // Implementation for adding administrative notes
        alert('Add note functionality would be implemented here');
    }

    function confirmDelete() {
        if (confirm('Are you sure you want to delete this damage report? This action cannot be undone.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.damages.destroy", $damage) }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Keyboard navigation for image gallery
    document.addEventListener('keydown', function(e) {
        if (document.querySelector('.fixed')) {
            if (e.key === 'ArrowLeft' && window.previousImage) {
                window.previousImage();
            } else if (e.key === 'ArrowRight' && window.nextImage) {
                window.nextImage();
            } else if (e.key === 'Escape') {
                const modal = document.querySelector('.fixed');
                if (modal) {
                    document.body.removeChild(modal);
                }
            }
        }
    });
</script>
@endsection