@extends('components.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="p-6">
    <!-- Welcome Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-600">Manage your truck control activities and inspections</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Controls -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $userStats['total_controls'] }}</div>
                    <div class="text-sm text-gray-600">Total Controls</div>
                </div>
            </div>
        </div>

        <!-- Active Controls -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $userStats['active_controls'] }}</div>
                    <div class="text-sm text-gray-600">Active Controls</div>
                    @if($userStats['active_controls'] > 0)
                        <div class="text-xs text-yellow-600 mt-1">Need your attention</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Completed Controls -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $userStats['completed_controls'] }}</div>
                    <div class="text-sm text-gray-600">Completed</div>
                    @if($userStats['total_controls'] > 0)
                        <div class="text-xs text-green-600 mt-1">
                            {{ round(($userStats['completed_controls'] / $userStats['total_controls']) * 100) }}% completion rate
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pending Damages -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $userStats['pending_damages'] }}</div>
                    <div class="text-sm text-gray-600">Pending Damages</div>
                    @if($userStats['pending_damages'] > 0)
                        <div class="text-xs text-red-600 mt-1">Awaiting repair</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Active Controls -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">My Control Activities</h3>
                <span class="text-sm text-gray-500">{{ $userControls->count() }} recent controls</span>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($userControls as $control)
                    @php
                        $totalTasks = $control->tasks->count();
                        $completedTasks = $control->tasks->filter(function($task) {
                            return $task->completions->count() > 0;
                        })->count();
                        $progressPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                        
                        $hasStartCheck = $control->tasks()
                            ->whereHas('completions', function($query) {
                                $query->where('check_type', 'start');
                            })->exists();
                        
                        $hasExitCheck = $control->tasks()
                            ->whereHas('completions', function($query) {
                                $query->where('check_type', 'exit');
                            })->exists();
                    @endphp
                    <div class="p-6 hover:bg-gray-50">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                @if($control->status === 'completed')
                                    <span class="inline-flex items-center justify-center w-12 h-12 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center w-12 h-12 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                                        {{ $progressPercentage }}%
                                    </span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-medium text-gray-900">
                                            {{ $control->truck->license_plate }}
                                        </h4>
                                        <p class="text-sm text-gray-600">
                                            {{ $control->truck->make }} {{ $control->truck->model }} - {{ $control->controlTemplate->name }}
                                        </p>
                                        <div class="mt-2">
                                            <div class="flex items-center text-xs text-gray-500">
                                                <span>Progress: {{ $completedTasks }}/{{ $totalTasks }} tasks</span>
                                                <span class="mx-2">•</span>
                                                <span>{{ $control->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $progressPercentage }}%"></div>
                                            </div>
                                        </div>

                                        <!-- Check Status -->
                                        <div class="flex items-center space-x-2 mt-3">
                                            @if($hasStartCheck)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Start ✓
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                    Start Pending
                                                </span>
                                            @endif

                                            @if($hasExitCheck)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Exit ✓
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                    Exit Pending
                                                </span>
                                            @endif
                                        </div>

                                        @if($control->damageReports->count() > 0)
                                            <div class="mt-2">
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ $control->damageReports->count() }} damage(s) reported
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex flex-col space-y-2 ml-4">
                                        @if($control->status === 'active')
                                            @if(!$hasStartCheck)
                                                <a href="{{ route('user.control.start', $control) }}" 
                                                   class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md transition duration-150 ease-in-out">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293H15M9 10V9a3 3 0 013-3v.5a.5.5 0 01-.5.5H11V7a1 1 0 011-1h2"/>
                                                    </svg>
                                                    Start Check
                                                </a>
                                            @elseif($hasStartCheck && !$hasExitCheck)
                                                <a href="{{ route('user.control.exit', $control) }}" 
                                                   class="inline-flex items-center px-3 py-2 bg-orange-600 hover:bg-orange-700 text-white text-xs font-medium rounded-md transition duration-150 ease-in-out">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                                    </svg>
                                                    Exit Check
                                                </a>
                                            @endif
                                        @endif
                                        
                                        <a href="{{ route('user.control.show', $control) }}" 
                                           class="inline-flex items-center px-3 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-medium rounded-md transition duration-150 ease-in-out">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5l7-7 7 7M9 20h6m-7 4h7m0 0v5a2 2 0 002 2h14a2 2 0 002-2v-5M5 12a2 2 0 012-2h10a2 2 0 012 2v5a2 2 0 01-2 2H7a2 2 0 01-2-2v-5z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No controls yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating your first control.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="space-y-6">
            <!-- Create New Control -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Create New Control</h3>
                </div>
                <div class="p-6">
                    @if($activeTemplate)
                        <form method="POST" action="{{ route('user.control.store') }}" id="createControlForm">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select Truck</label>
                                <select name="truck_id" required 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Choose a truck...</option>
                                    @foreach($availableTrucks as $truck)
                                        <option value="{{ $truck->id }}">
                                            {{ $truck->license_plate }} - {{ $truck->make }} {{ $truck->model }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Template</label>
                                <div class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                                    <div class="text-sm font-medium text-blue-900">{{ $activeTemplate->name }}</div>
                                    @if($activeTemplate->description)
                                        <div class="text-xs text-blue-700 mt-1">{{ $activeTemplate->description }}</div>
                                    @endif
                                    <div class="text-xs text-blue-600 mt-2">
                                        {{ $activeTemplate->tasks->count() }} inspection tasks
                                    </div>
                                </div>
                                <input type="hidden" name="control_template_id" value="{{ $activeTemplate->id }}">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                                <textarea name="notes" rows="3" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Additional notes or instructions..."></textarea>
                            </div>
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Create Control
                            </button>
                        </form>
                    @else
                        <div class="text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5l7-7 7 7M9 20h6m-7 4h7m0 0v5a2 2 0 002 2h14a2 2 0 002-2v-5M5 12a2 2 0 012-2h10a2 2 0 012 2v5a2 2 0 01-2 2H7a2 2 0 01-2-2v-5z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No active template</h3>
                            <p class="mt-1 text-sm text-gray-500">Contact your administrator to activate a control template.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Available Trucks -->
            @if($availableTrucks->count() > 0)
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Available Trucks</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($availableTrucks as $truck)
                            <div class="p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2v-2a2 2 0 00-2-2H8V7z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $truck->license_plate }}</div>
                                        <div class="text-xs text-gray-500">{{ $truck->make }} {{ $truck->model }} ({{ $truck->year }})</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif


        </div>
    </div>

    <!-- All Controls Link -->
    <div class="text-center">
        <a href="{{ route('user.control.index') }}" 
           class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            View All My Controls
        </a>
    </div>
</div>

<script>
    // Form validation for creating new control
    document.getElementById('createControlForm')?.addEventListener('submit', function(e) {
        const truckSelect = this.querySelector('select[name="truck_id"]');
        if (!truckSelect.value) {
            e.preventDefault();
            alert('Please select a truck before creating the control.');
            truckSelect.focus();
        }
    });

    // Auto-refresh for real-time updates (optional)
    // setInterval(function() {
    //     // You could add AJAX calls here to update stats in real-time
    // }, 60000); // Every minute
</script>
@endsection