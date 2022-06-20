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
            'price' => 'sometimes|numeric',
            'stock' => 'sometimes|integer',
            'min_order' => 'sometimes|integer',
            'is_new' => 'sometimes|boolean',
            'weight' => 'sometimes|integer',
            'description' => 'sometimes|string',
        ];
    }
}
