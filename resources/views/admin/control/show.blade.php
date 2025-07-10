@extends('components.layouts.app')

@section('title', 'Control Details - Admin')

@section('content')
<div class="p-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">Control Details - Admin View</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $controlLine->truck->license_plate }} - {{ $controlLine->truck->make }} {{ $controlLine->truck->model }}</p>
                </div>
                @if($controlLine->status === 'active')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        Active
                    </span>
                @elseif($controlLine->status === 'completed')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Completed
                    </span>
                @endif
            </div>
            <div class="flex space-x-3">
                @php
                    $hasStartCheck = $controlLine->tasks()
                        ->whereHas('completions', function($query) {
                            $query->where('check_type', 'start');
                        })->exists();
                    
                    $hasExitCheck = $controlLine->tasks()
                        ->whereHas('completions', function($query) {
                            $query->where('check_type', 'exit');
                        })->exists();
                @endphp

                @if($hasStartCheck && $hasExitCheck)
                    <a href="{{ route('admin.control.compare', $controlLine) }}" 
                       class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Compare Checks
                    </a>
                @endif

                @if($controlLine->damageReports->count() > 0)
                    <a href="{{ route('admin.control.damages', $controlLine) }}" 
                       class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        View Damages ({{ $controlLine->damageReports->count() }})
                    </a>
                @endif

                <a href="{{ route('admin.control.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Controls
                </a>
            </div>
        </div>

        <!-- Control Info -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Control Details -->
                <div class="lg:col-span-2">
                    <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Template</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $controlLine->controlTemplate->name }}</dd>
                                @if($controlLine->controlTemplate->description)
                                    <dd class="text-sm text-gray-600">{{ $controlLine->controlTemplate->description }}</dd>
                                @endif
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Assigned User</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $controlLine->assignedUser->name }}</dd>
                                <dd class="text-sm text-gray-600">{{ $controlLine->assignedUser->email }}</dd>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Created</dt>
                                <dd class="text-gray-900">{{ $controlLine->created_at->format('M j, Y \a\t g:i A') }}</dd>
                                <dd class="text-xs text-gray-500">by {{ $controlLine->createdBy->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Assigned Date</dt>
                                <dd class="text-gray-900">{{ $controlLine->assigned_at->format('M j, Y \a\t g:i A') }}</dd>
                            </div>
                            @if($controlLine->completed_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Completed</dt>
                                    <dd class="text-gray-900">{{ $controlLine->completed_at->format('M j, Y \a\t g:i A') }}</dd>
                                    <dd class="text-xs text-green-600">
                                        Duration: {{ $controlLine->assigned_at->diffForHumans($controlLine->completed_at, true) }}
                                    </dd>
                                </div>
                            @endif
                        </div>

                        @if($controlLine->notes)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Notes</dt>
                                <dd class="text-gray-900">{{ $controlLine->notes }}</dd>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Progress Stats -->
                <div>
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Admin Overview</h4>
                    <div class="space-y-4">
                        <!-- Total Tasks -->
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <div class="text-xl font-bold text-blue-600">{{ $controlLine->tasks->count() }}</div>
                                    <div class="text-sm text-blue-800">Total Tasks</div>
                                </div>
                            </div>
                        </div>

                        <!-- Completed Tasks -->
                        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <div class="text-xl font-bold text-green-600">{{ $controlLine->tasks->filter(function($task) {
                                        return $task->completions->count() > 0;
                                    })->count() }}</div>
                                    <div class="text-sm text-green-800">Tasks Completed</div>
                                </div>
                            </div>
                        </div>

                        <!-- Tasks with Issues -->
                        @php
                            $tasksWithIssues = $controlLine->tasks()->whereHas('completions', function($query) {
                                $query->whereIn('status', ['issue', 'missing', 'damaged']);
                            })->count();
                        @endphp
                        <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <div class="text-xl font-bold text-red-600">{{ $tasksWithIssues }}</div>
                                    <div class="text-sm text-red-800">Tasks with Issues</div>
                                </div>
                            </div>
                        </div>

                        <!-- Check Progress -->
                        @php
                            $startChecksCount = $controlLine->tasks()->whereHas('completions', function($query) {
                                $query->where('check_type', 'start');
                            })->count();
                            
                            $exitChecksCount = $controlLine->tasks()->whereHas('completions', function($query) {
                                $query->where('check_type', 'exit');
                            })->count();
                        @endphp
                        <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                            <h5 class="text-sm font-medium text-purple-900 mb-3">Check Progress</h5>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-purple-800">Start Checks:</span>
                                    <span class="text-sm font-medium text-purple-900">{{ $startChecksCount }}/{{ $controlLine->tasks->count() }}</span>
                                </div>
                                <div class="w-full bg-purple-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $controlLine->tasks->count() > 0 ? round(($startChecksCount / $controlLine->tasks->count()) * 100) : 0 }}%"></div>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-purple-800">Exit Checks:</span>
                                    <span class="text-sm font-medium text-purple-900">{{ $exitChecksCount }}/{{ $controlLine->tasks->count() }}</span>
                                </div>
                                <div class="w-full bg-purple-200 rounded-full h-2">
                                    <div class="bg-orange-600 h-2 rounded-full" style="width: {{ $controlLine->tasks->count() > 0 ? round(($exitChecksCount / $controlLine->tasks->count()) * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Damage Reports Summary -->
                        @if($controlLine->damageReports->count() > 0)
                            <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-xl font-bold text-yellow-600">{{ $controlLine->damageReports->count() }}</div>
                                        <div class="text-sm text-yellow-800">Damage Reports</div>
                                    </div>
                                </div>
                                <div class="mt-2 text-xs text-yellow-700">
                                    @php
                                        $statusCounts = $controlLine->damageReports->groupBy('status')->map->count();
                                    @endphp
                                    @foreach($statusCounts as $status => $count)
                                        <span class="inline-block mr-2">{{ ucfirst(str_replace('_', ' ', $status)) }}: {{ $count }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tasks List -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h4 class="text-lg font-medium text-gray-900">Control Tasks ({{ $controlLine->tasks->count() }})</h4>
            <!-- Filter buttons -->
            <div class="flex space-x-2">
                <button onclick="filterTasks('all')" class="task-filter-btn px-3 py-1 text-sm rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors" data-filter="all">
                    All Tasks
                </button>
                <button onclick="filterTasks('completed')" class="task-filter-btn px-3 py-1 text-sm rounded-md bg-green-100 text-green-700 hover:bg-green-200 transition-colors" data-filter="completed">
                    Completed
                </button>
                <button onclick="filterTasks('pending')" class="task-filter-btn px-3 py-1 text-sm rounded-md bg-yellow-100 text-yellow-700 hover:bg-yellow-200 transition-colors" data-filter="pending">
                    Pending
                </button>
                <button onclick="filterTasks('issues')" class="task-filter-btn px-3 py-1 text-sm rounded-md bg-red-100 text-red-700 hover:bg-red-200 transition-colors" data-filter="issues">
                    With Issues
                </button>
            </div>
        </div>
        
        @if($controlLine->tasks->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($controlLine->tasks as $task)
                    @php
                        $hasIssues = $task->completions->whereIn('status', ['issue', 'missing', 'damaged', 'same_as_start'])->count() > 0;
                        $isCompleted = $task->completions->count() > 0;
                        $isPending = $task->completions->count() === 0;
                    @endphp
                    <div class="task-item p-6 {{ $isCompleted ? 'bg-green-50' : '' }}" 
                         data-status="{{ $isCompleted ? 'completed' : 'pending' }}" 
                         data-has-issues="{{ $hasIssues ? 'true' : 'false' }}">
                        <div class="flex items-start space-x-4">
                            <!-- Task Number -->
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center w-10 h-10 {{ $isCompleted ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }} rounded-full text-sm font-medium">
                                    @if($isCompleted)
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    @else
                                        {{ $task->sort_order }}
                                    @endif
                                </span>
                            </div>

                            <!-- Task Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h5 class="text-lg font-medium text-gray-900 mb-2">{{ $task->title }}</h5>
                                        
                                        @if($task->description)
                                            <p class="text-gray-600 mb-3">{{ $task->description }}</p>
                                        @endif

                                        <!-- Task Meta -->
                                        <div class="flex flex-wrap items-center gap-3 mb-3">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $task->is_required ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $task->is_required ? 'Required' : 'Optional' }}
                                            </span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 capitalize">
                                                {{ $task->task_type }}
                                            </span>
                                            
                                            @if($task->truckTemplate)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    Reference
                                                    @if($task->template_reference_number)
                                                        Point {{ $task->template_reference_number }}
                                                    @endif
                                                </span>
                                            @endif

                                            @if($hasIssues)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Has Issues
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Task Completions -->
                                        @if($task->completions->count() > 0)
                                            <div class="bg-gray-50 rounded-lg p-4 mt-3">
                                                <h6 class="text-sm font-medium text-gray-700 mb-3">Check History ({{ $task->completions->count() }} checks)</h6>
                                                <div class="space-y-3">
                                                    @foreach($task->completions as $completion)
                                                        <div class="border border-gray-200 rounded-lg p-3 bg-white">
                                                            <!-- Completion Header -->
                                                            <div class="flex items-center justify-between mb-2">
                                                                <div class="flex items-center space-x-2">
                                                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $completion->check_type === 'start' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                                                        {{ ucfirst($completion->check_type) }} Check
                                                                    </span>
                                                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $completion->status_color }}">
                                                                        @if($completion->status === 'ok')
                                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                                            </svg>
                                                                            OK
                                                                        @elseif($completion->status === 'issue')
                                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                                            </svg>
                                                                            Issue
                                                                        @elseif($completion->status === 'missing')
                                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                                                            </svg>
                                                                            Missing
                                                                        @elseif($completion->status === 'damaged')
                                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                                            </svg>
                                                                            Damaged
                                                                        @elseif($completion->status === 'same_as_start')
                                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                                <path fill-rule="evenodd" d="M4 2a2 2 0 00-2 2v11a2 2 0 002 2h12a2 2 0 002-2V4a2 2 0 00-2-2H4zm0 2h12v11H4V4zm6 2a1 1 0 100 2 1 1 0 000-2zm-1 4a1 1 0 112 0v2a1 1 0 11-2 0v-2z" clip-rule="evenodd"/>
                                                                            </svg>
                                                                            Same as Start Check
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                                <div class="text-xs text-gray-500">
                                                                    {{ $completion->completed_at->format('M j, g:i A') }} by {{ $completion->completedBy->name }}
                                                                </div>
                                                            </div>

                                                            <!-- Damage Area -->
                                                            @if($completion->damage_area)
                                                                <div class="mb-2">
                                                                    <span class="text-xs text-gray-600 font-medium">Damage Area:</span>
                                                                    <span class="text-xs text-gray-800 ml-1">{{ $completion->damage_area }}</span>
                                                                </div>
                                                            @endif

                                                            <!-- Notes -->
                                                            @if($completion->notes)
                                                                <div class="mb-3">
                                                                    <p class="text-sm text-gray-700">{{ $completion->notes }}</p>
                                                                </div>
                                                            @endif

                                                            <!-- Photos -->
                                                            @if($completion->attachments && count($completion->getAttachmentUrls()) > 0)
                                                                <div class="mt-3">
                                                                    <h6 class="text-xs font-medium text-gray-700 mb-2">Attached Photos:</h6>
                                                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                                                        @foreach($completion->getAttachmentUrls() as $attachment)
                                                                            @if($attachment['type'] === 'image')
                                                                                <div class="relative group">
                                                                                    <img src="{{ $attachment['url'] }}" 
                                                                                         alt="{{ $attachment['name'] }}"
                                                                                         class="w-full h-20 object-cover rounded border border-gray-200 cursor-pointer hover:scale-105 transition-transform shadow-sm"
                                                                                         onclick="openImagePreview('{{ $attachment['url'] }}', '{{ $attachment['name'] }}')">
                                                                                    <!-- Overlay with filename -->
                                                                                    <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 rounded-b opacity-0 group-hover:opacity-100 transition-opacity">
                                                                                        {{ Str::limit($attachment['name'], 20) }}
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @else
                                            <!-- No Completions Yet -->
                                            <div class="bg-yellow-50 rounded-lg p-3 mt-3 border border-yellow-200">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="text-sm text-yellow-800">No checks completed yet for this task</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Template Image -->
                                    @if($task->truckTemplate)
                                        <div class="flex-shrink-0 ml-4">
                                            <img src="{{ asset('storage/' . $task->truckTemplate->image_path) }}" 
                                                 alt="{{ $task->truckTemplate->name }}"
                                                 class="w-24 h-16 object-cover rounded border border-gray-200 cursor-pointer hover:scale-105 transition-transform shadow-sm"
                                                 onclick="openImagePreview('{{ asset('storage/' . $task->truckTemplate->image_path) }}', '{{ $task->truckTemplate->name }}')">
                                        </div>
                                    @endif
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
                <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks</h3>
                <p class="mt-1 text-sm text-gray-500">This control doesn't have any tasks.</p>
            </div>
        @endif
    </div>

    <!-- Recent Damage Reports Summary -->
    @if($controlLine->damageReports->count() > 0)
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h4 class="text-lg font-medium text-gray-900">Recent Damage Reports ({{ $controlLine->damageReports->count() }})</h4>
                <a href="{{ route('admin.control.damages', $controlLine) }}" 
                   class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                    View All Damages →
                </a>
            </div>
            
            <div class="divide-y divide-gray-100">
                @foreach($controlLine->damageReports->take(3) as $damage)
                    <div class="p-6">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $damage->status === 'fixed' ? 'bg-green-100 text-green-800' : ($damage->status === 'in_repair' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst(str_replace('_', ' ', $damage->status)) }}
                                </span>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h5 class="font-medium text-gray-900">{{ $damage->damage_type ?? 'Damage Report' }}</h5>
                                        <p class="text-gray-600 mt-1">{{ Str::limit($damage->description, 100) }}</p>
                                        <div class="text-sm text-gray-500 mt-2">
                                            Reported by {{ $damage->reportedBy->name }} on {{ $damage->created_at->format('M j, Y \a\t g:i A') }}
                                        </div>
                                        @if($damage->damage_area)
                                            <div class="text-xs text-gray-600 mt-1">
                                                <span class="font-medium">Area:</span> {{ $damage->damage_area }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex space-x-2">
                                        @if($damage->status !== 'fixed')
                                            <form method="POST" action="{{ route('admin.damages.update-status', $damage) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="{{ $damage->status === 'reported' ? 'in_repair' : 'fixed' }}">
                                                <button type="submit" class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded hover:bg-blue-200 transition-colors">
                                                    Mark as {{ $damage->status === 'reported' ? 'In Repair' : 'Fixed' }}
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('admin.damages.show', $damage) }}" 
                                           class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded hover:bg-gray-200 transition-colors">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                @if($controlLine->damageReports->count() > 3)
                    <div class="px-6 py-3 bg-gray-50 text-center">
                        <a href="{{ route('admin.control.damages', $controlLine) }}" 
                           class="text-sm text-blue-600 hover:text-blue-900 font-medium">
                            View {{ $controlLine->damageReports->count() - 3 }} more damage reports →
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif

</div>

<script>
    // Task filtering functionality
    function filterTasks(filter) {
        const taskItems = document.querySelectorAll('.task-item');
        const filterButtons = document.querySelectorAll('.task-filter-btn');
        
        // Update button states
        filterButtons.forEach(btn => {
            btn.classList.remove('bg-blue-600', 'text-white');
            btn.classList.add('bg-gray-100', 'text-gray-700');
        });
        
        const activeButton = document.querySelector(`[data-filter="${filter}"]`);
        activeButton.classList.remove('bg-gray-100', 'text-gray-700');
        activeButton.classList.add('bg-blue-600', 'text-white');
        
        // Filter tasks
        taskItems.forEach(item => {
            const status = item.dataset.status;
            const hasIssues = item.dataset.hasIssues === 'true';
            
            let show = false;
            
            switch(filter) {
                case 'all':
                    show = true;
                    break;
                case 'completed':
                    show = status === 'completed';
                    break;
                case 'pending':
                    show = status === 'pending';
                    break;
                case 'issues':
                    show = hasIssues;
                    break;
            }
            
            item.style.display = show ? 'block' : 'none';
        });
    }

    // Enhanced image preview function
    function openImagePreview(imageSrc, title) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4';
        modal.onclick = function(e) {
            if (e.target === this) {
                document.body.removeChild(this);
            }
        };
        
        // Determine if this is a completion photo or template image
        const isCompletionPhoto = imageSrc.includes('task-photos') || imageSrc.includes('damage-photos');
        const titleText = isCompletionPhoto ? `Photo: ${title}` : `Template: ${title}`;
        
        modal.innerHTML = `
            <div class="relative bg-white p-6 rounded-lg max-w-5xl max-h-full overflow-auto shadow-xl">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-900">${titleText}</h3>
                    <button onclick="document.body.removeChild(this.closest('.fixed'))" class="text-gray-400 hover:text-gray-600 p-2 rounded-full hover:bg-gray-100 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="text-center">
                    <img src="${imageSrc}" 
                         alt="${title}" 
                         class="max-w-full max-h-96 mx-auto rounded-lg shadow-lg"
                         onload="this.style.opacity=1" 
                         style="opacity:0; transition: opacity 0.3s ease-in-out;">
                    <p class="text-center text-sm text-gray-600 mt-4">
                        ${isCompletionPhoto ? 'Task completion photo' : 'Reference template for inspection points'}
                    </p>
                </div>
                <!-- Loading indicator -->
                <div class="text-center text-gray-500 mt-4" id="loading-indicator">
                    <svg class="animate-spin h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-xs mt-1">Loading image...</p>
                </div>
            </div>
        `;
        
        // Hide loading indicator when image loads
        const img = modal.querySelector('img');
        img.addEventListener('load', function() {
            const loadingIndicator = modal.querySelector('#loading-indicator');
            if (loadingIndicator) {
                loadingIndicator.style.display = 'none';
            }
        });
        
        document.body.appendChild(modal);
    }

    // Admin action functions
    function openNotificationModal() {
        // Implementation for notification modal
        alert('Notification feature would be implemented here');
    }

    function confirmForceComplete() {
        if (confirm('Are you sure you want to force complete this control? This action cannot be undone.')) {
            // Implementation for force complete
            alert('Force complete feature would be implemented here');
        }
    }

    function confirmReassign() {
        if (confirm('Are you sure you want to reassign this control to another user?')) {
            // Implementation for reassignment
            alert('Reassignment feature would be implemented here');
        }
    }

    // Initialize with 'all' filter active
    document.addEventListener('DOMContentLoaded', function() {
        filterTasks('all');
    });
</script>
@endsection