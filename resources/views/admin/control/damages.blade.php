@extends('components.layouts.app')

@section('title', 'Damage Reports')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-medium text-gray-900">
                    Damage Reports - Control #{{ $controlLine->id }}
                </h3>
                <p class="text-sm text-gray-500">
                    {{ $controlLine->truck->license_plate }} - {{ $controlLine->truck->make }} {{ $controlLine->truck->model }}
                </p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.control.show', $controlLine) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
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
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ $damages->count() }}</div>
                    <div class="text-sm text-gray-500">Total Reports</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600">{{ $damages->where('status', 'reported')->count() }}</div>
                    <div class="text-sm text-gray-500">Open</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600">{{ $damages->where('status', 'in_repair')->count() }}</div>
                    <div class="text-sm text-gray-500">In Repair</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $damages->where('status', 'fixed')->count() }}</div>
                    <div class="text-sm text-gray-500">Fixed</div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-wrap gap-4 items-center">
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-700">Filter by Status:</span>
                    <select id="status-filter" class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="reported">Reported</option>
                        <option value="in_repair">In Repair</option>
                        <option value="fixed">Fixed</option>
                    </select>
                </div>
                
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-700">Filter by Severity:</span>
                    <select id="severity-filter" class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Severity</option>
                        <option value="minor">Minor</option>
                        <option value="major">Major</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
                
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-700">Search:</span>
                    <input type="text" id="search-input" placeholder="Search location or description..." 
                           class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- Damage Reports List -->
        <div class="p-6">
            @if($damages->count() > 0)
                <div class="space-y-6" id="damages-container">
                    @foreach($damages as $damage)
                        <div class="damage-card border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow duration-200" 
                             data-status="{{ $damage->status }}" 
                             data-severity="{{ $damage->severity }}"
                             data-search="{{ strtolower($damage->damage_location . ' ' . $damage->damage_description) }}">
                            
                            <!-- Header -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        @if($damage->severity === 'critical')
                                            <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        @elseif($damage->severity === 'major')
                                            <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-1">
                                        <h4 class="text-lg font-medium text-gray-900">{{ $damage->damage_location }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">{{ $damage->damage_description }}</p>
                                        
                                        <!-- Damage Area Display -->
                                        @if($damage->damage_area)
                                            <div class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ $damage->damage_area_display }}
                                            </div>
                                        @endif
                                        
                                        <div class="flex items-center space-x-4 mt-2">
                                            <span class="text-xs text-gray-500">
                                                Reported by: {{ $damage->reportedBy->name }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                {{ $damage->created_at->format('M d, Y H:i') }}
                                            </span>
                                            @if($damage->controlTask)
                                                <span class="text-xs text-gray-500">
                                                    Task: {{ $damage->controlTask->title }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    <!-- Severity Badge -->
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($damage->severity === 'critical') bg-red-100 text-red-800
                                        @elseif($damage->severity === 'major') bg-orange-100 text-orange-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ ucfirst($damage->severity) }}
                                    </span>
                                    
                                    <!-- Status Badge -->
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($damage->status === 'reported') bg-red-100 text-red-800
                                        @elseif($damage->status === 'in_repair') bg-yellow-100 text-yellow-800
                                        @elseif($damage->status === 'fixed') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $damage->status)) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Photos -->
                            @if($damage->damage_photos && count($damage->damage_photos) > 0)
                                <div class="mb-4">
                                    <h5 class="text-sm font-medium text-gray-700 mb-2">Damage Photos:</h5>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($damage->damage_photos as $photo)
                                            <a href="{{ Storage::url($photo['path']) }}" target="_blank" 
                                               class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded hover:bg-gray-200">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ $photo['name'] ?? 'View Photo' }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Repair Information -->
                            @if($damage->status === 'fixed' && $damage->fixed_date)
                                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-md">
                                    <h5 class="text-sm font-medium text-green-800 mb-1">Repair Information:</h5>
                                    <p class="text-sm text-green-700">
                                        <strong>Fixed on:</strong> {{ \Carbon\Carbon::parse($damage->fixed_date)->format('M d, Y') }}
                                    </p>
                                    @if($damage->repair_notes)
                                        <p class="text-sm text-green-700 mt-1">
                                            <strong>Repair Notes:</strong> {{ $damage->repair_notes }}
                                        </p>
                                    @endif
                                </div>
                            @endif

                            <!-- Actions -->
                            <div class="flex justify-end space-x-2">
                                @if($damage->status !== 'fixed')
                                    <button type="button" onclick="openMarkFixedModal({{ $damage->id }})" 
                                            class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Mark as Fixed
                                    </button>
                                @endif
                                
                                @if($damage->status === 'reported')
                                    <form action="{{ route('admin.damage.update-status', $damage) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="in_repair">
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            Mark In Repair
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- No results message (hidden by default) -->
                <div id="no-results" class="text-center py-12 hidden">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0120 12a8 8 0 00-8-8A8 8 0 004 12a7.962 7.962 0 002 5.291z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No damage reports found</h3>
                    <p class="text-gray-500">No damage reports match your current filters.</p>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No damage reports</h3>
                    <p class="text-gray-500">This control line doesn't have any damage reports yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Mark as Fixed Modal -->
<div id="mark-fixed-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Mark Damage as Fixed</h3>
                <button type="button" onclick="closeMarkFixedModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form id="mark-fixed-form" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="mb-4">
                    <label for="fixed_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Fixed Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="fixed_date" name="fixed_date" required
                           value="{{ date('Y-m-d') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                
                <div class="mb-6">
                    <label for="repair_notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Repair Notes
                    </label>
                    <textarea id="repair_notes" name="repair_notes" rows="3"
                              placeholder="Describe the repair work done..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeMarkFixedModal()" 
                            class="px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                        Mark as Fixed
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('status-filter');
    const severityFilter = document.getElementById('severity-filter');
    const searchInput = document.getElementById('search-input');
    const damageCards = document.querySelectorAll('.damage-card');
    const noResults = document.getElementById('no-results');

    function applyFilters() {
        const statusValue = statusFilter.value.toLowerCase();
        const severityValue = severityFilter.value.toLowerCase();
        const searchValue = searchInput.value.toLowerCase();
        
        let visibleCount = 0;

        damageCards.forEach(card => {
            let show = true;

            // Status filter
            if (statusValue && card.dataset.status !== statusValue) {
                show = false;
            }

            // Severity filter
            if (severityValue && card.dataset.severity !== severityValue) {
                show = false;
            }

            // Search filter
            if (searchValue && !card.dataset.search.includes(searchValue)) {
                show = false;
            }

            card.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });

        // Show/hide no results message
        if (visibleCount === 0 && damageCards.length > 0) {
            noResults.classList.remove('hidden');
        } else {
            noResults.classList.add('hidden');
        }
    }

    statusFilter.addEventListener('change', applyFilters);
    severityFilter.addEventListener('change', applyFilters);
    searchInput.addEventListener('input', applyFilters);
});

function openMarkFixedModal(damageId) {
    const modal = document.getElementById('mark-fixed-modal');
    const form = document.getElementById('mark-fixed-form');
    form.action = `/admin/damage/${damageId}/mark-fixed`;
    modal.classList.remove('hidden');
}

function closeMarkFixedModal() {
    const modal = document.getElementById('mark-fixed-modal');
    modal.classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('mark-fixed-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeMarkFixedModal();
    }
});
</script>
@endsection