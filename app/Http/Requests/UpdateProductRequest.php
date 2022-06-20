<?php

namespace App\Http\Requests;

class UpdateProductRequest extends CreateProductRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'code' => 'sometimes|string',
            'name' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:1',
            'stock' => 'sometimes|numeric|min:0',
            'min_order' => 'sometimes|numeric|min:1',
            'is_new' => 'sometimes|boolean',
            'weight' => 'sometimes|numeric|min:1',
            'description' => 'sometimes|string',
        ];
    }
}
