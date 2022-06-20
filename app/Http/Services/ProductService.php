<?php

namespace App\Http\Services;

use App\Models\Product;
use GuzzleHttp\Exception\GuzzleException;

class ProductService
{
    use \App\Traits\RequestService;

    protected $storeUri;
    public function __construct()
    {
        $this->storeUri = env('STORE_SERVICE_URL');
    }

    public function store($request)
    {
        $store_id = $request['store_id'];
        $request['is_active'] = 1;
        try {
            // Store action
            $store = $this->request('GET', $this->storeUri, '/'.$store_id);
            $store = json_decode($store);

            // product action
            $product = Product::create($request);
            $product->store = $store;
            return $product;
        } catch (GuzzleException $e) {
            return response()->json([
                'error_code' => $e->getCode(),
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function update($request, $product)
    {
        $product->update($request);
        $product->save();
        return $product;
    }
}
