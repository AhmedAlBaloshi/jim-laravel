<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    protected $table = 'store';

    public function products(){
        return $this->hasMany(Product::class, 'storeId', 'id');
    }

    public function orderItems(){
        return $this->hasMany(OrderItem::class, 'prdct_storeId', 'id');
    }
}
