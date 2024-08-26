<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;

class RegisterAddressRequest extends FormRequest
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
        return ['id_user' => 'required|integer', 'street' => 'required|string', 'city' => 'required|string', 'state' => 'required|string', 'zip_code' => 'required|string', 'country' => 'required|string'];
    }
}
