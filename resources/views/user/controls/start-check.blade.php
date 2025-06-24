@extends('components.layouts.app')

@section('title', 'START Check')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-medium text-gray-900">START Check - Control #{{ $controlLine->id }}</h3>
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
        <div class="px-6 py-4 bg-blue-50 border-b border-gray-200">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h4 class="text-sm font-medium text-blue-900">START Check Instructions</h4>
                    <p class="text-sm text-blue-700 mt-1">
                        Complete all required tasks before taking the vehicle. Check each item carefully and report any issues or damage. 
                        You can attach photos as evidence for any problems found. For tasks with template references, use the specified template points for damage area reporting.
                    </p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <form action="{{ route('user.controls.start.submit', $controlLine) }}" method="POST" enctype="multipart/form-data" id="start-check-form">
                @csrf
                
                @if($controlLine->tasks->count() > 0)
                    <div class="space-y-6">
                        @foreach($controlLine->tasks as $index => $task)
                            <div class="bg-gray-50 rounded-lg p-6 task-card" data-task-id="{{ $task->id }}">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mr-4">
                                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center text-white font-bold">
                                                {{ $index + 1 }}
                                            </div>
                                        </div>
                                        
                                        <div class="flex-1">
                                            <h4 class="text-lg font-medium text-gray-900">{{ $task->title }}</h4>
                                            @if($task->description)
                                                <p class="text-sm text-gray-600 mt-1">{{ $task->description }}</p>
                                            @endif
                                            
                                            <!-- Display Admin-Set Template Reference (Read-Only) -->
                                            @if($task->truck_template_id && $task->truckTemplate)
                                                <div class="flex items-center bg-blue-50 border border-blue-200 rounded-lg p-3 mt-3">
                                                    <svg class="w-4 h-4 text-blue-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center">
                                                            <span class="text-sm font-medium text-blue-900">{{ $task->truckTemplate->name }}</span>
                                                            @if($task->template_reference_number)
                                                                <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                    Reference Point #{{ $task->template_reference_number }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="text-xs text-blue-700">{{ ucfirst($task->truckTemplate->view_type) }} view - Use this template for damage area reporting</div>
                                                    </div>
                                                    @if($task->truckTemplate->image_path)
                                                        <button type="button" 
                                                                onclick="openTemplatePreview('{{ asset('storage/' . $task->truckTemplate->image_path) }}', '{{ $task->truckTemplate->name }}', {{ $task->template_reference_number ?? 'null' }})"
                                                                class="ml-2 inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded">
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

                                <input type="hidden" name="tasks[{{ $index }}][task_id]" value="{{ $task->id }}">

                                <!-- Status Selection -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Task Status <span class="text-red-500">*</span></label>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                        <label class="relative flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <input type="radio" name="tasks[{{ $index }}][status]" value="ok" class="sr-only" required>
                                            <div class="flex items-center">
                                                <div class="w-4 h-4 bg-green-500 rounded-full mr-3 flex items-center justify-center">
                                                    <svg class="w-3 h-3 text-white hidden check-icon" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                                <span class="text-sm font-medium text-gray-900">OK</span>
                                            </div>
                                        </label>

                                        <label class="relative flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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

                                        <label class="relative flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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

                                        <label class="relative flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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

                                <!-- Notes -->
                                <div class="mb-4">
                                    <label for="tasks[{{ $index }}][notes]" class="block text-sm font-medium text-gray-700 mb-2">
                                        Notes <span class="notes-required hidden text-red-500">*</span>
                                    </label>
                                    <textarea name="tasks[{{ $index }}][notes]" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent task-notes" 
                                              rows="3"
                                              placeholder="Add notes about this task..."></textarea>
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
                                                <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
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
                                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Complete START Check
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

<!-- Template Reference Images - Only show for tasks without specific template references -->
@php
    $tasksWithoutTemplates = $controlLine->tasks->filter(function($task) {
        return !$task->truck_template_id;
    });
    $availableTemplates = collect(); // You can load these if needed
@endphp

@if($tasksWithoutTemplates->count() > 0)
<div class="p-6">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Reference Templates - Use these area numbers for tasks without specific template references
            </h3>
        </div>
        <div class="p-6">
            <div id="check-templates-gallery" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Templates will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endif

<!-- Template Preview Modal -->
<div id="template-preview-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative bg-white p-4 rounded-lg max-w-5xl max-h-full overflow-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900" id="preview-title">Template Preview</h3>
            <button onclick="closeTemplatePreview()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="relative">
            <img id="preview-image" src="" alt="Template" class="max-w-full max-h-[80vh] mx-auto rounded">
            <div id="highlight-point" class="absolute bg-red-500 border-2 border-red-600 rounded-full opacity-75" style="width: 20px; height: 20px; display: none;"></div>
        </div>
        <p class="text-center text-sm text-gray-600 mt-4" id="preview-description">Use the numbered areas in this template for damage area reporting</p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('start-check-form');
    const taskCards = document.querySelectorAll('.task-card');
    const templatesGallery = document.getElementById('check-templates-gallery');

    // Load templates for reference (only if needed)
    function loadTemplatesReference() {
        if (!templatesGallery) return; // Skip if no gallery element
        
        // Get truck type from the page data or make an API call
        fetch('/admin/api/truck-templates')
            .then(response => response.json())
            .then(templates => {
                displayTemplatesReference(templates);
            })
            .catch(error => {
                console.error('Error loading templates:', error);
            });
    }

    // Display templates for reference
    function displayTemplatesReference(templates) {
        if (!templatesGallery) return;
        
        templatesGallery.innerHTML = '';
        
        if (templates.length === 0) {
            templatesGallery.innerHTML = '<p class="text-gray-500 text-center">No reference templates available</p>';
            return;
        }

        templates.forEach(template => {
            const templateCard = document.createElement('div');
            templateCard.className = 'border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm';
            
            templateCard.innerHTML = `
                <div class="aspect-w-16 aspect-h-9 bg-gray-100">
                    <img src="/storage/${template.image_path}" 
                         alt="${template.name}"
                         class="w-full h-48 object-cover cursor-pointer hover:scale-105 transition-transform duration-200"
                         onclick="openTemplatePreview('/storage/${template.image_path}', '${template.name}', null)">
                </div>
                <div class="p-4">
                    <h4 class="font-medium text-gray-900">${template.name}</h4>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-sm text-gray-500 capitalize">${template.view_type} View</span>
                        <span class="text-sm font-medium text-blue-600">${template.number_points} areas</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">Reference areas 1-${template.number_points} for damage reporting</p>
                </div>
            `;
            
            templatesGallery.appendChild(templateCard);
        });
    }

    // Template preview function
    window.openTemplatePreview = function(imageSrc, title, pointNumber) {
        const modal = document.getElementById('template-preview-modal');
        const previewTitle = document.getElementById('preview-title');
        const previewImage = document.getElementById('preview-image');
        const previewDescription = document.getElementById('preview-description');
        const highlightPoint = document.getElementById('highlight-point');
        
        previewTitle.textContent = title;
        previewImage.src = imageSrc;
        
        if (pointNumber) {
            previewDescription.textContent = `Template reference view - Point #${pointNumber} highlighted. Use the numbered areas for damage reporting.`;
            // You could add logic here to position the highlight point based on stored coordinates
        } else {
            previewDescription.textContent = 'Use the numbered areas in this template for damage area reporting';
        }
        
        modal.classList.remove('hidden');
    };

    window.closeTemplatePreview = function() {
        document.getElementById('template-preview-modal').classList.add('hidden');
    };

    // Close modal when clicking outside
    document.getElementById('template-preview-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            window.closeTemplatePreview();
        }
    });

    // Load templates on page load (only if gallery exists)
    if (templatesGallery) {
        loadTemplatesReference();
    }

    // Handle radio button changes
    taskCards.forEach(card => {
        const radioButtons = card.querySelectorAll('input[type="radio"]');
        const notesField = card.querySelector('.task-notes');
        const notesRequired = card.querySelector('.notes-required');
        const photoUpload = card.querySelector('.photo-upload');
        const fileInput = card.querySelector('input[type="file"]');
        const uploadedFiles = card.querySelector('.uploaded-files');

        radioButtons.forEach(radio => {
            // Handle both change and click events
            ['change', 'click'].forEach(eventType => {
                radio.addEventListener(eventType, function() {
                    // Update visual state
                    updateRadioVisuals(card, this.value);
                    
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
    });

    function updateRadioVisuals(card, selectedValue) {
        const labels = card.querySelectorAll('label:has(input[type="radio"])');
        labels.forEach(label => {
            const radio = label.querySelector('input[type="radio"]');
            const checkIcon = label.querySelector('.check-icon');
            
            if (radio && radio.value === selectedValue) {
                label.classList.add('bg-blue-50', 'border-blue-500', 'ring-2', 'ring-blue-200');
                label.classList.remove('border-gray-300');
                if (checkIcon) checkIcon.classList.remove('hidden');
                radio.checked = true;
            } else {
                label.classList.remove('bg-blue-50', 'border-blue-500', 'ring-2', 'ring-blue-200');
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