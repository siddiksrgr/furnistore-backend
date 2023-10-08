<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionsController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::with(['user', 'shipping'])
        ->where('code', 'LIKE', '%'.$request->filter.'%')
        ->orWhereRelation('user', 'name', 'LIKE', '%'.$request->filter.'%')
        ->latest()
        ->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'List Semua Transaksi',
            'data'    => $transactions
        ], 200);
    }

    public function show($id)
    {
        $transaction = Transaction::with(['transaction_details.product.images', 'shipping', 'payment', 'user'])
        ->where('code', $id)
        ->firstOrfail();

        if ($transaction) {
            return response()->json([
                'success'   => true,
                'message'   => 'Detail transaction!',
                'data'      => $transaction
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi Tidak Ditemukan!',
            ], 404);
        }
    }

    public function shipping(Request $request)
    {
        $validator = Validator::make($request->all(), [
          'resi'   => 'required|min:11',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Error !',
                'data'   => $validator->errors()
            ], 401);

        } else {

          $transaction = Transaction::findOrfail($request->transaction_id);
          $transaction->resi = $request->resi;
          $transaction->transaction_status = "dikirim";
          $transaction->save();

          return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil diupdate',
          ], 201);

        }
    }
}