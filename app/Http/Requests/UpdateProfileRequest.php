<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'full_name' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:500',
            'gender' => 'nullable|string|in:male,female,other',
            'date_of_birth' => 'nullable|date|before:today',
            'height' => 'nullable|numeric|min:0|max:300',
            'weight' => 'nullable|numeric|min:0|max:500',
            'primary_sport' => 'nullable|string|max:100',
            'fitness_level' => 'nullable|string|in:beginner,intermediate,advanced,elite',
        ];
    }
}
