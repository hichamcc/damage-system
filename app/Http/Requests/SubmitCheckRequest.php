<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitCheckRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled in controller
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'tasks' => 'required|array|min:1',
            'tasks.*.status' => 'required|in:ok,issue,missing,damaged',
            'tasks.*.notes' => 'nullable|string|max:1000',
            'tasks.*.damage_area' => 'nullable|string|max:255',
            'tasks.*.photos' => 'nullable|array|max:5', // Max 5 photos per task
            'tasks.*.photos.*' => 'image|mimes:jpeg,png,jpg|max:10240', // 10MB max per photo
        ];

        // Add exit-specific rules
        if ($this->isMethod('post') && str_contains($this->route()->getName(), 'exit')) {
            $rules['fuel_level'] = 'nullable|string|max:50';
            $rules['final_mileage'] = 'nullable|integer|min:0|max:9999999';
            $rules['overall_notes'] = 'nullable|string|max:2000';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'tasks.required' => 'At least one task must be completed.',
            'tasks.*.status.required' => 'Task status is required.',
            'tasks.*.status.in' => 'Invalid task status selected.',
            'tasks.*.notes.max' => 'Task notes cannot exceed 1000 characters.',
            'tasks.*.damage_area.max' => 'Damage area cannot exceed 255 characters.',
            'tasks.*.photos.max' => 'Maximum 5 photos allowed per task.',
            'tasks.*.photos.*.image' => 'Uploaded file must be an image.',
            'tasks.*.photos.*.mimes' => 'Image must be JPEG, PNG, or JPG format.',
            'tasks.*.photos.*.max' => 'Image size cannot exceed 10MB.',
            'final_mileage.integer' => 'Mileage must be a valid number.',
            'final_mileage.min' => 'Mileage cannot be negative.',
            'overall_notes.max' => 'Overall notes cannot exceed 2000 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'tasks.*.status' => 'task status',
            'tasks.*.notes' => 'task notes',
            'tasks.*.damage_area' => 'damage area',
            'tasks.*.photos.*' => 'task photo',
            'fuel_level' => 'fuel level',
            'final_mileage' => 'final mileage',
            'overall_notes' => 'overall notes',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean up task data - remove empty entries
        if ($this->has('tasks')) {
            $tasks = collect($this->input('tasks'))->filter(function ($task) {
                return isset($task['status']) && !empty($task['status']);
            })->toArray();

            $this->merge(['tasks' => $tasks]);
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Custom validation logic
            $tasks = $this->input('tasks', []);
            
            foreach ($tasks as $taskId => $taskData) {
                // Validate that required tasks have status
                if (empty($taskData['status'])) {
                    $validator->errors()->add(
                        "tasks.{$taskId}.status", 
                        'Status is required for this task.'
                    );
                }

                // If status indicates issues, notes should be provided
                if (in_array($taskData['status'] ?? '', ['issue', 'missing', 'damaged']) 
                    && empty($taskData['notes'])) {
                    $validator->errors()->add(
                        "tasks.{$taskId}.notes", 
                        'Please provide details about the issue.'
                    );
                }

                // If status indicates damage, damage area is recommended
                if (in_array($taskData['status'] ?? '', ['damaged', 'missing']) 
                    && empty($taskData['damage_area'])) {
                    // This is a warning, not an error - we'll add a custom message
                    $validator->errors()->add(
                        "tasks.{$taskId}.damage_area", 
                        'Specifying damage area is recommended for damaged or missing items.'
                    );
                }
            }
        });
    }
}