<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'points' => ['nullable', 'integer', 'min:1', 'max:100'],
            'priority' => ['nullable', Rule::in(['low', 'medium', 'high', 'urgent'])],
            'difficulty' => ['nullable', Rule::in(['easy', 'medium', 'hard', 'legendary'])],
            'due_date' => ['nullable', 'date'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Give your task a name!',
            'title.max' => 'Task name is too long (max 255 chars).',
            'points.min' => 'Points must be at least 1.',
            'points.max' => 'Max 100 points per task, bestie!',
        ];
    }
}
