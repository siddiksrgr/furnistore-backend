<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::with('images')->latest()->get();

        return response()->json([
            'success' => true,
            'data'    => $products
        ], 200);
    }

    public function show($id)
    {
        $product = Product::with('images')->where('slug', $id)->firstOrFail();

        if ($product) {
            return response()->json([
                'success'   => true,
                'message'   => 'Detail product',
                'data'      => $product
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product tidak ditemukan',
            ], 404);
        }
    }
}