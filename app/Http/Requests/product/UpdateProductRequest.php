<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'id_product' => 'required|integer',
            'name' => 'required|string',
            'price' => 'required|decimal',
            'company' => 'required|string',
            'type_id' => 'required|integer',
            'desc' => 'required|string',
            'stock' => 'required|integer',
        ];
    }
}
