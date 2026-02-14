<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
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
            'project_id' => [
                'required',
                'integer',
                Rule::exists('projects', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                }),
            ],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'points' => ['nullable', 'integer', 'min:1', 'max:100'],
            'priority' => ['nullable', Rule::in(['low', 'medium', 'high', 'urgent'])],
            'difficulty' => ['nullable', Rule::in(['easy', 'medium', 'hard', 'legendary'])],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
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
            'project_id.required' => 'Select a project for this task!',
            'project_id.exists' => 'That project doesn\'t exist or isn\'t yours.',
            'title.required' => 'Give your task a name!',
            'title.max' => 'Task name is too long (max 255 chars).',
            'points.min' => 'Points must be at least 1.',
            'points.max' => 'Max 100 points per task, bestie!',
            'due_date.after_or_equal' => 'Due date can\'t be in the past!',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if (! $this->has('points') || $this->points === null) {
            $this->merge(['points' => 10]);
        }

        if (! $this->has('priority') || $this->priority === null) {
            $this->merge(['priority' => 'medium']);
        }

        if (! $this->has('difficulty') || $this->difficulty === null) {
            $this->merge(['difficulty' => 'medium']);
        }
    }
}
