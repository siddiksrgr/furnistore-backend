<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'transaction_id', 'resi'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}