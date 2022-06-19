<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Traits\RequestService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use RequestService;

    protected $storeUri;
    public function __construct()
    {
        $this->storeUri = env('STORE_SERVICE_URL');
    }

    /**
     * Display a listing of the resource.
     *
     * @return LengthAwarePaginator
     */
    public function index(): LengthAwarePaginator
    {
        // Paginate products result, default 10 data/page
        $products = Product::with('productCategories')
            ->paginate(request()->get('per_page', 10));

        // Get store data based on store id, and assign it to product
        foreach ($products as $product) {
            $store = $this->request('GET', $this->storeUri, '/'.$product->id);
            $product->store = json_decode($store);
        }
        return $products;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            $msg ='Product not found';
            return $this->errorResponse($msg, 404);
        }
        return $product;
    }

    /**
     * Get products by specific store.
     *
     * @param int $store_id
     * @return LengthAwarePaginator
     */
    public function productsByStore(int $store_id): LengthAwarePaginator
    {
        return Product::with('productCategories')
            ->where('store_id', $store_id)
            ->paginate(
                request()->get('per_page', 10)
            );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Product::find($id)->delete();
    }
}
