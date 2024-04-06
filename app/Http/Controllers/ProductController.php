<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        $products = Product::available()->latest()->paginate(6);
       return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->all());

        return $this->success('product created', $product);
    }
    public function show(Product $product)
    {
        return $this->response(new ProductResource($product));
    }
    public function update(UpdateProductRequest $request, Product $product)
    {
        if ($request->user()->isAdmin()) {
            $product->update($request->all());
        } else {
            $product->update($request->except('article'));
        }

        return $this->success('product updated', $product);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return $this->success('product deleted', $product);
    }
    
}
