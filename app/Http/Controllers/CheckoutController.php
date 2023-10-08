<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaction;
use App\Models\Cart;
use App\Models\TransactionDetail;

class CheckoutController extends Controller
{
    public function store(Request $request)
    { 
        $validator = Validator::make($request->all(), [
          'name'   => 'required',
          'phone' => 'required|numeric',
          'address' => 'required',
        ]);

        if ($validator->fails()) {

          return response()->json([
              'success' => false,
              'message' => 'Error !',
              'data'   => $validator->errors()
          ], 401);

        } else {

          $transaction = Transaction::create([
            'user_id' => auth()->user()->id,
            'code' => 'FS-' . mt_rand(000000, 999999),
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'total_price' => $request->total,
            'transaction_status' => 'unpaid',
          ]);

          $carts = Cart::where('user_id', auth()->user()->id)->get();
          foreach($carts as $cart) {

            TransactionDetail::create([
              'transaction_id' => $transaction->id,
              'product_id' => $cart->product_id,
              'price' => $cart->product->price,
              'qty' => $cart->qty,
            ]);

            $cart->delete();
          }

          return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil disimpan',
            'data' => $transaction->code,
          ], 201);
          
        }
    }
}