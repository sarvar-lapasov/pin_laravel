<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService) {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    public function index(Request $request)
    {
        $products = $this->productService->getProducts($request);
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

    public function destroy($id)
    {
        $product = $this->productService->deleteProduct($id);

        return $this->success('product deleted', $product);
    }
    
    public function restore($id)
    {
        $product = Product::withTrashed()->find($id);
        $product->restore();
        return $this->success('product restored', $product);
    }
}
