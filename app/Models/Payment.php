<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'transaction_id', 'photo',
    ];

    protected $appends = ['photo_url'];

    public function getPhotoUrlAttribute()
    {
        return url('payment-images/' . $this->photo);
    }
}

