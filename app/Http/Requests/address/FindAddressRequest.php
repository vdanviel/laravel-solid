<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;

class FindAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    
    //sobrescrevendo a função validationData() da classe do FormRequest neste Request para ele analisar a query get da url..
    public function validationData(): array
    {
        return $this->query();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return ['id_address' => 'required|integer'];
    }
}
