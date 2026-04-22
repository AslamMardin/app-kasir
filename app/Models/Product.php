<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'barcode',
        'name',
        'category_id',
        'purchase_price',
        'selling_price',
        'stock',
        'min_stock',
        'unit',
        'image',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
