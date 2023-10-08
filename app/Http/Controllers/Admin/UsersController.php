<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('transactions')
        ->where('name','LIKE','%'.$request->filter.'%')
        ->orWhere('email','LIKE','%'.$request->filter.'%')
        ->latest()
        ->paginate(10);

        return response()->json([
            'success' => true,
            'data'    => $users
        ], 200);
    }
}