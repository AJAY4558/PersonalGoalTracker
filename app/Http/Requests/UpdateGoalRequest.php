<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * UpdateGoalRequest
 * Validates updating an existing goal.
 * Same rules as StoreGoalRequest but deadline can be today or future.
 */
class UpdateGoalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'deadline'    => ['nullable', 'date', 'after_or_equal:today'],
            'status'      => ['required', 'in:pending,in_progress,completed,cancelled'],
            'priority'    => ['required', 'in:low,medium,high,critical'],
            'progress'    => ['required', 'integer', 'min:0', 'max:100'],
            'image'       => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,pdf,doc,docx', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'     => 'Goal title is required.',
            'deadline.after_or_equal' => 'Deadline must be today or a future date.',
            'progress.min'       => 'Progress cannot be less than 0%.',
            'progress.max'       => 'Progress cannot exceed 100%.',
        ];
    }
}
