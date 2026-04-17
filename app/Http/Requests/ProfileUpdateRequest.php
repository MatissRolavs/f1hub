<?php

namespace App\Http\Requests;

use App\Models\Standing;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'favorite_constructor_id' => ['nullable', 'integer', 'exists:constructors,id'],
            'favorite_driver_id' => [
                'nullable',
                'integer',
                'exists:drivers,id',
                function ($attribute, $value, $fail) {
                    $constructorId = $this->input('favorite_constructor_id');
                    if (!$constructorId) {
                        $fail('Select a favorite team before picking a driver.');
                        return;
                    }
                    $exists = Standing::where('season', now()->year)
                        ->where('driver_id', $value)
                        ->where('constructor_id', $constructorId)
                        ->exists();
                    if (!$exists) {
                        $fail('Selected driver does not race for that team this season.');
                    }
                },
            ],
        ];
    }
}
