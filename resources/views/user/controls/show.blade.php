@extends('components.layouts.app')

@section('title', 'Control Details')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Control #{{ $controlLine->id }} Details</h3>
                <p class="text-sm text-gray-500">{{ $controlLine->truck->license_plate }} - {{ $controlLine->truck->make }} {{ $controlLine->truck->model }}</p>
            </div>
            <div class="flex space-x-2">
                @if($controlLine->status === 'active')
                    @if(!$controlLine->start_check_at)
                        <a href="{{ route('user.controls.start', $controlLine) }}" 
                           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Start Check
                        </a>
                    @elseif(!$controlLine->exit_check_at)
                        <a href="{{ route('user.controls.exit', $controlLine) }}" 
                           class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Exit Check
                        </a>
                    @endif
                @endif
                
                <a href="{{ route('user.controls') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Controls
                </a>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Control Information -->
                <div class="lg:col-span-1">
                    <div class="space-y-6">
                        <!-- Vehicle Information -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                Vehicle Information
                            </h4>
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">License Plate</label>
                                    <p class="text-sm font-medium text-gray-900">{{ $controlLine->truck->license_plate }}</p>
                                </div>
                                
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Make & Model</label>
                                    <p class="text-sm text-gray-900">{{ $controlLine->truck->make }} {{ $controlLine->truck->model }}</p>
                                </div>
                                
                                @if($controlLine->truck->truck_number)
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Truck Number</label>
                                    <p class="text-sm text-gray-900">{{ $controlLine->truck->truck_number }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Control Status -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Control Status
                            </h4>
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status</label>
                                    @if($controlLine->status === 'active')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Active
                                        </span>
                                    @elseif($controlLine->status === 'completed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Completed
                                        </span>
                                    @endif
                                </div>
                                
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Assigned Date</label>
                                    <p class="text-sm text-gray-900">{{ $controlLine->assigned_at->format('M d, Y H:i') }}</p>
                                </div>
                                
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">START Check</label>
                                    @if($controlLine->start_check_at)
                                        <div class="flex items-center text-green-600">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-sm">Completed at {{ $controlLine->start_check_at->format('M d, Y H:i') }}</span>
                                        </div>
                                    @else
                                        <div class="flex items-center text-orange-600">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L10 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-sm">Pending</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">EXIT Check</label>
                                    @if($controlLine->exit_check_at)
                                        <div class="flex items-center text-green-600">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-sm">Completed at {{ $controlLine->exit_check_at->format('M d, Y H:i') }}</span>
                                        </div>
                                    @else
                                        <div class="flex items-center text-orange-600">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L10 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-sm">{{ $controlLine->start_check_at ? 'Pending' : 'Waiting for START check' }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        @if($controlLine->notes)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-md font-medium text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                                Control Notes
                            </h4>
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $controlLine->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Tasks Section -->
                <div class="lg:col-span-2">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h4 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m0 6h-3m0 0l3 3m-3-3l3-3"/>
                                </svg>
                                Control Tasks ({{ $controlLine->tasks->count() }})
                            </h4>
                            
                            <div class="text-sm text-gray-600">
                                @php
                                    $startCompletions = $controlLine->completions->where('check_type', 'start')->count();
                                    $exitCompletions = $controlLine->completions->where('check_type', 'exit')->count();
                                    $totalTasks = $controlLine->tasks->count();
                                @endphp
                                START: {{ $startCompletions }}/{{ $totalTasks }} | EXIT: {{ $exitCompletions }}/{{ $totalTasks }}
                            </div>
                        </div>

                        @if($controlLine->tasks->count() > 0)
                            <div class="space-y-4">
                                @foreach($controlLine->tasks as $index => $task)
                                    @php
                                        $startCompletion = $controlLine->completions->where('control_task_id', $task->id)->where('check_type', 'start')->first();
                                        $exitCompletion = $controlLine->completions->where('control_task_id', $task->id)->where('check_type', 'exit')->first();
                                    @endphp
                                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 mr-3">
                                                    <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                                                        <span class="text-xs font-medium text-white">{{ $index + 1 }}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="flex-1">
                                                    <h5 class="font-medium text-gray-900">{{ $task->title }}</h5>
                                                    @if($task->description)
                                                        <p class="text-sm text-gray-600 mt-1">{{ $task->description }}</p>
                                                    @endif
                                                    
                                                    <!-- Template Reference Display -->
                                                    @if($task->truck_template_id && $task->truckTemplate)
                                                        <div class="flex items-center bg-blue-50 border border-blue-200 rounded-lg p-2 mt-2">
                                                            <svg class="w-4 h-4 text-blue-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                            <div class="flex-1 min-w-0">
                                                                <div class="flex items-center">
                                                                    <span class="text-xs font-medium text-blue-900">{{ $task->truckTemplate->name }}</span>
                                                                    @if($task->template_reference_number)
                                                                        <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                            Point #{{ $task->template_reference_number }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <div class="text-xs text-blue-700">{{ ucfirst($task->truckTemplate->view_type) }} view</div>
                                                            </div>
                                                            @if($task->truckTemplate->image_path)
                                                                <button type="button" 
                                                                        onclick="openTemplatePreview('{{ asset('storage/' . $task->truckTemplate->image_path) }}', '{{ $task->truckTemplate->name }}', {{ $task->template_reference_number ?? 'null' }})"
                                                                        class="ml-2 inline-flex items-center px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                                    </svg>
                                                                    View Template
                                                                </button>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center space-x-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($task->task_type === 'check') bg-blue-100 text-blue-800
                                                    @elseif($task->task_type === 'inspect') bg-purple-100 text-purple-800
                                                    @elseif($task->task_type === 'document') bg-green-100 text-green-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst($task->task_type) }}
                                                </span>
                                                
                                                @if($task->is_required)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Required
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- START and EXIT Check Status -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                            <!-- START Check -->
                                            <div class="border border-gray-200 rounded-md p-3">
                                                <div class="flex items-center justify-between mb-2">
                                                    <span class="text-sm font-medium text-gray-700">START Check</span>
                                                    @if($startCompletion)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                            @if($startCompletion->status === 'ok') bg-green-100 text-green-800
                                                            @elseif($startCompletion->status === 'issue') bg-yellow-100 text-yellow-800
                                                            @elseif($startCompletion->status === 'damaged') bg-red-100 text-red-800
                                                            @else bg-gray-100 text-gray-800
                                                            @endif">
                                                            {{ ucfirst($startCompletion->status) }}
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                            Pending
                                                        </span>
                                                    @endif
                                                </div>
                                                
                                                @if($startCompletion)
                                                    @if($startCompletion->notes)
                                                        <p class="text-xs text-gray-600 mb-2">{{ $startCompletion->notes }}</p>
                                                    @endif
                                                    
                                                    @if($startCompletion->damage_area)
                                                        <div class="mb-2">
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                                </svg>
                                                                {{ $startCompletion->damage_area_display }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    
                                                    @if($startCompletion->attachments && count($startCompletion->attachments) > 0)
                                                        <div class="flex flex-wrap gap-1">
                                                            @foreach($startCompletion->attachments as $attachment)
                                                                <a href="{{ Storage::url($attachment['path']) }}" target="_blank" 
                                                                   class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded hover:bg-blue-200">
                                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    {{ $attachment['name'] }}
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    
                                                    <p class="text-xs text-gray-500 mt-2">
                                                        Completed: {{ $startCompletion->completed_at->format('M d, Y H:i') }}
                                                    </p>
                                                @else
                                                    <p class="text-xs text-gray-500">Not completed yet</p>
                                                @endif
                                            </div>
                                            
                                            <!-- EXIT Check -->
                                            <div class="border border-gray-200 rounded-md p-3">
                                                <div class="flex items-center justify-between mb-2">
                                                    <span class="text-sm font-medium text-gray-700">EXIT Check</span>
                                                    @if($exitCompletion)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                            @if($exitCompletion->status === 'ok') bg-green-100 text-green-800
                                                            @elseif($exitCompletion->status === 'issue') bg-yellow-100 text-yellow-800
                                                            @elseif($exitCompletion->status === 'damaged') bg-red-100 text-red-800
                                                            @else bg-gray-100 text-gray-800
                                                            @endif">
                                                            {{ ucfirst($exitCompletion->status) }}
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                            {{ $controlLine->start_check_at ? 'Pending' : 'Waiting for START' }}
                                                        </span>
                                                    @endif
                                                </div>
                                                
                                                @if($exitCompletion)
                                                    @if($exitCompletion->notes)
                                                        <p class="text-xs text-gray-600 mb-2">{{ $exitCompletion->notes }}</p>
                                                    @endif
                                                    
                                                    @if($exitCompletion->damage_area)
                                                        <div class="mb-2">
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                                </svg>
                                                                {{ $exitCompletion->damage_area_display }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    
                                                    @if($exitCompletion->attachments && count($exitCompletion->attachments) > 0)
                                                        <div class="flex flex-wrap gap-1">
                                                            @foreach($exitCompletion->attachments as $attachment)
                                                                <a href="{{ Storage::url($attachment['path']) }}" target="_blank" 
                                                                   class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded hover:bg-blue-200">
                                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    {{ $attachment['name'] }}
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    
                                                    <p class="text-xs text-gray-500 mt-2">
                                                        Completed: {{ $exitCompletion->completed_at->format('M d, Y H:i') }}
                                                    </p>
                                                @else
                                                    <p class="text-xs text-gray-500">
                                                        {{ $controlLine->start_check_at ? 'Not completed yet' : 'Complete START check first' }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Damage Reports for this task -->
                                        @php
                                            $damageReports = $controlLine->damageReports->where('control_task_id', $task->id);
                                        @endphp
                                        @if($damageReports->count() > 0)
                                            <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-md">
                                                <h6 class="text-sm font-medium text-red-800 mb-2 flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Damage Reports
                                                </h6>
                                                @foreach($damageReports as $damage)
                                                    <div class="text-sm text-red-700 mb-1">
                                                        <strong>{{ ucfirst($damage->severity) }}:</strong> {{ $damage->damage_description }}
                                                        <span class="text-xs text-red-600">({{ $damage->created_at->format('M d, H:i') }})</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <h3 class="text-sm font-medium text-gray-900">No tasks assigned</h3>
                                <p class="text-sm text-gray-500">This control doesn't have any tasks yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Damage Reports Section -->
            @php
                $allDamageReports = $controlLine->damageReports;
            @endphp
            @if($allDamageReports->count() > 0)
                <div class="mt-8 bg-red-50 border border-red-200 rounded-lg p-6">
                    <h4 class="text-lg font-medium text-red-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        All Damage Reports ({{ $allDamageReports->count() }})
                    </h4>
                    
                    <div class="space-y-4">
                        @foreach($allDamageReports as $damage)
                            <div class="bg-white border border-red-200 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h5 class="font-medium text-red-900">{{ $damage->damage_location }}</h5>
                                        <p class="text-sm text-red-700">{{ $damage->damage_description }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($damage->severity === 'minor') bg-yellow-100 text-yellow-800
                                        @elseif($damage->severity === 'major') bg-orange-100 text-orange-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($damage->severity) }}
                                    </span>
                                </div>
                                
                                @if($damage->damage_photos && count($damage->damage_photos) > 0)
                                    <div class="flex flex-wrap gap-2 mt-3">
                                        @foreach($damage->damage_photos as $photo)
                                            <a href="{{ Storage::url($photo['path']) }}" target="_blank" 
                                               class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 text-sm rounded hover:bg-red-200">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ $photo['name'] }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                                
                                <p class="text-xs text-red-600 mt-2">
                                    Reported: {{ $damage->created_at->format('M d, Y H:i') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
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