<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;

class CartsController extends Controller
{
    public function index()
    {
        $carts = Cart::with('product.images')->where('user_id', auth()->user()->id)->latest()->get();

        return response()->json([
            'success' => true,
            'message' =>'List Semua Cart',
            'data'    => $carts
        ], 200);
    }

    public function count()
    {
        $carts_count = Cart::where('user_id', auth()->user()->id)->count();

        return response()->json([
            'success' => true,
            'message' =>'Jumlah Cart',
            'data'    => $carts_count
        ], 200);
    }

    public function total()
    {
        $carts = Cart::where('user_id', auth()->user()->id)->get();
        $total = 0;

        foreach($carts as $cart){
          $total += $cart->product->price * $cart->qty;
        }

        return response()->json([
            'success' => true,
            'message' => 'Total',
            'data'    => $total
        ], 200);
    }

    public function store($id)
    {   
        $product = Product::findorFail($id);

        if($product->stock == 0){

          return response()->json([
            'success' => false,
            'message' => 'Stock ' . $product->name . ' tersisa 0',
          ], 404);

        } else {

          $user_cart = Cart::where('user_id', auth()->user()->id)->where('product_id', $id)->first();
  
          if($user_cart){
  
            $user_cart->qty += 1;
            $user_cart->save();
  
            $product->stock -= 1;
            $product->save();
  
            return response()->json([
              'success' => true,
              'message' => 'Cart berhasil disimpan',
            ], 201);
  
          } else {
  
            Cart::create([
              'user_id' => auth()->user()->id,
              'product_id' => $id,
              'qty' => 1,
            ]);  
  
            $product->stock -= 1;
            $product->save();

            return response()->json([
              'success' => true,
              'message' => 'Cart berhasil disimpan',
            ], 201);
  
          }

        }

    }

    public function decrease($id)
    {
        $cart = Cart::findOrfail($id);
        $product = Product::findOrfail($cart->product_id);

        if($cart->qty == 1) {

          return response()->json([
            'success' => false,
            'message' => 'Qty ' . $product->name . ' tidak boleh kurang dari 1',
          ], 404);

        } else {

          $cart->qty -= 1;
          $cart->save();
  
          $product->stock += 1;
          $product->save();

          return response()->json([
            'success' => true,
            'message' => 'Cart berhasil diupdate',
          ], 201);

        }
    }

    public function increase($id)
    {
        $cart = Cart::findOrfail($id);
        $product = Product::findOrfail($cart->product_id);

        if($product->stock == 0){

          return response()->json([
            'success' => false,
            'message' => 'Stock ' . $product->name . ' tersisa 0',
          ], 404);

        } else {

          $cart->qty += 1;
          $cart->save();

          $product->stock -= 1;
          $product->save();
  
          return response()->json([
            'success' => true,
            'message' => 'Cart berhasil diupdate',
          ], 201);

        }

    }

    public function destroy($id)
    {
        $cart = Cart::findOrfail($id);
        $product = Product::findOrfail($cart->product_id);

        $product->stock += $cart->qty;
        $product->save();
        $cart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart berhasil dihapus',
        ], 200);
    }
}