@extends('components.layouts.app')

@section('title', 'Control Comparison - Start vs Exit')

@section('content')
<div class="p-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">Control Comparison: Start vs Exit</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $controlLine->truck->license_plate }} - {{ $controlLine->truck->make }} {{ $controlLine->truck->model }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $controlLine->status === 'active' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                        {{ ucfirst($controlLine->status) }}
                    </span>
                    @php
                        $changesCount = collect($comparison)->where('has_changes', true)->count();
                    @endphp
                    @if($changesCount > 0)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $changesCount }} Change{{ $changesCount > 1 ? 's' : '' }} Detected
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            No Changes Detected
                        </span>
                    @endif
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.control.show', $controlLine) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    View Control Details
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
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Control Info -->
                <div class="lg:col-span-2">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Template</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $controlLine->controlTemplate->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Assigned User</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $controlLine->assignedUser->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Created</dt>
                                <dd class="text-sm text-gray-900">{{ $controlLine->created_at->format('M j, Y g:i A') }}</dd>
                            </div>
                            @if($controlLine->completed_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Completed</dt>
                                    <dd class="text-sm text-gray-900">{{ $controlLine->completed_at->format('M j, Y g:i A') }}</dd>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Comparison Stats -->
                <div class="lg:col-span-2">
                    <div class="grid grid-cols-2 gap-4">
                        @php
                            $totalTasks = count($comparison);
                            $bothCompleted = collect($comparison)->where('start', '!=', null)->where('exit', '!=', null)->count();
                            $onlyStart = collect($comparison)->where('start', '!=', null)->where('exit', '=', null)->count();
                            $onlyExit = collect($comparison)->where('start', '=', null)->where('exit', '!=', null)->count();
                        @endphp
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ $totalTasks }}</div>
                                <div class="text-sm text-blue-800">Total Tasks</div>
                            </div>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ $bothCompleted }}</div>
                                <div class="text-sm text-green-800">Both Checks Done</div>
                            </div>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-yellow-600">{{ $onlyStart }}</div>
                                <div class="text-sm text-yellow-800">Start Only</div>
                            </div>
                        </div>
                        <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-red-600">{{ $changesCount }}</div>
                                <div class="text-sm text-red-800">Changes Found</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Options -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h4 class="text-lg font-medium text-gray-900">Filter Comparison</h4>
        </div>
        <div class="p-6">
            <div class="flex flex-wrap gap-2">
                <button onclick="filterTasks('all')" class="task-filter-btn px-4 py-2 text-sm rounded-md bg-blue-600 text-white transition-colors" data-filter="all">
                    All Tasks ({{ $totalTasks }})
                </button>
                <button onclick="filterTasks('changes')" class="task-filter-btn px-4 py-2 text-sm rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors" data-filter="changes">
                    Changes Only ({{ $changesCount }})
                </button>
                <button onclick="filterTasks('both-completed')" class="task-filter-btn px-4 py-2 text-sm rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors" data-filter="both-completed">
                    Both Completed ({{ $bothCompleted }})
                </button>
                <button onclick="filterTasks('missing-exit')" class="task-filter-btn px-4 py-2 text-sm rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors" data-filter="missing-exit">
                    Missing Exit ({{ $onlyStart }})
                </button>
                <button onclick="filterTasks('issues')" class="task-filter-btn px-4 py-2 text-sm rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors" data-filter="issues">
                    With Issues
                </button>
            </div>
        </div>
    </div>

    <!-- Comparison Results -->
    <div class="space-y-6">
        @foreach($comparison as $index => $item)
            @php
                $task = $item['task'];
                $start = $item['start'];
                $exit = $item['exit'];
                $hasChanges = $item['has_changes'];
                $hasIssues = ($start && in_array($start->status, ['issue', 'missing', 'damaged'])) || 
                           ($exit && in_array($exit->status, ['issue', 'missing', 'damaged', 'same_as_start']));
            @endphp
            <div class="comparison-item bg-white rounded-lg shadow {{ $hasChanges ? 'ring-2 ring-red-200' : '' }}" 
                 data-has-changes="{{ $hasChanges ? 'true' : 'false' }}"
                 data-both-completed="{{ ($start && $exit) ? 'true' : 'false' }}"
                 data-missing-exit="{{ ($start && !$exit) ? 'true' : 'false' }}"
                 data-has-issues="{{ $hasIssues ? 'true' : 'false' }}">
                
                <!-- Task Header -->
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center justify-center w-10 h-10 {{ $hasChanges ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }} rounded-full text-sm font-medium">
                                @if($hasChanges)
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    {{ $task->sort_order }}
                                @endif
                            </span>
                        </div>
                        <div>
                            <h5 class="text-lg font-medium text-gray-900">{{ $task->title }}</h5>
                            @if($task->description)
                                <p class="text-sm text-gray-600">{{ $task->description }}</p>
                            @endif
                            <div class="flex items-center space-x-2 mt-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $task->is_required ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $task->is_required ? 'Required' : 'Optional' }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 capitalize">
                                    {{ $task->task_type }}
                                </span>
                                @if($hasChanges)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        Change Detected
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- Template Image -->
                    @if($task->truckTemplate)
                        <div class="flex-shrink-0">
                            <img src="{{ asset('storage/' . $task->truckTemplate->image_path) }}" 
                                 alt="{{ $task->truckTemplate->name }}"
                                 class="w-24 h-16 object-cover rounded border border-gray-200 cursor-pointer hover:scale-105 transition-transform shadow-sm"
                                 onclick="openImagePreview('{{ asset('storage/' . $task->truckTemplate->image_path) }}', '{{ $task->truckTemplate->name }}')">
                        </div>
                    @endif
                </div>

                <!-- Comparison Content -->
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Start Check -->
                        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                            <div class="flex items-center justify-between mb-3">
                                <h6 class="text-sm font-medium text-green-900">START CHECK</h6>
                                @if($start)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $start->status === 'ok' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        @if($start->status === 'ok')
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                        {{ ucfirst($start->status) }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                        Not Completed
                                    </span>
                                @endif
                            </div>
                            @if($start)
                                <div class="space-y-2">
                                    <div class="text-xs text-green-700">
                                        <span class="font-medium">Completed:</span> {{ $start->completed_at->format('M j, Y g:i A') }}
                                    </div>
                                    <div class="text-xs text-green-700">
                                        <span class="font-medium">By:</span> {{ $start->completedBy->name }}
                                    </div>
                                    @if($start->damage_area)
                                        <div class="text-xs text-green-700">
                                            <span class="font-medium">Area:</span> {{ $start->damage_area }}
                                        </div>
                                    @endif
                                    @if($start->notes)
                                        <div class="text-xs text-green-700">
                                            <span class="font-medium">Notes:</span> {{ $start->notes }}
                                        </div>
                                    @endif
                                    @if($start->attachments && count($start->getAttachmentUrls()) > 0)
                                        <div class="mt-3">
                                            <div class="text-xs font-medium text-green-700 mb-2">Photos ({{ count($start->getAttachmentUrls()) }}):</div>
                                            <div class="grid grid-cols-2 gap-2">
                                                @foreach($start->getAttachmentUrls() as $attachment)
                                                    @if($attachment['type'] === 'image')
                                                        <img src="{{ $attachment['url'] }}" 
                                                             alt="{{ $attachment['name'] }}"
                                                             class="w-full h-16 object-cover rounded border border-green-300 cursor-pointer hover:scale-105 transition-transform"
                                                             onclick="openImagePreview('{{ $attachment['url'] }}', '{{ $attachment['name'] }}')">
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-center text-gray-500 py-4">
                                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-xs mt-1">Start check not completed</p>
                                </div>
                            @endif
                        </div>

                        <!-- Exit Check -->
                        <div class="bg-orange-50 rounded-lg p-4 border border-orange-200">
                            <div class="flex items-center justify-between mb-3">
                                <h6 class="text-sm font-medium text-orange-900">EXIT CHECK</h6>
                                @if($exit)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $exit->status === 'ok' ? 'bg-green-100 text-green-800' : ($exit->status === 'same_as_start' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                                        @if($exit->status === 'ok')
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        @elseif($exit->status === 'same_as_start')
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 2a2 2 0 00-2 2v11a2 2 0 002 2h12a2 2 0 002-2V4a2 2 0 00-2-2H4zm0 2h12v11H4V4zm6 2a1 1 0 100 2 1 1 0 000-2zm-1 4a1 1 0 112 0v2a1 1 0 11-2 0v-2z" clip-rule="evenodd"/>
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                        {{ $exit->status === 'same_as_start' ? 'Same as Start' : ucfirst($exit->status) }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                        Not Completed
                                    </span>
                                @endif
                            </div>
                            @if($exit)
                                <div class="space-y-2">
                                    <div class="text-xs text-orange-700">
                                        <span class="font-medium">Completed:</span> {{ $exit->completed_at->format('M j, Y g:i A') }}
                                    </div>
                                    <div class="text-xs text-orange-700">
                                        <span class="font-medium">By:</span> {{ $exit->completedBy->name }}
                                    </div>
                                    @if($exit->damage_area)
                                        <div class="text-xs text-orange-700">
                                            <span class="font-medium">Area:</span> {{ $exit->damage_area }}
                                        </div>
                                    @endif
                                    @if($exit->notes)
                                        <div class="text-xs text-orange-700">
                                            <span class="font-medium">Notes:</span> {{ $exit->notes }}
                                        </div>
                                    @endif
                                    @if($exit->attachments && count($exit->getAttachmentUrls()) > 0)
                                        <div class="mt-3">
                                            <div class="text-xs font-medium text-orange-700 mb-2">Photos ({{ count($exit->getAttachmentUrls()) }}):</div>
                                            <div class="grid grid-cols-2 gap-2">
                                                @foreach($exit->getAttachmentUrls() as $attachment)
                                                    @if($attachment['type'] === 'image')
                                                        <img src="{{ $attachment['url'] }}" 
                                                             alt="{{ $attachment['name'] }}"
                                                             class="w-full h-16 object-cover rounded border border-orange-300 cursor-pointer hover:scale-105 transition-transform"
                                                             onclick="openImagePreview('{{ $attachment['url'] }}', '{{ $attachment['name'] }}')">
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-center text-gray-500 py-4">
                                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-xs mt-1">Exit check not completed</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Change Analysis -->
                    @if($hasChanges && $start && $exit)
                        <div class="mt-4 p-4 bg-red-50 rounded-lg border border-red-200">
                            <div class="flex items-center mb-2">
                                <svg class="w-4 h-4 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <h6 class="text-sm font-medium text-red-900">Changes Detected</h6>
                            </div>
                            <div class="space-y-1 text-xs text-red-800">
                                @if($start->status !== $exit->status)
                                    <div>• Status changed from "{{ ucfirst($start->status) }}" to "{{ ucfirst($exit->status) }}"</div>
                                @endif
                                @if($start->notes !== $exit->notes)
                                    <div>• Notes were modified</div>
                                @endif
                                @if($start->damage_area !== $exit->damage_area)
                                    <div>• Damage area changed</div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if(count($comparison) === 0)
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5l7-7 7 7M9 20h6m-7 4h7m0 0v5a2 2 0 002 2h14a2 2 0 002-2v-5M5 12a2 2 0 012-2h10a2 2 0 012 2v5a2 2 0 01-2 2H7a2 2 0 01-2-2v-5z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks to compare</h3>
            <p class="mt-1 text-sm text-gray-500">This control doesn't have any tasks to compare.</p>
        </div>
    @endif
</div>

<script>
    // Task filtering functionality
    function filterTasks(filter) {
        const taskItems = document.querySelectorAll('.comparison-item');
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
            const hasChanges = item.dataset.hasChanges === 'true';
            const bothCompleted = item.dataset.bothCompleted === 'true';
            const missingExit = item.dataset.missingExit === 'true';
            const hasIssues = item.dataset.hasIssues === 'true';
            
            let show = false;
            
            switch(filter) {
                case 'all':
                    show = true;
                    break;
                case 'changes':
                    show = hasChanges;
                    break;
                case 'both-completed':
                    show = bothCompleted;
                    break;
                case 'missing-exit':
                    show = missingExit;
                    break;
                case 'issues':
                    show = hasIssues;
                    break;
            }
            
            item.style.display = show ? 'block' : 'none';
        });
    }

    function openImagePreview(imageSrc, title) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4';
        modal.onclick = function(e) {
            if (e.target === this) {
                document.body.removeChild(this);
            }
        };
        
        modal.innerHTML = `
            <div class="relative bg-white p-6 rounded-lg max-w-4xl max-h-full overflow-auto shadow-xl">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-900">${title}</h3>
                    <button onclick="document.body.removeChild(this.closest('.fixed'))" class="text-gray-400 hover:text-gray-600 p-2 rounded-full hover:bg-gray-100 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="text-center">
                    <img src="${imageSrc}" alt="${title}" class="max-w-full max-h-96 mx-auto rounded-lg shadow-lg">
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
    }

    // Initialize with 'all' filter active
    document.addEventListener('DOMContentLoaded', function() {
        filterTasks('all');
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey || e.metaKey) {
            switch(e.key) {
                case '1':
                    e.preventDefault();
                    filterTasks('all');
                    break;
                case '2':
                    e.preventDefault();
                    filterTasks('changes');
                    break;
                case '3':
                    e.preventDefault();
                    filterTasks('both-completed');
                    break;
                case '4':
                    e.preventDefault();
                    filterTasks('missing-exit');
                    break;
                case '5':
                    e.preventDefault();
                    filterTasks('issues');
                    break;
            }
        }
    });

    // Add tooltips for keyboard shortcuts
    document.querySelectorAll('.task-filter-btn').forEach((btn, index) => {
        btn.title = `Keyboard shortcut: Ctrl+${index + 1}`;
    });
</script>
@endsection