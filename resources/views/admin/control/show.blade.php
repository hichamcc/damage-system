@extends('components.layouts.app')

@section('title', 'Control Details')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Control Line #{{ $controlLine->id }}</h3>
                <p class="text-sm text-gray-500">Created {{ $controlLine->created_at->format('M d, Y g:i A') }} by {{ $controlLine->createdBy->name }}</p>
            </div>
            <div class="flex space-x-3">
                @if($controlLine->start_check_at && $controlLine->exit_check_at)
                    <a href="{{ route('admin.control.compare', $controlLine) }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Compare Checks
                    </a>
                @endif
                <a href="{{ route('admin.control.edit', $controlLine) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('admin.control.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Control
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Control Information -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h4 class="text-md font-medium text-gray-900">Control Information</h4>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Truck Info -->
                    <div>
                        <div class="flex items-center mb-3">
                            <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center text-white text-xl font-bold">
                                🚛
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-medium text-gray-900">{{ $controlLine->truck->license_plate }}</h3>
                                <p class="text-sm text-gray-500">{{ $controlLine->truck->make }} {{ $controlLine->truck->model }}</p>
                                <p class="text-xs text-gray-400">Truck #{{ $controlLine->truck->truck_number }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- User Info -->
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ $controlLine->assignedUser->initials() ?? substr($controlLine->assignedUser->name, 0, 1) }}
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $controlLine->assignedUser->name }}</p>
                                <p class="text-xs text-gray-500">{{ $controlLine->assignedUser->email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Status & Dates -->
                    <div class="border-t border-gray-200 pt-4 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500">Status:</span>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $controlLine->status_badge_color }}">
                                {{ ucfirst($controlLine->status) }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500">Assigned:</span>
                            <span class="text-sm text-gray-900">{{ $controlLine->assigned_at->format('M d, Y H:i') }}</span>
                        </div>

                        @if($controlLine->start_check_at)
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">START Check:</span>
                                <span class="text-sm text-green-600">{{ $controlLine->start_check_at->format('M d, Y H:i') }}</span>
                            </div>
                        @endif

                        @if($controlLine->exit_check_at)
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">EXIT Check:</span>
                                <span class="text-sm text-red-600">{{ $controlLine->exit_check_at->format('M d, Y H:i') }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Notes -->
                    @if($controlLine->notes)
                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-sm font-medium text-gray-500 mb-2">Notes:</p>
                            <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-md">{{ $controlLine->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Progress Overview -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h4 class="text-md font-medium text-gray-900">Progress Overview</h4>
                </div>
                <div class="p-6 space-y-4">
                    <!-- START Progress -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-green-600">START Check</span>
                            <span class="text-sm text-gray-500">{{ $controlLine->startCheckProgress() }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-green-500 h-2.5 rounded-full transition-all duration-300" style="width: {{ $controlLine->startCheckProgress() }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $controlLine->completions->where('check_type', 'start')->count() }} of {{ $controlLine->tasks->where('is_required', true)->count() }} required tasks completed
                        </p>
                    </div>

                    <!-- EXIT Progress -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-red-600">EXIT Check</span>
                            <span class="text-sm text-gray-500">{{ $controlLine->exitCheckProgress() }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-red-500 h-2.5 rounded-full transition-all duration-300" style="width: {{ $controlLine->exitCheckProgress() }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $controlLine->completions->where('check_type', 'exit')->count() }} of {{ $controlLine->tasks->where('is_required', true)->count() }} required tasks completed
                        </p>
                    </div>
                </div>
            </div>

            <!-- Damage Summary -->
            @if($controlLine->damageReports && $controlLine->damageReports->count() > 0)
            <!-- Damage Reports Summary -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-medium text-red-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Damage Reports ({{ $controlLine->damageReports->count() }})
                    </h4>
                    <a href="{{ route('admin.control.damages', $controlLine) }}" 
                       class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        View All Damages
                    </a>
                </div>
                
                <!-- Damage Summary Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-800">{{ $controlLine->damageReports->where('status', 'reported')->count() }}</div>
                        <div class="text-sm text-red-600">Open</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-700">{{ $controlLine->damageReports->where('status', 'in_repair')->count() }}</div>
                        <div class="text-sm text-yellow-600">In Repair</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-700">{{ $controlLine->damageReports->where('status', 'fixed')->count() }}</div>
                        <div class="text-sm text-green-600">Fixed</div>
                    </div>
                </div>
        
                <!-- Recent Damages Preview -->
                <div class="space-y-2">
                    @foreach($controlLine->damageReports->take(3) as $damage)
                        <div class="flex items-center justify-between p-2 bg-white rounded border">
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium mr-2
                                    @if($damage->severity === 'critical') bg-red-100 text-red-800
                                    @elseif($damage->severity === 'major') bg-orange-100 text-orange-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ ucfirst($damage->severity) }}
                                </span>
                                <span class="text-sm font-medium text-gray-900">{{ $damage->damage_location }}</span>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                @if($damage->status === 'reported') bg-red-100 text-red-800
                                @elseif($damage->status === 'in_repair') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $damage->status)) }}
                            </span>
                        </div>
                    @endforeach
                    
                    @if($controlLine->damageReports->count() > 3)
                        <div class="text-center pt-2">
                            <span class="text-sm text-red-600">and {{ $controlLine->damageReports->count() - 3 }} more...</span>
                        </div>
                    @endif
                </div>
            </div>
        @endif
        </div>

        <!-- Tasks Details -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h4 class="text-md font-medium text-gray-900">Control Tasks ({{ $controlLine->tasks->count() }})</h4>
                    @if($controlLine->status === 'active')
                    <button type="button" id="add-task-btn" class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add Task
                    </button>
                    @endif
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($controlLine->tasks as $task)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <!-- Task Header -->
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <h5 class="text-sm font-medium text-gray-900">{{ $task->title }}</h5>
                                            <span class="ml-2 inline-flex px-2 py-1 text-xs font-medium rounded {{ $task->task_type_color }}">
                                                {{ ucfirst($task->task_type) }}
                                            </span>
                                            @if($task->is_required)
                                                <span class="ml-1 text-red-500 text-xs">*</span>
                                            @endif
                                        </div>
                                        @if($task->description)
                                            <p class="text-xs text-gray-600 mb-2">{{ $task->description }}</p>
                                        @endif
                                        
                                        <!-- Template Reference Display -->
                                        @if($task->truck_template_id)
                                            <div class="flex items-center bg-blue-50 border border-blue-200 rounded-lg p-2 mb-2">
                                                <svg class="w-4 h-4 text-blue-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center">
                                                        <span class="text-xs font-medium text-blue-900">{{ $task->truckTemplate->name ?? 'Template' }}</span>
                                                        @if($task->template_reference_number)
                                                            <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                Point #{{ $task->template_reference_number }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    @if($task->truckTemplate)
                                                        <div class="text-xs text-blue-700">{{ ucfirst($task->truckTemplate->view_type) }} view</div>
                                                    @endif
                                                </div>
                                                @if($task->truckTemplate && $task->truckTemplate->image_path)
                                                    <button type="button" 
                                                            onclick="openTemplatePreview('{{ asset('storage/' . $task->truckTemplate->image_path) }}', '{{ $task->truckTemplate->name }}', {{ $task->template_reference_number ?? 'null' }})"
                                                            class="ml-2 inline-flex items-center px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                        View
                                                    </button>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <form action="{{ route('admin.control.tasks.remove', [$controlLine, $task]) }}" method="POST" class="ml-3">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Remove Task" onclick="return confirm('Are you sure you want to remove this task?')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>

                                <!-- Task Completion Status -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- START Check -->
                                    <div class="border border-gray-200 rounded-lg p-3">
                                        <h6 class="text-xs font-medium text-green-600 mb-2">START Check</h6>
                                        @php $startCompletion = $task->completions->where('check_type', 'start')->first(); @endphp
                                        
                                        @if($startCompletion)
                                            <div class="space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $startCompletion->status_color }}">
                                                        {{ ucfirst($startCompletion->status) }}
                                                    </span>
                                                    <span class="text-xs text-gray-500">{{ $startCompletion->completed_at->format('M d H:i') }}</span>
                                                </div>
                                                @if($startCompletion->notes)
                                                    <p class="text-xs text-gray-600 bg-gray-50 p-2 rounded">{{ $startCompletion->notes }}</p>
                                                @endif
                                                @if($startCompletion->attachments && count($startCompletion->attachments) > 0)
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($startCompletion->getAttachmentUrls() as $attachment)
                                                            <a href="{{ $attachment['url'] }}" target="_blank" class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded hover:bg-blue-200">
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.586-6.586a4 4 0 00-5.656-5.656l-6.586 6.586a6 6 0 008.486 8.486L20.5 13"/>
                                                                </svg>
                                                                {{ $attachment['name'] }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                <p class="text-xs text-gray-500">by {{ $startCompletion->completedBy->name }}</p>
                                            </div>
                                        @else
                                            <div class="text-center py-2">
                                                <svg class="w-6 h-6 text-gray-300 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <p class="text-xs text-gray-400">Not completed</p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- EXIT Check -->
                                    <div class="border border-gray-200 rounded-lg p-3">
                                        <h6 class="text-xs font-medium text-red-600 mb-2">EXIT Check</h6>
                                        @php $exitCompletion = $task->completions->where('check_type', 'exit')->first(); @endphp
                                        
                                        @if($exitCompletion)
                                            <div class="space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $exitCompletion->status_color }}">
                                                        {{ ucfirst($exitCompletion->status) }}
                                                    </span>
                                                    <span class="text-xs text-gray-500">{{ $exitCompletion->completed_at->format('M d H:i') }}</span>
                                                </div>
                                                @if($exitCompletion->notes)
                                                    <p class="text-xs text-gray-600 bg-gray-50 p-2 rounded">{{ $exitCompletion->notes }}</p>
                                                @endif
                                                @if($exitCompletion->attachments && count($exitCompletion->attachments) > 0)
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($exitCompletion->getAttachmentUrls() as $attachment)
                                                            <a href="{{ $attachment['url'] }}" target="_blank" class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded hover:bg-blue-200">
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.586-6.586a4 4 0 00-5.656-5.656l-6.586 6.586a6 6 0 008.486 8.486L20.5 13"/>
                                                                </svg>
                                                                {{ $attachment['name'] }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                <p class="text-xs text-gray-500">by {{ $exitCompletion->completedBy->name }}</p>
                                            </div>
                                        @else
                                            <div class="text-center py-2">
                                                <svg class="w-6 h-6 text-gray-300 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <p class="text-xs text-gray-400">Not completed</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Changes Indicator -->
                                @if($startCompletion && $exitCompletion)
                                    @if($startCompletion->status !== $exitCompletion->status)
                                        <div class="mt-3 p-2 bg-yellow-50 border border-yellow-200 rounded-lg">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="text-xs font-medium text-yellow-800">
                                                    Status changed: {{ ucfirst($startCompletion->status) }} → {{ ucfirst($exitCompletion->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <p class="text-sm text-gray-500">No tasks added yet.</p>
                                <p class="text-xs text-gray-400">Click "Add Task" to create control tasks.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Task Modal -->
<div id="add-task-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Task</h3>
            <form action="{{ route('admin.control.tasks.add', $controlLine) }}" method="POST" id="add-task-form">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Task Title</label>
                        <input type="text" name="title" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="task_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="check">Check</option>
                            <option value="inspect">Inspect</option>
                            <option value="document">Document</option>
                            <option value="report">Report</option>
                        </select>
                    </div>
                    
                    <!-- Template Reference Fields -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reference Template <span class="text-xs text-gray-500">(Optional)</span></label>
                            <select name="truck_template_id" id="modal-template-select" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Template</option>
                                <!-- Templates will be populated via JavaScript based on truck -->
                            </select>
                        </div>
                        <div id="modal-template-number-container" style="display: none;">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reference Point Number</label>
                            <input type="number" name="template_reference_number" id="modal-template-number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., 1, 2, 3..." min="1">
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="is_required" value="1" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-700">Required Task</label>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" id="cancel-add-task" class="px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                        Add Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Template Preview Modal -->
<div id="template-preview-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative bg-white p-4 rounded-lg max-w-4xl max-h-full overflow-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900" id="preview-title">Template Preview</h3>
            <button onclick="closeTemplatePreview()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="relative">
            <img id="preview-image" src="" alt="Template" class="max-w-full max-h-96 mx-auto rounded">
            <div id="highlight-point" class="absolute bg-red-500 border-2 border-red-600 rounded-full opacity-75" style="width: 20px; height: 20px; display: none;"></div>
        </div>
        <p class="text-center text-sm text-gray-600 mt-2" id="preview-description">Template reference view</p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('add-task-modal');
    const addBtn = document.getElementById('add-task-btn');
    const cancelBtn = document.getElementById('cancel-add-task');
    const templateSelect = document.getElementById('modal-template-select');
    const templateNumberContainer = document.getElementById('modal-template-number-container');
    const templateNumberInput = document.getElementById('modal-template-number');
    
    // Load available templates for this truck
    function loadTemplatesForModal() {
        const truckType = '{{ $controlLine->truck->truck_type ?? "" }}';
        
        fetch(`{{ route('admin.truck-templates.api') }}?truck_type=${truckType}`)
            .then(response => response.json())
            .then(data => {
                templateSelect.innerHTML = '<option value="">Select Template</option>';
                data.forEach(template => {
                    const option = document.createElement('option');
                    option.value = template.id;
                    option.textContent = `${template.name} (${template.view_type} - ${template.number_points} points)`;
                    templateSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading templates:', error);
            });
    }
    
    // Show/hide template number input
    templateSelect.addEventListener('change', function() {
        if (this.value) {
            templateNumberContainer.style.display = 'block';
        } else {
            templateNumberContainer.style.display = 'none';
            templateNumberInput.value = '';
        }
    });
    
    // Show modal
    if (addBtn) {
        addBtn.addEventListener('click', function() {
            modal.classList.remove('hidden');
            loadTemplatesForModal();
        });
    }
    
    // Hide modal
    cancelBtn.addEventListener('click', function() {
        modal.classList.add('hidden');
    });
    
    // Hide modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });
});

// Template preview functions
function openTemplatePreview(imageSrc, title, pointNumber) {
    const modal = document.getElementById('template-preview-modal');
    const previewTitle = document.getElementById('preview-title');
    const previewImage = document.getElementById('preview-image');
    const previewDescription = document.getElementById('preview-description');
    const highlightPoint = document.getElementById('highlight-point');
    
    previewTitle.textContent = title;
    previewImage.src = imageSrc;
    
    if (pointNumber) {
        previewDescription.textContent = `Template reference view - Point #${pointNumber} highlighted`;
        // You could add logic here to position the highlight point based on stored coordinates
        // For now, we'll just show it's referencing a specific point
    } else {
        previewDescription.textContent = 'Template reference view';
    }
    
    modal.classList.remove('hidden');
}

function closeTemplatePreview() {
    document.getElementById('template-preview-modal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('template-preview-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeTemplatePreview();
    }
});
</script>
@endsection