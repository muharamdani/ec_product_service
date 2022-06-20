<?php

namespace App\Http\Services;

use App\Models\Product;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

class ProductService
{
    use \App\Traits\RequestService;

    protected $storeUri;
    public function __construct()
    {
        $this->storeUri = env('STORE_SERVICE_URI');
    }

    public function store($request)
    {
        $store_id = $request['store_id'];
        $request['is_active'] = 1;
        try {
            // Store action
            $store = $this->getStoreData($store_id);
            $total_product = $store->total_product;

            // product action
            $product = Product::create($request);
            $stock_prev = $product->stock;

            // Update store total product
            $updateStore = $this->updateTotalProduct(
                $stock_prev,
                $total_product,
                $store_id,
                $product->stock*2
            );

            $product->store = $updateStore;

            return $product;
        } catch (ClientException $e) {
            $err = json_decode($e->getResponse()->getBody()->getContents());
            return response()->json($err, $e->getCode());
        }
    }

    public function update($request, $product)
    {
        // Get store data
        $store_id = $product->store_id;
        $store = $this->getStoreData($store_id);

        // If product update contains stock
        // Update stock in store
        if (array_key_exists('stock', $request)) {
            // Get stock / total product data
            $total_product = $store->total_product;
            $stock_prev = $product->stock;
            $stock_after = $request['stock'];

            // Calculate stock
            $store = $this->updateTotalProduct($stock_prev, $total_product, $store_id,  $stock_after);
        }

        $product->update($request);
        $product->save();
        $product->store = $store;
        return $product;
    }

    public function updateTotalProduct($stock_prev, $store_total_product, $store_id, $stock_after)
    {
        $stock_update = $stock_after - $stock_prev;
        $store_total_product += $stock_update;

        // Update store total product
        $storeReq = [
            'total_product' => $store_total_product
        ];
        return json_decode($this->request('PUT', $this->storeUri, '/'.$store_id, $storeReq));
    }

    public function getStoreData($store_id)
    {
        try {
            $store = $this->request('GET', $this->storeUri, '/'.$store_id);
            return json_decode($store);
        } catch (ClientException $e) {
            $err = json_decode($e->getResponse()->getBody()->getContents());
            return response()->json($err, $e->getCode());
        }
    }
}
