<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductService extends Service
{
    public function getProducts(Request $request)
    {
        $query = Product::query();
        if($request->user() && $request->user()->isAdmin()){
            if ($request->has('status')) {
                $status = $request->input('status');
                $query->where('status', $status);
            }
    
            if ($request->has('onlyTrashed')) {
                $query->onlyTrashed();
            }
            return $query->latest()->paginate(6);
        }
       return $query->available()->latest()->paginate(6);
    }

    public function deleteProduct($id) {
        
    $product = Product::withTrashed()->find($id);

    if ($product) {
        if ($product->trashed()) {
            $product->forceDelete();
        } else {
            $product->delete();
        }
    }

         return $product;
    }
}
