<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'transaction_id', 'product_id', 'price', 'qty'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}