@extends('components.layouts.app')

@section('title', 'Truck Numbers')

@section('content')
<div class="p-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Truck Numbers Management</h3>
                <p class="text-sm text-gray-600">Manage the list of available truck numbers</p>
            </div>
            <div class="flex space-x-3">

                <a href="{{ route('admin.truck-numbers.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Truck Number
                </a>
            </div>
        </div>

        <!-- Search and Stats -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div class="flex-1 max-w-md">
                    <form method="GET" class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Search truck numbers..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        @if(request('search'))
                            <button type="button" 
                                    onclick="window.location.href='{{ route('admin.truck-numbers.index') }}'"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        @endif
                    </form>
                </div>
                <div class="text-sm text-gray-600">
                    Total: {{ $truckNumbers->total() }} truck numbers
                    @if(request('search'))
                        | Showing results for "{{ request('search') }}"
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div id="bulk-actions" class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 hidden">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <span class="text-sm font-medium text-blue-900" id="selected-count">0 items selected</span>
            </div>
            <div class="flex space-x-2">
                <button onclick="clearSelection()" 
                        class="px-3 py-1 text-sm text-blue-700 hover:text-blue-900">
                    Clear Selection
                </button>
                <button onclick="confirmBulkDelete()" 
                        class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm rounded transition-colors">
                    Delete Selected
                </button>
            </div>
        </div>
    </div>

    <!-- Truck Numbers Table -->
    <div class="bg-white rounded-lg shadow">
        @if($truckNumbers->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" 
                                       id="select-all"
                                       onchange="toggleAllSelection()"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Truck Number
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($truckNumbers as $truckNumber)
                            <tr class="hover:bg-gray-50 truck-number-row">
                                <td class="px-6 py-4">
                                    <input type="checkbox" 
                                           class="truck-number-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                           value="{{ $truckNumber->id }}"
                                           onchange="updateBulkActions()">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $truckNumber->truck_number }}</div>
                                    <div class="text-xs text-gray-500">ID: {{ $truckNumber->id }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <div>{{ $truckNumber->created_at->format('M j, Y') }}</div>
                                    <div class="text-xs">{{ $truckNumber->created_at->format('g:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.truck-numbers.edit', $truckNumber) }}" 
                                           class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <button onclick="confirmDelete({{ $truckNumber->id }}, '{{ $truckNumber->truck_number }}')" 
                                                class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($truckNumbers->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $truckNumbers->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No truck numbers found</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if(request('search'))
                        No truck numbers match your search criteria.
                    @else
                        Get started by adding your first truck number.
                    @endif
                </p>
                @if(!request('search'))
                    <div class="mt-6">
                        <a href="{{ route('admin.truck-numbers.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add First Truck Number
                        </a>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>



<!-- Delete Form -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Bulk Delete Form -->
<form id="bulk-delete-form" method="POST" action="{{ route('admin.truck-numbers.bulk-delete') }}" style="display: none;">
    @csrf
    <div id="bulk-delete-inputs"></div>
</form>

<script>


// Selection Management
function toggleAllSelection() {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.truck-number-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.truck-number-checkbox:checked');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    
    if (checkboxes.length > 0) {
        bulkActions.classList.remove('hidden');
        selectedCount.textContent = `${checkboxes.length} item${checkboxes.length > 1 ? 's' : ''} selected`;
    } else {
        bulkActions.classList.add('hidden');
    }
    
    // Update select all checkbox state
    const allCheckboxes = document.querySelectorAll('.truck-number-checkbox');
    const selectAll = document.getElementById('select-all');
    
    if (checkboxes.length === 0) {
        selectAll.checked = false;
        selectAll.indeterminate = false;
    } else if (checkboxes.length === allCheckboxes.length) {
        selectAll.checked = true;
        selectAll.indeterminate = false;
    } else {
        selectAll.checked = false;
        selectAll.indeterminate = true;
    }
}

function clearSelection() {
    const checkboxes = document.querySelectorAll('.truck-number-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    updateBulkActions();
}

// Delete Functions
function confirmDelete(id, truckNumber) {
    if (confirm(`Are you sure you want to delete truck number "${truckNumber}"?`)) {
        const form = document.getElementById('delete-form');
        form.action = `/admin/truck-numbers/${id}`;
        form.submit();
    }
}

function confirmBulkDelete() {
    const checkboxes = document.querySelectorAll('.truck-number-checkbox:checked');
    
    if (checkboxes.length === 0) {
        alert('Please select at least one truck number to delete.');
        return;
    }
    
    if (confirm(`Are you sure you want to delete ${checkboxes.length} truck number${checkboxes.length > 1 ? 's' : ''}?`)) {
        const form = document.getElementById('bulk-delete-form');
        const inputsContainer = document.getElementById('bulk-delete-inputs');
        
        // Clear previous inputs
        inputsContainer.innerHTML = '';
        
        // Add selected IDs
        checkboxes.forEach(checkbox => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'truck_number_ids[]';
            input.value = checkbox.value;
            inputsContainer.appendChild(input);
        });
        
        form.submit();
    }
}

// Auto-submit search form on input (with debounce)
let searchTimeout;
const searchInput = document.querySelector('input[name="search"]');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            this.form.submit();
        }, 500);
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateBulkActions();
});
</script>
@endsection