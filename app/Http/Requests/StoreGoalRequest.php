<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * StoreGoalRequest
 * Validates creating a new goal.
 * Demonstrates Laravel Form Requests, custom rules, and conditional validation.
 */
class StoreGoalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Auth middleware handles authorization
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'deadline'    => ['nullable', 'date', 'after:today'],  // Must be a future date
            'status'      => ['required', 'in:pending,in_progress,completed,cancelled'],
            'priority'    => ['required', 'in:low,medium,high,critical'],
            'progress'    => ['required', 'integer', 'min:0', 'max:100'], // 0–100
            'image'       => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,pdf,doc,docx', 'max:5120'], // 5MB max
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'    => 'Goal title is required.',
            'title.min'         => 'Title must be at least 3 characters.',
            'deadline.after'    => 'Deadline must be a future date.',
            'progress.min'      => 'Progress cannot be less than 0%.',
            'progress.max'      => 'Progress cannot exceed 100%.',
            'image.mimes'       => 'Uploaded file must be an image or document (jpg, png, gif, pdf, doc).',
            'image.max'         => 'File size cannot exceed 5MB.',
            'category_id.exists'=> 'Selected category does not exist.',
        ];
    }
}
