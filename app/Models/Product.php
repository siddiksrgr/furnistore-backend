<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name', 'stock', 'price', 'descriptions', 'slug',
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}

