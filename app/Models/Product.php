<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Category::class, 'cid', 'id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'storeId', 'id');
    }

    public function carts()
    {
        return $this->belongsTo(Cart::class, 'pid', 'id');
    }
}
