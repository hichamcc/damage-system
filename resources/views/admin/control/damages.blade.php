@extends('components.layouts.app')

@section('title', 'Control Damage Reports')

@section('content')
<div class="p-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">Damage Reports for Control</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $controlLine->truck->license_plate }} - {{ $controlLine->truck->make }} {{ $controlLine->truck->model }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $controlLine->status === 'active' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                        {{ ucfirst($controlLine->status) }}
                    </span>
                    @if($damages->count() > 0)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            {{ $damages->count() }} Damage{{ $damages->count() > 1 ? 's' : '' }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.control.show', $controlLine) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    View Control
                </a>
                <a href="{{ route('admin.control.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Controls
                </a>
            </div>
        </div>

        <!-- Control Summary -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Control Details -->
                <div class="lg:col-span-2">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Template</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $controlLine->controlTemplate->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Assigned User</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $controlLine->assignedUser->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Created</dt>
                                <dd class="text-sm text-gray-900">{{ $controlLine->created_at->format('M j, Y g:i A') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Assigned</dt>
                                <dd class="text-sm text-gray-900">{{ $controlLine->assigned_at->format('M j, Y g:i A') }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Damage Summary Stats -->
                <div class="lg:col-span-2">
                    <div class="grid grid-cols-2 gap-4">
                        @php
                            $statusCounts = $damages->groupBy('status')->map->count();
                            $severityCounts = $damages->groupBy('severity')->map->count();
                        @endphp
                        <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-red-600">{{ $damages->count() }}</div>
                                <div class="text-sm text-red-800">Total Damages</div>
                            </div>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-yellow-600">{{ $statusCounts->get('reported', 0) + $statusCounts->get('in_repair', 0) }}</div>
                                <div class="text-sm text-yellow-800">Active Issues</div>
                            </div>
                        </div>
                        <div class="bg-orange-50 rounded-lg p-4 border border-orange-200">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-orange-600">{{ $severityCounts->get('critical', 0) + $severityCounts->get('major', 0) }}</div>
                                <div class="text-sm text-orange-800">Major+ Issues</div>
                            </div>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ $statusCounts->get('fixed', 0) }}</div>
                                <div class="text-sm text-green-800">Fixed</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Damage Reports -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h4 class="text-lg font-medium text-gray-900">Damage Reports ({{ $damages->count() }})</h4>
            <!-- Filter buttons -->
            <div class="flex space-x-2">
                <button onclick="filterDamages('all')" class="damage-filter-btn px-3 py-1 text-sm rounded-md bg-blue-600 text-white transition-colors" data-filter="all">
                    All
                </button>
                <button onclick="filterDamages('reported')" class="damage-filter-btn px-3 py-1 text-sm rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors" data-filter="reported">
                    Reported
                </button>
                <button onclick="filterDamages('in_repair')" class="damage-filter-btn px-3 py-1 text-sm rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors" data-filter="in_repair">
                    In Repair
                </button>
                <button onclick="filterDamages('fixed')" class="damage-filter-btn px-3 py-1 text-sm rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors" data-filter="fixed">
                    Fixed
                </button>
                <button onclick="filterDamages('critical')" class="damage-filter-btn px-3 py-1 text-sm rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors" data-filter="critical">
                    Critical
                </button>
            </div>
        </div>

        @if($damages->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($damages as $damage)
                    <div class="damage-item p-6 hover:bg-gray-50" 
                         data-status="{{ $damage->status }}" 
                         data-severity="{{ $damage->severity }}">
                        <div class="flex items-start space-x-4">
                            <!-- Damage Image -->
                            <div class="flex-shrink-0">
                                @if($damage->damage_photos && count($damage->getPhotoUrls()) > 0)
                                    <div class="w-20 h-16 rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                        <img src="{{ $damage->getPhotoUrls()[0]['url'] }}" 
                                             alt="Damage preview"
                                             class="w-full h-full object-cover cursor-pointer hover:scale-105 transition-transform"
                                             onclick="openImagePreview('{{ $damage->getPhotoUrls()[0]['url'] }}', '{{ $damage->getPhotoUrls()[0]['name'] }}', {{ $damage->id }})">
                                    </div>
                                    @if(count($damage->getPhotoUrls()) > 1)
                                        <div class="text-xs text-gray-500 text-center mt-1">
                                            +{{ count($damage->getPhotoUrls()) - 1 }}
                                        </div>
                                    @endif
                                @else
                                    <div class="w-20 h-16 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Damage Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h5 class="text-lg font-medium text-gray-900">
                                                {{ $damage->damage_location ?? 'Damage Report' }}
                                            </h5>
                                            <!-- Status Badge -->
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $damage->status_color }}">
                                                @if($damage->status === 'reported')
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                    </svg>
                                                @elseif($damage->status === 'in_repair')
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                                                    </svg>
                                                @elseif($damage->status === 'fixed')
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                @endif
                                                {{ ucfirst(str_replace('_', ' ', $damage->status)) }}
                                            </span>
                                            <!-- Severity Badge -->
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $damage->severity_color }}">
                                                @if($damage->severity === 'critical')
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                @elseif($damage->severity === 'major')
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 011 1v4a1 1 0 11-2 0V6a1 1 0 011-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                @endif
                                                {{ ucfirst($damage->severity) }}
                                            </span>
                                        </div>
                                        
                                        <p class="text-gray-600 mb-3">{{ $damage->damage_description }}</p>

                                        <!-- Damage Details Grid -->
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                                            @if($damage->damage_area)
                                                <div>
                                                    <div class="text-xs font-medium text-gray-500">Damage Area</div>
                                                    <div class="text-sm text-blue-600">
                                                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        </svg>
                                                        Area: {{ $damage->damage_area }}
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            @if($damage->controlTask)
                                                <div>
                                                    <div class="text-xs font-medium text-gray-500">Related Task</div>
                                                    <div class="text-sm text-gray-800">{{ Str::limit($damage->controlTask->title, 30) }}</div>
                                                </div>
                                            @endif
                                            
                                            <div>
                                                <div class="text-xs font-medium text-gray-500">Reported By</div>
                                                <div class="text-sm text-gray-800">{{ $damage->reportedBy->name }}</div>
                                            </div>
                                        </div>

                                        <!-- Timestamps -->
                                        <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500">
                                            <div>
                                                <span class="font-medium">Reported:</span> 
                                                {{ $damage->created_at->format('M j, Y g:i A') }}
                                                ({{ $damage->created_at->diffForHumans() }})
                                            </div>
                                            @if($damage->fixed_date)
                                                <div class="text-green-600">
                                                    <span class="font-medium">Fixed:</span> 
                                                    {{ $damage->fixed_date->format('M j, Y') }}
                                                </div>
                                            @endif
                                        </div>

                                        @if($damage->repair_notes)
                                            <div class="mt-3 p-3 bg-blue-50 rounded border border-blue-200">
                                                <div class="text-xs font-medium text-blue-800 mb-1">Repair Notes:</div>
                                                <div class="text-sm text-blue-900">{{ $damage->repair_notes }}</div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center space-x-2 ml-4">
                                        <a href="{{ route('admin.damages.show', $damage) }}" 
                                           class="text-blue-600 hover:text-blue-900 p-2 rounded-full hover:bg-blue-50 transition-colors" 
                                           title="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        
                                        @if($damage->status !== 'fixed')
                                            <div class="relative" x-data="{ open: false }">
                                                <button @click="open = !open" 
                                                        class="text-gray-600 hover:text-gray-900 p-2 rounded-full hover:bg-gray-50 transition-colors"
                                                        title="Update Status">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                    </svg>
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
                                                    @if($damage->status === 'in_repair')
                                                        <button onclick="openFixedModal({{ $damage->id }})" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            Mark as Fixed
                                                        </button>
                                                    @endif
                                                    <form method="POST" action="{{ route('admin.damages.update-status', $damage) }}" class="block">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="ignored">
                                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                                            Mark as Ignored
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5l7-7 7 7M9 20h6m-7 4h7m0 0v5a2 2 0 002 2h14a2 2 0 002-2v-5M5 12a2 2 0 012-2h10a2 2 0 012 2v5a2 2 0 01-2 2H7a2 2 0 01-2-2v-5z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No damage reports</h3>
                <p class="mt-1 text-sm text-gray-500">This control doesn't have any damage reports.</p>
            </div>
        @endif
    </div>
</div>

<!-- Fixed Modal -->
<div id="fixedModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Mark Damage as Fixed</h3>
            <form id="fixedForm" method="POST">
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
    // Damage filtering functionality
    function filterDamages(filter) {
        const damageItems = document.querySelectorAll('.damage-item');
        const filterButtons = document.querySelectorAll('.damage-filter-btn');
        
        // Update button states
        filterButtons.forEach(btn => {
            btn.classList.remove('bg-blue-600', 'text-white');
            btn.classList.add('bg-gray-100', 'text-gray-700');
        });
        
        const activeButton = document.querySelector(`[data-filter="${filter}"]`);
        activeButton.classList.remove('bg-gray-100', 'text-gray-700');
        activeButton.classList.add('bg-blue-600', 'text-white');
        
        // Filter damages
        damageItems.forEach(item => {
            const status = item.dataset.status;
            const severity = item.dataset.severity;
            
            let show = false;
            
            switch(filter) {
                case 'all':
                    show = true;
                    break;
                case 'reported':
                case 'in_repair':
                case 'fixed':
                    show = status === filter;
                    break;
                case 'critical':
                    show = severity === 'critical';
                    break;
            }
            
            item.style.display = show ? 'block' : 'none';
        });
    }

    function openImagePreview(imageSrc, title, damageId) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4';
        modal.onclick = function(e) {
            if (e.target === this) {
                document.body.removeChild(this);
            }
        };
        
        modal.innerHTML = `
            <div class="relative bg-white p-6 rounded-lg max-w-4xl max-h-full overflow-auto shadow-xl">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-900">Damage Photo</h3>
                    <button onclick="document.body.removeChild(this.closest('.fixed'))" class="text-gray-400 hover:text-gray-600 p-2 rounded-full hover:bg-gray-100 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="text-center">
                    <img src="${imageSrc}" alt="${title}" class="max-w-full max-h-96 mx-auto rounded-lg shadow-lg">
                    <p class="text-center text-sm text-gray-600 mt-4">${title}</p>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
    }

    function openFixedModal(damageId) {
        const modal = document.getElementById('fixedModal');
        const form = document.getElementById('fixedForm');
        form.action = `/admin/damages/${damageId}/fixed`;
        modal.classList.remove('hidden');
    }

    function closeFixedModal() {
        document.getElementById('fixedModal').classList.add('hidden');
    }

    // Initialize with 'all' filter active
    document.addEventListener('DOMContentLoaded', function() {
        filterDamages('all');
    });
</script>
@endsection