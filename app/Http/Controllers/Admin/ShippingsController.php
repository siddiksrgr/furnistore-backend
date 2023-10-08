<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipping;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingsController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
          'transaction_id' => 'required|exists:transactions,id',
          'resi' => 'required|min:11',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Error !',
                'data'   => $validator->errors()
            ], 401);

        } else {

          $transaction = Transaction::findOrfail($request->transaction_id);
          $transaction->transaction_status = "dikirim";
          $transaction->save();

          Shipping::create([
            'transaction_id' => $transaction->id, 
            'resi' => $request->input('resi')
          ]);

          return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dikirim',
          ], 201);

        }
    }
}