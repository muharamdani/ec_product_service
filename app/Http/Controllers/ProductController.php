<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Services\ProductService;
use App\Models\Product;
use App\Traits\RequestService;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    use RequestService;

    protected $storeUri;
    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
        $this->storeUri = env('STORE_SERVICE_URI');
    }

    /**
     * Display a listing of the resource.
     *
     * @return LengthAwarePaginator
     */
    public function index(): LengthAwarePaginator
    {
        // Paginate products result, default 10 data/page
        if (env('API_GATEWAY_URI')) {
            $products = Product::paginate(request()->get('per_page', 10))
                ->setPath(env('API_GATEWAY_URI').'/api/products');
        } else {
            $products = Product::paginate(request()->get('per_page', 10));
        }

        // Get store data based on store id, and assign it to product
        foreach ($products as $product) {
            $store = $this->request('GET', $this->storeUri, '/'.$product->store_id);
            $product->store = json_decode($store);
        }
        return $products;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateProductRequest $request
     * @return Response
     */
    public function store(CreateProductRequest $request)
    {
        $req = $request->validated();
        return $this->productService->store($req);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            $msg ='Product not found';
            return $this->errorResponse($msg, 404);
        }

        $store_id = $product->store_id;
        $store = $this->request('GET', $this->storeUri, '/'.$store_id);
        $store = json_decode($store);
        $product['store'] = $store;
        return $product;
    }

    /**
     * Get products by specific store.
     *
     * @param int $store_id
     */
    public function productsByStore(int $store_id)
    {
        try {
            $store = $this->request('GET', $this->storeUri, '/'.$store_id);
            $store = json_decode($store);
        } catch (ClientException $e) {
            $err = json_decode($e->getResponse()->getBody()->getContents());
            return response()->json($err, $e->getCode());
        }

        if (env('API_GATEWAY_URI')) {
            $products = Product::where('store_id', $store_id)
                ->paginate(request()->get('per_page', 10))
                ->setPath(env('API_GATEWAY_URI').'/api/products');
        } else {
            $products = Product::where('store_id', $store_id)
                ->paginate(request()->get('per_page', 10));
        }

        $data = [
          'store' => $store,
          'paginate' => $products,
        ];
        return response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateProductRequest  $request
     * @param  int  $id
     * @return Response
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $req = $request->validated();
        $product = Product::find($id);
        if (!$product) {
            $msg ='Product not found';
            return $this->errorResponse($msg, 404);
        }
        return $this->productService->update($req, $product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            Product::find($id)->delete();
            return $this->successResponse(["message" => "Delete successful"], 200);
        } catch (\Throwable $e) {
            return $this->errorResponse("Product id not found", 404);
        }
    }
}
