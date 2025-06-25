@extends('components.layouts.app')

@section('title', 'Control Lines')

@section('content')
<div class="p-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-medium text-gray-900">User Control Lines</h3>
                <p class="text-sm text-gray-600">View and manage control checks created by users</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.control-templates.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-blue-300 hover:bg-blue-50 text-blue-700 text-sm font-medium rounded-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Manage Templates
                </a>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['total_controls'] }}</div>
                    <div class="text-sm text-gray-600">Total Controls</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600">{{ $stats['active_controls'] }}</div>
                    <div class="text-sm text-gray-600">Active Controls</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['completed_today'] }}</div>
                    <div class="text-sm text-gray-600">Completed Today</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600">{{ $stats['pending_damages'] }}</div>
                    <div class="text-sm text-gray-600">Pending Damages</div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Truck</label>
                    <select name="truck_id" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option value="">All Trucks</option>
                        @foreach($trucks as $truck)
                            <option value="{{ $truck->id }}" {{ request('truck_id') == $truck->id ? 'selected' : '' }}>
                                {{ $truck->license_plate }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">User</label>
                    <select name="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Controls Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h4 class="text-md font-medium text-gray-900">Control Lines ({{ $controlLines->total() }})</h4>
        </div>

        @if($controlLines->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Control Info</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Checks Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issues</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($controlLines as $control)
                            @php
                                $totalTasks = $control->tasks->count();
                                $completedTasks = $control->tasks()->whereHas('completions')->count();
                                $startChecks = $control->tasks()->whereHas('completions', function($q) { $q->where('check_type', 'start'); })->count();
                                $exitChecks = $control->tasks()->whereHas('completions', function($q) { $q->where('check_type', 'exit'); })->count();
                                $tasksWithIssues = $control->tasks()->whereHas('completions', function($q) { $q->whereIn('status', ['issue', 'missing', 'damaged']); })->count();
                                $progressPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <!-- Control Info -->
                                <td class="px-6 py-4">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            @if($control->status === 'completed')
                                                <span class="inline-flex items-center justify-center w-8 h-8 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center justify-center w-8 h-8 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                                                    {{ $progressPercentage }}%
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-medium text-gray-900">{{ $control->truck->license_plate }}</div>
                                            <div class="text-sm text-gray-500">{{ $control->truck->make }} {{ $control->truck->model }}</div>
                                            <div class="text-xs text-gray-400 mt-1">
                                                User: {{ $control->assignedUser->name }}
                                            </div>
                                            @if($control->controlTemplate)
                                                <div class="text-xs text-blue-600 mt-1">
                                                    Template: {{ $control->controlTemplate->name }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Progress -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $completedTasks }}/{{ $totalTasks }} tasks</div>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $progressPercentage }}%"></div>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $progressPercentage }}% complete</div>
                                </td>

                                <!-- Checks Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        <div class="flex items-center text-xs">
                                            @if($startChecks === $totalTasks && $totalTasks > 0)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Start ✓
                                                </span>
                                            @elseif($startChecks > 0)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-yellow-100 text-yellow-800">
                                                    Start {{ $startChecks }}/{{ $totalTasks }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-gray-100 text-gray-800">
                                                    Start Pending
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center text-xs">
                                            @if($exitChecks === $totalTasks && $totalTasks > 0)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Exit ✓
                                                </span>
                                            @elseif($exitChecks > 0)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-orange-100 text-orange-800">
                                                    Exit {{ $exitChecks }}/{{ $totalTasks }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-gray-100 text-gray-800">
                                                    Exit Pending
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Issues -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($tasksWithIssues > 0)
                                        <div class="flex items-center text-sm">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-red-100 text-red-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ $tasksWithIssues }} issues
                                            </span>
                                        </div>
                                        @if($control->damageReports->count() > 0)
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $control->damageReports->count() }} damage reports
                                            </div>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-800 text-xs">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            No issues
                                        </span>
                                    @endif
                                </td>

                                <!-- Created -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div>{{ $control->created_at->format('M j, Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $control->created_at->format('g:i A') }}</div>
                                    @if($control->completed_at)
                                        <div class="text-xs text-green-600 mt-1">
                                            Completed: {{ $control->completed_at->format('M j, g:i A') }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.control.show', $control) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        @if($startChecks > 0 && $exitChecks > 0 && $startChecks === $totalTasks && $exitChecks === $totalTasks)
                                            <a href="{{ route('admin.control.compare', $control) }}" 
                                               class="text-purple-600 hover:text-purple-900" title="Compare Start vs Exit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                                </svg>
                                            </a>
                                        @endif
                                        @if($control->damageReports->count() > 0)
                                            <a href="{{ route('admin.control.damages', $control) }}" 
                                               class="text-red-600 hover:text-red-900" title="View Damage Reports">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($controlLines->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $controlLines->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5l7-7 7 7M9 20h6m-7 4h7m0 0v5a2 2 0 002 2h14a2 2 0 002-2v-5M5 12a2 2 0 012-2h10a2 2 0 012 2v5a2 2 0 01-2 2H7a2 2 0 01-2-2v-5z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No control lines found</h3>
                <p class="mt-1 text-sm text-gray-500">No control lines match your current filters.</p>
            </div>
        @endif
    </div>
</div>
@endsection