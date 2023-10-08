<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;

class TransactionsController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['payment', 'shipping'])->where('user_id', auth()->user()->id)->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'List Semua Transaksi',
            'data'    => $transactions
        ], 200);
    }

    public function show($id)
    {
        $transaction = Transaction::with(['transaction_details.product.images', 'payment', 'shipping'])
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
}