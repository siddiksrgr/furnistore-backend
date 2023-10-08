<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'user_id', 'code', 'name', 'phone', 'address' ,'total_price', 'transaction_status', 'expired_at'
    ];

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function shipping()
    {
        return $this->hasOne(Shipping::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction_details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}

