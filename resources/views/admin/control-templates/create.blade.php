@extends('components.layouts.app')

@section('title', 'Create Control Template')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-lg shadow">
        <!-- Header Section -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">Create New Control Template</h3>
            <div class="flex space-x-2">
                <a href="{{ route('admin.truck-templates.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-blue-300 hover:bg-blue-50 text-blue-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Manage Truck Templates
                </a>
                <a href="{{ route('admin.control-templates.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Templates
                </a>
            </div>
        </div>
        
        <!-- Form Content Container -->
        <div class="p-6">
            <form action="{{ route('admin.control-templates.store') }}" method="POST" id="template-form">
                @csrf
                
                <!-- Template Information -->
                <div class="space-y-6">
                    <div>
                        <h4 class="text-md font-medium text-gray-900 mb-4">Template Information</h4>
                        
                        <!-- Template Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Template Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   placeholder="e.g., Standard Vehicle Check Template"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Template Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3"
                                      placeholder="Describe what this template is used for...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active') ? 'checked' : '' }}>
                                <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                    Set as Active Template
                                    <span class="text-xs text-gray-500 block">Only one template can be active at a time. This will deactivate other templates.</span>
                                </label>
                            </div>
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tasks Section -->
                    <div class="space-y-6">
                        <div>
                            <!-- Tasks Header with Add Button -->
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-md font-medium text-gray-900">Template Tasks</h4>
                                <button type="button" id="add-task" class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Add Task
                                </button>
                            </div>

                            <!-- Truck Template Reference Gallery -->
                            <div id="templates-reference" class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <h5 class="text-sm font-medium text-blue-900 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Reference Templates - Use these numbers in your task descriptions
                                </h5>
                                <div id="templates-gallery" class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 gap-4">
                                    <!-- Templates will be loaded here via JavaScript -->
                                </div>
                            </div>

                            <!-- Tasks Container -->
                            <div id="tasks-container" class="space-y-4">
                                <!-- Tasks will be added here dynamically -->
                            </div>

                            <!-- Task Template (Hidden - Used by JavaScript) -->
                            <div id="task-template" class="hidden">
                                <div class="task-item p-4 border border-gray-200 rounded-lg bg-gray-50">
                                    <div class="flex justify-between items-start mb-3">
                                        <h5 class="font-medium text-gray-900 flex items-center">
                                            <span class="task-number bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded-full mr-2">1</span>
                                            Task Details
                                        </h5>
                                        <button type="button" class="remove-task text-red-600 hover:text-red-800 p-1 rounded hover:bg-red-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <div class="space-y-4">
                                        <!-- Task Title -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Task Title <span class="text-red-500">*</span></label>
                                            <input type="text" 
                                                   class="task-title w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                                   placeholder="e.g., Check front damage - Point 1, 2, 3">
                                        </div>

                                        <!-- Task Description -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                            <textarea class="task-description w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                                      rows="2"
                                                      placeholder="Inspect reference points as shown in template above..."></textarea>
                                        </div>

                                        <!-- Task Type and Required in same row -->
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Task Type</label>
                                                <select class="task-type w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                    <option value="check">Check</option>
                                                    <option value="inspect">Inspect</option>
                                                    <option value="document">Document</option>
                                                    <option value="report">Report</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                                <div class="flex items-center mt-2">
                                                    <input type="checkbox" 
                                                           class="task-required h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                                           checked>
                                                    <label class="ml-2 block text-sm text-gray-700">Required Task</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Template Reference Section -->
                                        <div class="border-t pt-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-3">Reference Template <span class="text-xs text-gray-500">(Optional)</span></label>
                                            
                                            <!-- No Template Option -->
                                            <div class="mb-3">
                                                <label class="flex items-center cursor-pointer p-2 border border-gray-200 rounded-md hover:bg-gray-50">
                                                    <input type="radio" 
                                                           class="task-template-radio h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" 
                                                           value=""
                                                           checked>
                                                    <span class="ml-2 text-sm text-gray-700">No Template Reference</span>
                                                </label>
                                            </div>

                                            <!-- Template Options -->
                                            <div class="template-options grid grid-cols-2 gap-2 mb-3">
                                                <!-- Template options will be populated via JavaScript -->
                                            </div>

                                            <!-- Reference Number Input -->
                                            <div class="template-number-container" style="display: none;">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Reference Point Number</label>
                                                <input type="number" 
                                                       class="task-template-number w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                                       placeholder="e.g., 1, 2, 3..."
                                                       min="1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Add Buttons -->
                            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                <h5 class="text-sm font-medium text-gray-700 mb-3">Quick Add Common Tasks:</h5>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                    <button type="button" class="quick-add-task px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-800 text-sm rounded-md transition-colors" 
                                            data-title="Check registration papers" 
                                            data-description="Verify vehicle registration documents are present and valid" 
                                            data-type="document">
                                        Registration Papers
                                    </button>
                                    <button type="button" class="quick-add-task px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-800 text-sm rounded-md transition-colors" 
                                            data-title="Inspect front damage" 
                                            data-description="Check front section for any scratches, dents, or damage" 
                                            data-type="inspect">
                                        Front Damage Check
                                    </button>
                                    <button type="button" class="quick-add-task px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-800 text-sm rounded-md transition-colors" 
                                            data-title="Inspect side damage" 
                                            data-description="Check side sections for any damage" 
                                            data-type="inspect">
                                        Side Damage Check
                                    </button>
                                    <button type="button" class="quick-add-task px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-800 text-sm rounded-md transition-colors" 
                                            data-title="Inspect back damage" 
                                            data-description="Check rear section for any damage" 
                                            data-type="inspect">
                                        Back Damage Check
                                    </button>
                                    <button type="button" class="quick-add-task px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-800 text-sm rounded-md transition-colors" 
                                            data-title="Check fuel level" 
                                            data-description="Record current fuel level" 
                                            data-type="check">
                                        Fuel Level
                                    </button>
                                    <button type="button" class="quick-add-task px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-800 text-sm rounded-md transition-colors" 
                                            data-title="Check lights and signals" 
                                            data-description="Test all lights, indicators, and warning signals" 
                                            data-type="check">
                                        Lights & Signals
                                    </button>
                                    <button type="button" class="quick-add-task px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-800 text-sm rounded-md transition-colors" 
                                            data-title="Check tire condition" 
                                            data-description="Inspect tire wear and pressure" 
                                            data-type="inspect">
                                        Tire Condition
                                    </button>
                                    <button type="button" class="quick-add-task px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-800 text-sm rounded-md transition-colors" 
                                            data-title="Interior cleanliness" 
                                            data-description="Check vehicle interior condition" 
                                            data-type="check">
                                        Interior Check
                                    </button>
                                </div>
                            </div>

                            <!-- Empty State -->
                            <div id="empty-state" class="text-center py-8 text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5l7-7 7 7M9 20h6m-7 4h7m0 0v5a2 2 0 002 2h14a2 2 0 002-2v-5M5 12a2 2 0 012-2h10a2 2 0 012 2v5a2 2 0 01-2 2H7a2 2 0 01-2-2v-5z"/>
                                </svg>
                                <p class="text-sm">No tasks added yet. Use the "Add Task" button or quick add options above.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.control-templates.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            Create Template
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let taskIndex = 0;
        let availableTemplates = [];
        const tasksContainer = document.getElementById('tasks-container');
        const taskTemplate = document.getElementById('task-template');
        const addTaskButton = document.getElementById('add-task');
        const templatesReference = document.getElementById('templates-reference');
        const templatesGallery = document.getElementById('templates-gallery');
        const emptyState = document.getElementById('empty-state');
    
        // Add task function
        function addTask(title = '', description = '', type = 'check', required = true) {
            const newTask = taskTemplate.cloneNode(true);
            newTask.id = '';
            newTask.classList.remove('hidden');
            
            // Update task number
            newTask.querySelector('.task-number').textContent = taskIndex + 1;
            
            // Set up proper form field names
            const titleInput = newTask.querySelector('.task-title');
            const descInput = newTask.querySelector('.task-description');
            const typeSelect = newTask.querySelector('.task-type');
            const requiredInput = newTask.querySelector('.task-required');
            const templateNumberInput = newTask.querySelector('.task-template-number');
            const templateNumberContainer = newTask.querySelector('.template-number-container');
            
            titleInput.name = `tasks[${taskIndex}][title]`;
            titleInput.required = true;
            descInput.name = `tasks[${taskIndex}][description]`;
            typeSelect.name = `tasks[${taskIndex}][task_type]`;
            requiredInput.name = `tasks[${taskIndex}][is_required]`;
            requiredInput.value = '1';
            templateNumberInput.name = `tasks[${taskIndex}][template_reference_number]`;
            
            // Set task index for template handling
            newTask.dataset.taskIndex = taskIndex;

            // Set values if provided
            if (title) titleInput.value = title;
            if (description) descInput.value = description;
            if (type) typeSelect.value = type;
            requiredInput.checked = required;

            // Populate template options
            populateTemplateOptions(newTask);

            // Handle "No Template" selection
            const noTemplateRadio = newTask.querySelector('input[type="radio"][value=""]');
            noTemplateRadio.name = `tasks[${taskIndex}][truck_template_id]`;
            noTemplateRadio.addEventListener('change', function() {
                if (this.checked) {
                    newTask.querySelectorAll('.template-option').forEach(opt => {
                        opt.classList.remove('border-blue-500', 'bg-blue-50');
                    });
                    templateNumberContainer.style.display = 'none';
                    templateNumberInput.value = '';
                }
            });
    
            // Add remove functionality
            newTask.querySelector('.remove-task').addEventListener('click', function() {
                newTask.remove();
                updateTaskNumbers();
                toggleEmptyState();
            });
    
            tasksContainer.appendChild(newTask);
            taskIndex++;
            toggleEmptyState();
        }

        // Populate template options for a specific task
        function populateTemplateOptions(taskItem) {
            const templateContainer = taskItem.querySelector('.template-options');
            const radioName = `tasks[${taskItem.dataset.taskIndex || 0}][truck_template_id]`;
            
            templateContainer.innerHTML = '';
            
            availableTemplates.forEach(template => {
                const optionDiv = document.createElement('div');
                optionDiv.className = 'template-option';
                
                optionDiv.innerHTML = `
                    <label class="flex items-center cursor-pointer p-2 border border-gray-200 rounded-md hover:bg-gray-50 transition-colors">
                        <input type="radio" 
                               class="task-template-radio h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" 
                               name="${radioName}" 
                               value="${template.id}">
                        <div class="ml-2 flex-1">
                            <div class="flex items-center">
                                <img src="/storage/${template.image_path}" 
                                     alt="${template.name}"
                                     class="w-8 h-8 object-cover rounded mr-2">
                                <div>
                                    <div class="font-medium text-sm text-gray-900">${template.name}</div>
                                    <div class="text-xs text-gray-500">${template.view_type} - ${template.number_points} pts</div>
                                </div>
                            </div>
                        </div>
                    </label>
                `;
                
                const radio = optionDiv.querySelector('input[type="radio"]');
                const label = optionDiv.querySelector('label');
                
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        // Remove selected class from all options
                        taskItem.querySelectorAll('.template-option label').forEach(opt => {
                            opt.classList.remove('border-blue-500', 'bg-blue-50');
                        });
                        
                        // Add selected class to this option
                        label.classList.add('border-blue-500', 'bg-blue-50');
                        
                        // Show template number input
                        const templateNumberContainer = taskItem.querySelector('.template-number-container');
                        templateNumberContainer.style.display = 'block';
                    }
                });
                
                templateContainer.appendChild(optionDiv);
            });
        }

        // Update task numbers after removal
        function updateTaskNumbers() {
            const tasks = tasksContainer.querySelectorAll('.task-item');
            tasks.forEach((task, index) => {
                task.querySelector('.task-number').textContent = index + 1;
                
                const titleInput = task.querySelector('.task-title');
                const descInput = task.querySelector('.task-description');
                const typeSelect = task.querySelector('.task-type');
                const requiredInput = task.querySelector('.task-required');
                const templateNumberInput = task.querySelector('.task-template-number');
                
                titleInput.name = `tasks[${index}][title]`;
                descInput.name = `tasks[${index}][description]`;
                typeSelect.name = `tasks[${index}][task_type]`;
                requiredInput.name = `tasks[${index}][is_required]`;
                templateNumberInput.name = `tasks[${index}][template_reference_number]`;
                
                task.dataset.taskIndex = index;
                const templateRadios = task.querySelectorAll('input[name*="truck_template_id"]');
                templateRadios.forEach(radio => {
                    radio.name = `tasks[${index}][truck_template_id]`;
                });
            });
            
            taskIndex = tasks.length;
        }

        // Toggle empty state visibility
        function toggleEmptyState() {
            const hasTasks = tasksContainer.querySelectorAll('.task-item').length > 0;
            emptyState.style.display = hasTasks ? 'none' : 'block';
        }
    
        // Load and display templates
        function loadTemplates() {
            fetch(`{{ route('admin.truck-templates.api') }}`)
                .then(response => response.json())
                .then(data => {
                    availableTemplates = data;
                    displayTemplates();
                })
                .catch(error => {
                    console.error('Error loading templates:', error);
                });
        }
    
        // Display templates in reference gallery
        function displayTemplates() {
            templatesGallery.innerHTML = '';
            
            if (availableTemplates.length === 0) {
                templatesReference.style.display = 'none';
                return;
            }
    
            availableTemplates.forEach(template => {
                const templateCard = document.createElement('div');
                templateCard.className = 'border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow';
                
                templateCard.innerHTML = `
                    <div class="aspect-w-16 aspect-h-9 bg-gray-100">
                        <img src="/storage/${template.image_path}" 
                             alt="${template.name}"
                             class="w-full h-32 object-cover cursor-pointer hover:scale-105 transition-transform duration-200"
                             onclick="openImagePreview('/storage/${template.image_path}', '${template.name}')">
                    </div>
                    <div class="p-3">
                        <h5 class="font-medium text-gray-900 text-sm">${template.name}</h5>
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-xs text-gray-500 capitalize">${template.view_type}</span>
                            <span class="text-xs font-medium text-blue-600">${template.number_points} points</span>
                        </div>
                    </div>
                `;
                
                templatesGallery.appendChild(templateCard);
            });
        }
    
        // Image preview function
        window.openImagePreview = function(imageSrc, title) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4';
            modal.onclick = function(e) {
                if (e.target === this) {
                    document.body.removeChild(this);
                }
            };
            
            modal.innerHTML = `
                <div class="relative bg-white p-6 rounded-lg max-w-4xl max-h-full overflow-auto">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">${title}</h3>
                        <button onclick="document.body.removeChild(this.closest('.fixed'))" class="text-gray-400 hover:text-gray-600 p-1 rounded hover:bg-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <img src="${imageSrc}" alt="${title}" class="max-w-full max-h-96 mx-auto rounded shadow-lg">
                    <p class="text-center text-sm text-gray-600 mt-4">Reference template for inspection points</p>
                </div>
            `;
            
            document.body.appendChild(modal);
        };
    
        // Event Listeners
        addTaskButton.addEventListener('click', function() {
            addTask();
        });
    
        // Quick add buttons
        document.querySelectorAll('.quick-add-task').forEach(button => {
            button.addEventListener('click', function() {
                const title = this.dataset.title;
                const description = this.dataset.description;
                const type = this.dataset.type;
                addTask(title, description, type, true);
            });
        });
    
        // Load templates on page load
        loadTemplates();
        
        // Show empty state initially
        toggleEmptyState();
    
        // Form validation
        document.getElementById('template-form').addEventListener('submit', function(e) {
            const tasks = tasksContainer.querySelectorAll('.task-item');
            let hasValidTask = false;
            
            tasks.forEach(task => {
                const titleInput = task.querySelector('.task-title');
                if (titleInput.value.trim() !== '') {
                    hasValidTask = true;
                }
            });
            
            if (!hasValidTask) {
                e.preventDefault();
                alert('Please add at least one task with a title.');
                return false;
            }
        });
    });
</script>
@endsection