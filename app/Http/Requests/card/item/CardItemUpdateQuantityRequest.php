<?php

namespace App\Http\Requests\Card\Item;

use Illuminate\Foundation\Http\FormRequest;

class CardItemUpdateQuantityRequest extends FormRequest
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
            'item_card_id' => 'integer|required',
            'qnt' => 'integer|required'
        ];
    }
}
