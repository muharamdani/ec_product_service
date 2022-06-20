<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'store_id' => 'required|integer',
            'code' => 'sometimes|string',
            'name' => 'required|string',
            'price' => 'required|numeric|min:1',
            'stock' => 'required|numeric|min:0',
            'min_order' => 'required|numeric|min:1',
            'is_new' => 'required|boolean',
            'weight' => 'required|numeric|min:1',
            'description' => 'sometimes|string',
        ];
    }
}
