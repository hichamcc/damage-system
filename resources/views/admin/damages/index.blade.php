@extends('components.layouts.app')

@section('title', 'All Damage Reports')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">All Damage Reports</h1>
                <p class="text-gray-600">Manage damage reports across all vehicle controls</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.control.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Controls
                </a>
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h2a2 2 0 012 2v2H8V5z"/>
                    </svg>
                    Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-gray-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Reports</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Open Reports</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['reported'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" clip-rule="evenodd"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">In Repair</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['in_repair'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Fixed</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['fixed'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-4">
            <div class="flex flex-wrap gap-4 items-center">
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-700">Status:</span>
                    <select id="status-filter" class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="reported">Reported</option>
                        <option value="in_repair">In Repair</option>
                        <option value="fixed">Fixed</option>
                    </select>
                </div>
                
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-700">Severity:</span>
                    <select id="severity-filter" class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Severity</option>
                        <option value="minor">Minor</option>
                        <option value="major">Major</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
                
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-700">Vehicle:</span>
                    <input type="text" id="vehicle-filter" placeholder="License plate..." 
                           class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-700">Search:</span>
                    <input type="text" id="search-input" placeholder="Search description..." 
                           class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>
    </div>

    <!-- Damages Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Damage Reports</h3>
        </div>
        
        <div class="overflow-hidden">
            @if($damages->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Damage</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Control</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reporter</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Severity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($damages as $damage)
                                <tr class="hover:bg-gray-50 damage-row" 
                                    data-status="{{ $damage->status }}" 
                                    data-severity="{{ $damage->severity }}"
                                    data-vehicle="{{ strtolower($damage->controlLine->truck->license_plate) }}"
                                    data-search="{{ strtolower($damage->damage_location . ' ' . $damage->damage_description) }}">
                                    
                                    <!-- Damage Info -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 mr-3">
                                                @if($damage->severity === 'critical')
                                                    <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </div>
                                                @elseif($damage->severity === 'major')
                                                    <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </div>
                                                @else
                                                    <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $damage->damage_location }}</div>
                                                <div class="text-sm text-gray-500">{{ Str::limit($damage->damage_description, 50) }}</div>
                                                @if($damage->damage_area)
                                                    <div class="text-xs text-orange-600 mt-1 font-medium">
                                                        📍 {{ $damage->damage_area_display }}
                                                    </div>
                                                @endif
                                                @if($damage->controlTask)
                                                    <div class="text-xs text-gray-400 mt-1">Task: {{ $damage->controlTask->title }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Vehicle -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $damage->controlLine->truck->license_plate }}</div>
                                        <div class="text-sm text-gray-500">{{ $damage->controlLine->truck->make }} {{ $damage->controlLine->truck->model }}</div>
                                    </td>

                                    <!-- Control -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('admin.control.show', $damage->controlLine) }}" 
                                           class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                            Control #{{ $damage->controlLine->id }}
                                        </a>
                                    </td>

                                    <!-- Reporter -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $damage->reportedBy->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $damage->reportedBy->email }}</div>
                                    </td>

                                    <!-- Severity -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($damage->severity === 'critical') bg-red-100 text-red-800
                                            @elseif($damage->severity === 'major') bg-orange-100 text-orange-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ ucfirst($damage->severity) }}
                                        </span>
                                    </td>

                                    <!-- Status -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($damage->status === 'reported') bg-red-100 text-red-800
                                            @elseif($damage->status === 'in_repair') bg-yellow-100 text-yellow-800
                                            @elseif($damage->status === 'fixed') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $damage->status)) }}
                                        </span>
                                    </td>

                                    <!-- Date -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $damage->created_at->format('M d, Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $damage->created_at->format('H:i') }}</div>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.control.damages', $damage->controlLine) }}" 
                                               class="text-blue-600 hover:text-blue-500">View</a>
                                            
                                            @if($damage->status !== 'fixed')
                                                <button type="button" onclick="openMarkFixedModal({{ $damage->id }})" 
                                                        class="text-green-600 hover:text-green-500">Fix</button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $damages->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No damage reports found</h3>
                    <p class="text-gray-500">There are no damage reports in the system yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Mark as Fixed Modal (reuse from damage reports view) -->
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
    const vehicleFilter = document.getElementById('vehicle-filter');
    const searchInput = document.getElementById('search-input');
    const damageRows = document.querySelectorAll('.damage-row');

    function applyFilters() {
        const statusValue = statusFilter.value.toLowerCase();
        const severityValue = severityFilter.value.toLowerCase();
        const vehicleValue = vehicleFilter.value.toLowerCase();
        const searchValue = searchInput.value.toLowerCase();

        damageRows.forEach(row => {
            let show = true;

            // Status filter
            if (statusValue && row.dataset.status !== statusValue) {
                show = false;
            }

            // Severity filter
            if (severityValue && row.dataset.severity !== severityValue) {
                show = false;
            }

            // Vehicle filter
            if (vehicleValue && !row.dataset.vehicle.includes(vehicleValue)) {
                show = false;
            }

            // Search filter
            if (searchValue && !row.dataset.search.includes(searchValue)) {
                show = false;
            }

            row.style.display = show ? '' : 'none';
        });
    }

    statusFilter.addEventListener('change', applyFilters);
    severityFilter.addEventListener('change', applyFilters);
    vehicleFilter.addEventListener('input', applyFilters);
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