@extends('components.layouts.app')

@section('title', 'EXIT Check')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-medium text-gray-900">EXIT Check - Control #{{ $controlLine->id }}</h3>
                <p class="text-sm text-gray-500">{{ $controlLine->truck->license_plate }} - {{ $controlLine->truck->make }} {{ $controlLine->truck->model }}</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('user.controls.show', $controlLine) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    View Details
                </a>
                <a href="{{ route('user.controls') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Controls
                </a>
            </div>
        </div>

        <!-- Instructions -->
        <div class="px-6 py-4 bg-red-50 border-b border-gray-200">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-red-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h4 class="text-sm font-medium text-red-900">EXIT Check Instructions</h4>
                    <p class="text-sm text-red-700 mt-1">
                        Complete all required tasks before returning the vehicle. Compare each item with the START check results. 
                        Report any new damage or issues that occurred during vehicle use. You can attach photos as evidence for damaged items.
                    </p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <form action="{{ route('user.controls.exit.submit', $controlLine) }}" method="POST" enctype="multipart/form-data" id="exit-check-form">
                @csrf
                
                @if($controlLine->tasks->count() > 0)
                    <div class="space-y-6">
                        @foreach($controlLine->tasks as $index => $task)
                            @php
                                $startCompletion = $task->startCompletion;
                            @endphp
                            <div class="bg-gray-50 rounded-lg p-6 task-card" data-task-id="{{ $task->id }}">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mr-4">
                                            <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center text-white font-bold">
                                                {{ $index + 1 }}
                                            </div>
                                        </div>
                                        
                                        <div class="flex-1">
                                            <h4 class="text-lg font-medium text-gray-900">{{ $task->title }}</h4>
                                            @if($task->description)
                                                <p class="text-sm text-gray-600 mt-1">{{ $task->description }}</p>
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

                                <!-- START Check Results -->
                                @if($startCompletion)
                                    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-md">
                                        <h5 class="text-sm font-medium text-blue-900 mb-2">START Check Result:</h5>
                                        <div class="flex items-center space-x-3">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                @if($startCompletion->status === 'ok') bg-green-100 text-green-800
                                                @elseif($startCompletion->status === 'issue') bg-yellow-100 text-yellow-800
                                                @elseif($startCompletion->status === 'missing') bg-orange-100 text-orange-800
                                                @elseif($startCompletion->status === 'damaged') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($startCompletion->status) }}
                                            </span>
                                            @if($startCompletion->notes)
                                                <span class="text-xs text-blue-700">{{ $startCompletion->notes }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="mb-4 p-3 bg-gray-100 border border-gray-300 rounded-md">
                                        <p class="text-sm text-gray-600">No START check data available</p>
                                    </div>
                                @endif

                                <input type="hidden" name="tasks[{{ $index }}][task_id]" value="{{ $task->id }}">

                                <!-- Status Selection -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Current Status <span class="text-red-500">*</span></label>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                        <label class="relative flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <input type="radio" name="tasks[{{ $index }}][status]" value="ok" class="sr-only" required
                                                   @if($startCompletion && $startCompletion->status === 'ok') checked @endif>
                                            <div class="flex items-center">
                                                <div class="w-4 h-4 bg-green-500 rounded-full mr-3 flex items-center justify-center">
                                                    <svg class="w-3 h-3 text-white hidden check-icon" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                                <span class="text-sm font-medium text-gray-900">OK</span>
                                            </div>
                                        </label>

                                        <label class="relative flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <input type="radio" name="tasks[{{ $index }}][status]" value="issue" class="sr-only">
                                            <div class="flex items-center">
                                                <div class="w-4 h-4 bg-yellow-500 rounded-full mr-3 flex items-center justify-center">
                                                    <svg class="w-3 h-3 text-white hidden check-icon" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                                <span class="text-sm font-medium text-gray-900">Issue</span>
                                            </div>
                                        </label>

                                        <label class="relative flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <input type="radio" name="tasks[{{ $index }}][status]" value="missing" class="sr-only">
                                            <div class="flex items-center">
                                                <div class="w-4 h-4 bg-orange-500 rounded-full mr-3 flex items-center justify-center">
                                                    <svg class="w-3 h-3 text-white hidden check-icon" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                                <span class="text-sm font-medium text-gray-900">Missing</span>
                                            </div>
                                        </label>

                                        <label class="relative flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <input type="radio" name="tasks[{{ $index }}][status]" value="damaged" class="sr-only">
                                            <div class="flex items-center">
                                                <div class="w-4 h-4 bg-red-500 rounded-full mr-3 flex items-center justify-center">
                                                    <svg class="w-3 h-3 text-white hidden check-icon" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                                <span class="text-sm font-medium text-gray-900">Damaged</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- New Issue Alert -->
                                <div class="new-issue-alert hidden mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                                    <div class="flex">
                                        <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <div>
                                            <h3 class="text-sm font-medium text-yellow-800">New Issue Detected</h3>
                                            <p class="text-sm text-yellow-700 mt-1">This item was OK during START check but now has issues. Please provide detailed explanation.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Notes -->
                                <div class="mb-4">
                                    <label for="tasks[{{ $index }}][notes]" class="block text-sm font-medium text-gray-700 mb-2">
                                        Notes <span class="notes-required hidden text-red-500">*</span>
                                    </label>
                                    <textarea name="tasks[{{ $index }}][notes]" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent task-notes" 
                                              rows="3"
                                              placeholder="Add notes about this task...">@if($startCompletion && $startCompletion->status === 'ok'){{ $startCompletion->notes }}@endif</textarea>
                                </div>

                                <!-- Photo Upload (only for damaged status) -->
                                <div class="photo-upload hidden">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Upload Photos <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label class="relative cursor-pointer bg-white rounded-md font-medium text-red-600 hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-red-500">
                                                    <span>Upload photos</span>
                                                    <input type="file" name="tasks[{{ $index }}][attachments][]" class="sr-only" multiple accept="image/*,application/pdf" data-task="{{ $index }}">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, PDF up to 10MB each</p>
                                        </div>
                                    </div>
                                    <div class="uploaded-files mt-2"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('user.controls') }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Complete EXIT Check
                            </button>
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No tasks found</h3>
                        <p class="text-gray-500">This control doesn't have any tasks assigned yet.</p>
                        <div class="mt-6">
                            <a href="{{ route('user.controls') }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                                Back to Controls
                            </a>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('exit-check-form');
    const taskCards = document.querySelectorAll('.task-card');

    // Handle radio button changes
    taskCards.forEach(card => {
        const radioButtons = card.querySelectorAll('input[type="radio"]');
        const notesField = card.querySelector('.task-notes');
        const notesRequired = card.querySelector('.notes-required');
        const photoUpload = card.querySelector('.photo-upload');
        const fileInput = card.querySelector('input[type="file"]');
        const uploadedFiles = card.querySelector('.uploaded-files');
        const newIssueAlert = card.querySelector('.new-issue-alert');

        // Get START check status from the page
        const startStatus = getStartCheckStatus(card);

        radioButtons.forEach(radio => {
            // Handle both change and click events
            ['change', 'click'].forEach(eventType => {
                radio.addEventListener(eventType, function() {
                    // Update visual state
                    updateRadioVisuals(card, this.value);
                    
                    // Check if this is a new issue compared to START check
                    const isNewIssue = startStatus === 'ok' && (this.value === 'issue' || this.value === 'damaged' || this.value === 'missing');
                    
                    if (isNewIssue) {
                        newIssueAlert.classList.remove('hidden');
                    } else {
                        newIssueAlert.classList.add('hidden');
                    }
                    
                    // Show/hide photo upload and make notes required for issues
                    if (this.value === 'issue' || this.value === 'missing') {
                        // For issue/missing: show notes as required
                        if (notesRequired) {
                            notesRequired.classList.remove('hidden');
                        }
                        if (notesField) {
                            notesField.required = true;
                        }
                        
                        // Hide photo upload for issue/missing
                        if (photoUpload) {
                            photoUpload.classList.add('hidden');
                        }
                        if (fileInput) {
                            fileInput.required = false;
                        }
                    } else if (this.value === 'damaged') {
                        // For damaged: show both notes and photo upload as required
                        if (notesRequired) {
                            notesRequired.classList.remove('hidden');
                        }
                        if (notesField) {
                            notesField.required = true;
                        }
                        if (photoUpload) {
                            photoUpload.classList.remove('hidden');
                        }
                        if (fileInput) {
                            fileInput.required = true;
                        }
                    } else {
                        // For OK: hide everything and make nothing required
                        if (photoUpload) {
                            photoUpload.classList.add('hidden');
                        }
                        if (notesRequired) {
                            notesRequired.classList.add('hidden');
                        }
                        if (notesField) {
                            notesField.required = false;
                        }
                        if (fileInput) {
                            fileInput.required = false;
                        }
                    }
                });
            });
        });

        // Also handle label clicks
        const labels = card.querySelectorAll('label:has(input[type="radio"])');
        labels.forEach(label => {
            label.addEventListener('click', function(e) {
                e.preventDefault();
                const radio = this.querySelector('input[type="radio"]');
                if (radio) {
                    // Uncheck all radios in this group first
                    radioButtons.forEach(r => r.checked = false);
                    // Check the clicked one
                    radio.checked = true;
                    // Trigger the change event
                    radio.dispatchEvent(new Event('change'));
                }
            });
        });

        // Handle file uploads
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                displayUploadedFiles(this, uploadedFiles);
            });
        }

        // Set initial state based on START check
        if (startStatus === 'ok') {
            const okRadio = card.querySelector('input[value="ok"]');
            if (okRadio) {
                okRadio.checked = true;
                updateRadioVisuals(card, 'ok');
            }
        }
    });

    function getStartCheckStatus(card) {
        const startResultElement = card.querySelector('.bg-blue-50 .px-2');
        if (startResultElement) {
            const text = startResultElement.textContent.toLowerCase();
            if (text.includes('ok')) return 'ok';
            if (text.includes('issue')) return 'issue';
            if (text.includes('damaged')) return 'damaged';
            if (text.includes('missing')) return 'missing';
        }
        return null;
    }

    function updateRadioVisuals(card, selectedValue) {
        const labels = card.querySelectorAll('label:has(input[type="radio"])');
        labels.forEach(label => {
            const radio = label.querySelector('input[type="radio"]');
            const checkIcon = label.querySelector('.check-icon');
            
            if (radio && radio.value === selectedValue) {
                label.classList.add('bg-red-50', 'border-red-500', 'ring-2', 'ring-red-200');
                label.classList.remove('border-gray-300');
                if (checkIcon) checkIcon.classList.remove('hidden');
                radio.checked = true;
            } else {
                label.classList.remove('bg-red-50', 'border-red-500', 'ring-2', 'ring-red-200');
                label.classList.add('border-gray-300');
                if (checkIcon) checkIcon.classList.add('hidden');
                if (radio) radio.checked = false;
            }
        });
    }

    function displayUploadedFiles(input, container) {
        container.innerHTML = '';
        
        if (input.files.length > 0) {
            const fileList = document.createElement('div');
            fileList.className = 'space-y-2';
            
            Array.from(input.files).forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'flex items-center justify-between p-2 bg-gray-100 rounded';
                
                fileItem.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">${file.name}</span>
                        <span class="text-xs text-gray-500 ml-2">(${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
                    </div>
                    <button type="button" class="text-red-600 hover:text-red-800" onclick="removeFile(this, ${index})">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                `;
                
                fileList.appendChild(fileItem);
            });
            
            container.appendChild(fileList);
        }
    }

    // Form validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        const errors = [];

        taskCards.forEach((card, index) => {
            const selectedRadio = card.querySelector('input[type="radio"]:checked');
            const notesField = card.querySelector('.task-notes');
            const fileInput = card.querySelector('input[type="file"]');
            const taskTitle = card.querySelector('h4').textContent;

            // Check if status is selected
            if (!selectedRadio) {
                isValid = false;
                errors.push(`Please select a status for task: ${taskTitle}`);
                card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            // Check if notes are required and provided (for issue, missing, damaged)
            if ((selectedRadio.value === 'issue' || selectedRadio.value === 'missing' || selectedRadio.value === 'damaged') && 
                notesField && notesField.required && !notesField.value.trim()) {
                isValid = false;
                errors.push(`Please provide notes for task: ${taskTitle}`);
                notesField.focus();
                return;
            }

            // Check if photos are required and provided (only for damaged)
            if (selectedRadio.value === 'damaged' && fileInput && fileInput.required && fileInput.files.length === 0) {
                isValid = false;
                errors.push(`Please upload photos for damaged task: ${taskTitle}`);
                return;
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Please complete all required fields:\n\n' + errors.join('\n'));
        } else {
            // Show confirmation for completing control
            if (!confirm('Are you sure you want to complete the EXIT check? This will mark the entire control as completed and cannot be undone.')) {
                e.preventDefault();
                return;
            }

            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing...
            `;
        }
    });

    // Global function to remove files
    window.removeFile = function(button, index) {
        const card = button.closest('.task-card');
        const fileInput = card.querySelector('input[type="file"]');
        const uploadedFiles = card.querySelector('.uploaded-files');
        
        // Create new FileList without the removed file
        const dt = new DataTransfer();
        const files = Array.from(fileInput.files);
        
        files.forEach((file, i) => {
            if (i !== index) {
                dt.items.add(file);
            }
        });
        
        fileInput.files = dt.files;
        displayUploadedFiles(fileInput, uploadedFiles);
    };
});
</script>
@endsection