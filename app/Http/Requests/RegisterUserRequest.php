<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

//https://laravel.com/docs/11.x/validation#quick-writing-the-validation-logic
//https://laravel.com/docs/11.x/validation#form-request-validation

class RegisterUserRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'required|string|min:8'
        ];
    }
}
