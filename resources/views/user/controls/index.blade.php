@extends('components.layouts.app')

@section('title', 'My Controls')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">My Controls</h1>
                <p class="text-gray-600">View and manage all your assigned vehicle controls</p>
            </div>
            <a href="{{ route('user.dashboard') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h2a2 2 0 012 2v2H8V5z"/>
                </svg>
                Dashboard
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-4">
            <div class="flex flex-wrap gap-4 items-center">
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-700">Filter:</span>
                    <select id="status-filter" class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-700">Check Status:</span>
                    <select id="check-filter" class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All</option>
                        <option value="start-pending">START Pending</option>
                        <option value="exit-pending">EXIT Pending</option>
                        <option value="both-completed">Both Completed</option>
                    </select>
                </div>
                
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-700">Date:</span>
                    <input type="date" id="date-filter" class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>
    </div>

    <!-- Controls List -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Controls List</h3>
        </div>
        
        <div class="overflow-hidden">
            @if($controls->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Control</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tasks</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($controls as $control)
                                <tr class="hover:bg-gray-50" data-status="{{ $control->status }}" 
                                    data-check-status="@if(!$control->start_check_at)start-pending@elseif(!$control->exit_check_at)exit-pending@else both-completed @endif"
                                    data-date="{{ $control->assigned_at->format('Y-m-d') }}">
                                    <!-- Control Info -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white font-bold">
                                                {{ $control->id }}
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">Control #{{ $control->id }}</div>
                                                <div class="text-sm text-gray-500">
                                                    @if($control->status === 'completed')
                                                        Completed {{ $control->updated_at->diffForHumans() }}
                                                    @else
                                                        Active
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Vehicle Info -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $control->truck->license_plate }}</div>
                                        <div class="text-sm text-gray-500">{{ $control->truck->make }} {{ $control->truck->model }}</div>
                                    </td>

                                    <!-- Assignment Date -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $control->assigned_at->format('M d, Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $control->assigned_at->format('H:i') }}</div>
                                    </td>

                                    <!-- Tasks -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $startCompletions = $control->completions->where('check_type', 'start')->count();
                                            $exitCompletions = $control->completions->where('check_type', 'exit')->count();
                                            $totalTasks = $control->tasks->count();
                                        @endphp
                                        <div class="text-sm text-gray-900">{{ $totalTasks }} tasks</div>
                                        <div class="text-sm text-gray-500">
                                            START: {{ $startCompletions }}/{{ $totalTasks }} | 
                                            EXIT: {{ $exitCompletions }}/{{ $totalTasks }}
                                        </div>
                                    </td>

                                    <!-- Status -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($control->status === 'active')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Active
                                            </span>
                                        @elseif($control->status === 'completed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ ucfirst($control->status) }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Progress -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col space-y-1">
                                            <!-- START Check -->
                                            <div class="flex items-center">
                                                @if($control->start_check_at)
                                                    <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="text-xs text-green-600">START ✓</span>
                                                @else
                                                    <svg class="w-4 h-4 text-orange-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L10 9.586V6z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="text-xs text-orange-600">START</span>
                                                @endif
                                            </div>
                                            
                                            <!-- EXIT Check -->
                                            <div class="flex items-center">
                                                @if($control->exit_check_at)
                                                    <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="text-xs text-green-600">EXIT ✓</span>
                                                @else
                                                    <svg class="w-4 h-4 text-orange-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L10 9.586V6z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="text-xs text-orange-600">EXIT</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            @if($control->status === 'active')
                                                @if(!$control->start_check_at)
                                                    <a href="{{ route('user.controls.start', $control) }}" 
                                                       class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs rounded-md transition duration-150 ease-in-out">
                                                        START
                                                    </a>
                                                @elseif(!$control->exit_check_at)
                                                    <a href="{{ route('user.controls.exit', $control) }}" 
                                                       class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded-md transition duration-150 ease-in-out">
                                                        EXIT
                                                    </a>
                                                @endif
                                            @endif
                                            
                                            <a href="{{ route('user.controls.show', $control) }}" 
                                               class="inline-flex items-center px-3 py-1 border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs rounded-md transition duration-150 ease-in-out">
                                                View
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $controls->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No controls found</h3>
                    <p class="text-gray-500">You don't have any controls assigned yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('status-filter');
    const checkFilter = document.getElementById('check-filter');
    const dateFilter = document.getElementById('date-filter');
    const rows = document.querySelectorAll('tbody tr');

    function applyFilters() {
        const statusValue = statusFilter.value.toLowerCase();
        const checkValue = checkFilter.value.toLowerCase();
        const dateValue = dateFilter.value;

        rows.forEach(row => {
            let show = true;

            // Status filter
            if (statusValue && row.dataset.status !== statusValue) {
                show = false;
            }

            // Check status filter
            if (checkValue && row.dataset.checkStatus !== checkValue) {
                show = false;
            }

            // Date filter
            if (dateValue && row.dataset.date !== dateValue) {
                show = false;
            }

            row.style.display = show ? '' : 'none';
        });
    }

    statusFilter.addEventListener('change', applyFilters);
    checkFilter.addEventListener('change', applyFilters);
    dateFilter.addEventListener('change', applyFilters);
});
</script>
@endsection