@extends('components.layouts.app')

@section('title', 'Start Check')

@section('content')
<div class="p-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">Start Check (Check-In)</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $controlLine->truck->license_plate }} - {{ $controlLine->truck->make }} {{ $controlLine->truck->model }}</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 1.414L10.586 9.5 7.707 6.621a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Start Check
                </span>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('user.control.show', $controlLine) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Control
                </a>
            </div>
        </div>

        <!-- Control Info -->
        <div class="px-6 py-4 bg-green-50 border-b border-green-200">
            <div class="flex items-center space-x-4">
                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="font-medium text-green-900">{{ $controlLine->controlTemplate->name }}</h4>
                    <p class="text-sm text-green-700">Complete all tasks before taking the vehicle. Check each item carefully.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Start Check Form -->
    @php
        $hasStartCheck = $controlLine->tasks()
            ->whereHas('completions', function($query) {
                $query->where('check_type', 'start');
            })->exists();
    @endphp

    @if($hasStartCheck)
        <!-- Already Completed Message -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-6 py-8 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <svg class="h-8 w-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Start Check Already Completed</h3>
                <p class="text-gray-600 mb-4">This control's start check has already been completed.</p>
                <div class="flex justify-center space-x-3">
                    <a href="{{ route('user.control.show', $controlLine) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                        View Control Details
                    </a>
                    @if(!$controlLine->tasks()->whereHas('completions', function($query) { $query->where('check_type', 'exit'); })->exists())
                        <a href="{{ route('user.control.exit', $controlLine) }}" 
                           class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-md">
                            Proceed to Exit Check
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @else
        <!-- Start Check Form -->
    <form action="{{ route('user.controls.start.submit', $controlLine) }}" method="POST" enctype="multipart/form-data" id="start-check-form">
        @csrf
        
        <!-- Tasks Checklist -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h4 class="text-lg font-medium text-gray-900">Vehicle Inspection Tasks</h4>
                <p class="text-sm text-gray-600 mt-1">Complete all {{ $controlLine->tasks->count() }} tasks below</p>
            </div>
            
            <div class="divide-y divide-gray-100">
                @foreach($controlLine->tasks as $index => $task)
                    <div class="p-6" id="task-{{ $task->id }}">
                        <div class="flex items-start space-x-4">
                            <!-- Task Number -->
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center w-10 h-10 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                    {{ $task->sort_order }}
                                </span>
                            </div>

                            <!-- Task Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h5 class="text-lg font-medium text-gray-900 mb-2">{{ $task->title }}</h5>
                                        
                                        @if($task->description)
                                            <p class="text-gray-600 mb-4">{{ $task->description }}</p>
                                        @endif

                                        <!-- Task Meta -->
                                        <div class="flex flex-wrap items-center gap-3 mb-4">
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
                                        </div>

                                        <!-- Task Completion Form -->
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <div class="space-y-4">
                                                <!-- Task Status -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Task Status <span class="text-red-500">*</span></label>
                                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                                        <label class="flex items-center cursor-pointer p-3 border border-gray-200 rounded-md hover:bg-gray-50">
                                                            <input type="radio" 
                                                                   name="tasks[{{ $task->id }}][status]" 
                                                                   value="ok" 
                                                                   class="task-status h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300"
                                                                   {{ $task->is_required ? 'required' : '' }}>
                                                            <span class="ml-2 flex items-center">
                                                                <svg class="w-4 h-4 text-green-600 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                                </svg>
                                                                OK / Good
                                                            </span>
                                                        </label>
                                                        <label class="flex items-center cursor-pointer p-3 border border-gray-200 rounded-md hover:bg-gray-50">
                                                            <input type="radio" 
                                                                   name="tasks[{{ $task->id }}][status]" 
                                                                   value="issue" 
                                                                   class="task-status h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300"
                                                                   {{ $task->is_required ? 'required' : '' }}>
                                                            <span class="ml-2 flex items-center">
                                                                <svg class="w-4 h-4 text-yellow-600 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                                </svg>
                                                                Issue
                                                            </span>
                                                        </label>
                                                        <label class="flex items-center cursor-pointer p-3 border border-gray-200 rounded-md hover:bg-gray-50">
                                                            <input type="radio" 
                                                                   name="tasks[{{ $task->id }}][status]" 
                                                                   value="missing" 
                                                                   class="task-status h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300"
                                                                   {{ $task->is_required ? 'required' : '' }}>
                                                            <span class="ml-2 flex items-center">
                                                                <svg class="w-4 h-4 text-orange-600 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                                                </svg>
                                                                Missing
                                                            </span>
                                                        </label>
                                                        <label class="flex items-center cursor-pointer p-3 border border-gray-200 rounded-md hover:bg-gray-50">
                                                            <input type="radio" 
                                                                   name="tasks[{{ $task->id }}][status]" 
                                                                   value="damaged" 
                                                                   class="task-status h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300"
                                                                   {{ $task->is_required ? 'required' : '' }}>
                                                            <span class="ml-2 flex items-center">
                                                                <svg class="w-4 h-4 text-red-600 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                                </svg>
                                                                Damaged
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- Damage Area (for issues) -->
                                                <div class="damage-area-container" style="display: none;">
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Damage Area/Points</label>
                                                    <input type="text" 
                                                           name="tasks[{{ $task->id }}][damage_area]" 
                                                           class="damage-area w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                                           placeholder="e.g., 1, 3-5, 8 (reference template points)">
                                                    <p class="text-xs text-gray-500 mt-1">Specify damaged areas using template reference numbers</p>
                                                </div>

                                                <!-- Task Notes -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                                    <textarea name="tasks[{{ $task->id }}][notes]" 
                                                              rows="3" 
                                                              class="task-notes w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                                              placeholder="Add any observations, measurements, or details..."></textarea>
                                                </div>

                                                <!-- Photo Upload -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Photos (Optional)</label>
                                                    <div class="flex items-center justify-center w-full">
                                                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                                <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                                                </svg>
                                                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> photos</p>
                                                                <p class="text-xs text-gray-500">PNG, JPG up to 10MB each</p>
                                                            </div>
                                                            <input type="file" 
                                                                   name="tasks[{{ $task->id }}][photos][]" 
                                                                   multiple 
                                                                   accept="image/*" 
                                                                   class="hidden task-photos" />
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Template Image -->
                                    @if($task->truckTemplate)
                                        <div class="flex-shrink-0 ml-4">
                                            <img src="{{ asset('storage/' . $task->truckTemplate->image_path) }}" 
                                                 alt="{{ $task->truckTemplate->name }}"
                                                 class="w-32 h-24 object-cover rounded border border-gray-200 cursor-pointer hover:scale-105 transition-transform shadow-sm"
                                                 onclick="openImagePreview('{{ asset('storage/' . $task->truckTemplate->image_path) }}', '{{ $task->truckTemplate->name }}')">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Form Actions -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Complete all required tasks before submitting
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('user.control.show', $controlLine) }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Complete Start Check
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image preview function
        window.openImagePreview = function(imageSrc, title) {
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
                    <img src="${imageSrc}" alt="${title}" class="max-w-full max-h-96 mx-auto rounded-lg shadow-lg">
                    <p class="text-center text-sm text-gray-600 mt-4">Reference template for inspection points</p>
                </div>
            `;
            
            document.body.appendChild(modal);
        };

        // Form validation
        document.getElementById('start-check-form').addEventListener('submit', function(e) {
            const requiredTasks = document.querySelectorAll('input[required][type="radio"]');
            let allRequiredCompleted = true;
            
            // Group required radio buttons by name
            const requiredGroups = {};
            requiredTasks.forEach(radio => {
                const name = radio.name;
                if (!requiredGroups[name]) {
                    requiredGroups[name] = [];
                }
                requiredGroups[name].push(radio);
            });
            
            // Check if each required group has a selection
            Object.keys(requiredGroups).forEach(groupName => {
                const group = requiredGroups[groupName];
                const hasSelection = group.some(radio => radio.checked);
                if (!hasSelection) {
                    allRequiredCompleted = false;
                }
            });
            
            if (!allRequiredCompleted) {
                e.preventDefault();
                alert('Please complete all required tasks before submitting.');
                return false;
            }
        });

        // Auto-scroll to task when status changes
        document.querySelectorAll('.task-status').forEach(radio => {
            radio.addEventListener('change', function() {
                const taskDiv = this.closest('[id^="task-"]');
                const damageAreaContainer = taskDiv.querySelector('.damage-area-container');
                const notesField = taskDiv.querySelector('.task-notes');
                
                if (this.value === 'damaged' || this.value === 'missing' || this.value === 'issue') {
                    // Show damage area input
                    damageAreaContainer.style.display = 'block';
                    
                    if (notesField) {
                        notesField.focus();
                        notesField.placeholder = 'Please describe the issue in detail...';
                    }
                } else {
                    // Hide damage area input
                    damageAreaContainer.style.display = 'none';
                    const damageAreaInput = damageAreaContainer.querySelector('.damage-area');
                    if (damageAreaInput) {
                        damageAreaInput.value = '';
                    }
                }
            });
        });

        // File upload preview
        document.querySelectorAll('.task-photos').forEach(input => {
            input.addEventListener('change', function() {
                const files = this.files;
                if (files.length > 0) {
                    const label = this.closest('label');
                    const fileList = Array.from(files).map(file => file.name).join(', ');
                    label.querySelector('p').innerHTML = `<span class="font-semibold text-green-600">${files.length} file(s) selected:</span><br><span class="text-xs">${fileList}</span>`;
                }
            });
        });
    });
</script>
@endsection