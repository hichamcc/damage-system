@extends('components.layouts.app')

@section('title', 'Control Dashboard')

@section('content')
<div class="p-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">Control Dashboard</h3>
                    <p class="text-sm text-gray-600 mt-1">Create and manage your vehicle control checks</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        {{ now()->format('M j, Y') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Stats Dashboard -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Controls -->
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-2xl font-bold text-blue-600">{{ $stats['total_controls'] }}</div>
                            <div class="text-sm text-blue-800">Total Controls</div>
                        </div>
                    </div>
                </div>

                <!-- Active Controls -->
                <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-2xl font-bold text-yellow-600">{{ $stats['active_controls'] }}</div>
                            <div class="text-sm text-yellow-800">Active Controls</div>
                        </div>
                    </div>
                </div>

                <!-- Completed Today -->
                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-2xl font-bold text-green-600">{{ $stats['completed_today'] }}</div>
                            <div class="text-sm text-green-800">Completed Today</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h4 class="text-lg font-medium text-gray-900">Quick Actions</h4>
        </div>
        <div class="p-6">
            <div class="flex flex-col sm:flex-row gap-4">
                @if($activeTemplate)
                    <button type="button" 
                            onclick="openCreateModal()"
                            class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Create New Control
                    </button>
                    
                    <!-- Template Info Badge -->
                    <div class="flex items-center space-x-3 px-4 py-2 bg-blue-50 rounded-md border border-blue-200">
                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div>
                            <div class="text-sm font-medium text-blue-900">{{ $activeTemplate->name }}</div>
                            <div class="text-xs text-blue-700">{{ $activeTemplate->tasks->count() }} tasks ({{ $activeTemplate->tasks->where('is_required', true)->count() }} required)</div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center space-x-3 px-4 py-3 bg-yellow-50 rounded-md border border-yellow-200">
                        <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <div>
                            <div class="text-sm font-medium text-yellow-900">No Active Template</div>
                            <div class="text-xs text-yellow-700">Contact administrator to activate a control template</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Create Control Modal -->
    @if($activeTemplate)
        <div id="createControlModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <!-- Modal Header -->
                <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Create New Control</h3>
                    <button type="button" onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="mt-4">
                    <!-- Active Template Info -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6 border border-blue-200">
                        <div class="flex items-start">
                            <svg class="h-6 w-6 text-blue-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <div>
                                <h5 class="font-medium text-blue-900">{{ $activeTemplate->name }}</h5>
                                <p class="text-sm text-blue-800 mt-1">{{ $activeTemplate->description }}</p>
                                <div class="flex items-center mt-2 space-x-4">
                                    <span class="text-sm text-blue-700">{{ $activeTemplate->tasks->count() }} tasks</span>
                                    <span class="text-sm text-blue-700">{{ $activeTemplate->tasks->where('is_required', true)->count() }} required</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Create Control Form -->
                    <form action="{{ route('user.control.store') }}" method="POST" id="createControlForm">
                        @csrf
                        
                        <div class="space-y-6">
                            <!-- Select Truck -->
                            <div>
                                <label for="modal_truck_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Select Truck <span class="text-red-500">*</span>
                                </label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                        id="modal_truck_id" 
                                        name="truck_id" 
                                        required>
                                    <option value="">Choose a truck...</option>
                                    @foreach($trucks as $truck)
                                        <option value="{{ $truck->id }}">
                                            {{ $truck->license_plate }} - {{ $truck->make }} {{ $truck->model }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Check Type -->
                        
                            <!-- Notes -->
                            <div>
                                <label for="modal_notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                          id="modal_notes" 
                                          name="notes" 
                                          rows="3"
                                          placeholder="Add any additional notes for this control..."></textarea>
                            </div>
                        </div>

                        <!-- Modal Actions -->
                        <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                            <button type="button" 
                                    onclick="closeCreateModal()"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Create Control
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Recent Controls -->
    @if($userControls->count() > 0)
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h4 class="text-lg font-medium text-gray-900">Your Recent Controls</h4>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Truck</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Template</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($userControls as $control)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $control->truck->license_plate }}</div>
                                            <div class="text-sm text-gray-500">{{ $control->truck->make }} {{ $control->truck->model }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $control->controlTemplate->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $control->tasks->count() }} tasks</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($control->status === 'active')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            Active
                                        </span>
                                    @elseif($control->status === 'completed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Completed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ ucfirst($control->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div>{{ $control->created_at->format('M j, Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $control->created_at->format('g:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('user.control.show', $control) }}" 
                                           class="text-blue-600 hover:text-blue-900">View</a>
                                        
                                        @if($control->status === 'active')
                                            @php
                                                $hasStartCheck = $control->tasks()
                                                    ->whereHas('completions', function($query) {
                                                        $query->where('check_type', 'start');
                                                    })->exists();
                                                
                                                $hasExitCheck = $control->tasks()
                                                    ->whereHas('completions', function($query) {
                                                        $query->where('check_type', 'exit');
                                                    })->exists();
                                            @endphp

                                            @if(!$hasStartCheck)
                                                <a href="{{ route('user.control.start', $control) }}" 
                                                   class="text-green-600 hover:text-green-900">Start Check</a>
                                            @elseif($hasStartCheck && !$hasExitCheck)
                                                <span class="text-gray-400">Start ✓</span>
                                                <a href="{{ route('user.control.exit', $control) }}" 
                                                   class="text-orange-600 hover:text-orange-900">Exit Check</a>
                                            @elseif(!$hasExitCheck)
                                                <a href="{{ route('user.control.exit', $control) }}" 
                                                   class="text-orange-600 hover:text-orange-900">Exit Check</a>
                                            @else
                                                <span class="text-green-600">All Checks Complete</span>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($userControls->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $userControls->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5l7-7 7 7M9 20h6m-7 4h7m0 0v5a2 2 0 002 2h14a2 2 0 002-2v-5M5 12a2 2 0 012-2h10a2 2 0 012 2v5a2 2 0 01-2 2H7a2 2 0 01-2-2v-5z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No controls yet</h3>
                <p class="mt-1 text-sm text-gray-500">You haven't created any control checks yet. Create your first one above!</p>
            </div>
        </div>
    @endif
</div>
@endsection

<script>
    // Modal functions
    function openCreateModal() {
        document.getElementById('createControlModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeCreateModal() {
        document.getElementById('createControlModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        // Reset form
        document.getElementById('createControlForm').reset();
    }

    // Close modal when clicking outside
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('createControlModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeCreateModal();
                }
            });
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeCreateModal();
            }
        });

        // Form submission handling
        document.getElementById('createControlForm').addEventListener('submit', function(e) {
            const truckId = document.getElementById('modal_truck_id').value;
            //const checkType = document.querySelector('input[name="check_type"]:checked');
            
            if (!truckId) {
                e.preventDefault();
                alert('Please select a truck.');
                return false;
            }
            

        });
    });
</script>