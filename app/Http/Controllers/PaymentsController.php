<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaction;
use App\Models\Payment;

class PaymentsController extends Controller
{
    public function index($id)
    {
        $transaction = Transaction::where('code', $id)->firstOrfail();

        if ($transaction) {
            return response()->json([
                'success'   => true,
                'message'   => 'Detail Transaction',
                'data'      => $transaction
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Transaction tidak ditemukan',
            ], 404);
        }
    }

    public function store(Request $request)
    {          
        $validator = Validator::make($request->all(), [
          'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {

          return response()->json([
              'success' => false,
              'message' => 'Error !',
              'data'   => $validator->errors()
          ], 401);

        } else {
     
          $image = $request->file('image');
          $name = time().rand(1,50).'.'.$image->extension();
          $image->move('payment', $name);  

          $transaction = Transaction::findOrfail($request->transaction_id);
          
          $payment = Payment::create([
            'transaction_id' => $transaction->id,
            'photo' => $name,
          ]);

          $transaction->transaction_status = 'paid';
          $transaction->save();

          return response()->json([
            'success' => true,
            'message' => 'Payment berhasil disimpan',
          ], 201);

        }
    }
}