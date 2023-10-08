<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::get()->count();
        $products = Product::get()->count();
        $transactions = Transaction::get()->count();
       
        $data = [
          "users" => $users, 
          "products" => $products, 
          "transactions" => $transactions,
        ];

        return response()->json([
            'success' => true,
            'data'    => $data
        ], 200);
    }
}