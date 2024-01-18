<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::all();

        $records = $products->map(function ($product) {
            return [
                'name' => $product->name,
                'price' => $product->price,
                'image_path' => $product->image_path,
            ];
        });

        return response()->json(['status' => 200, 'data' => $records]);
    }
}
