<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    /**
     * @var string
     */
    protected $table = 'product_images';

    /**
     * @var array
     */
    protected $fillable = [
      'product_id', 'name',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return url('product-images/' . $this->name);
    }
}

