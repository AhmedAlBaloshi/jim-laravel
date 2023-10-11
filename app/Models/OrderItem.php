<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    public function product(){
        return $this->belongsTo(Product::class, 'prdct_id','id');
    }


    public function store(){
        return $this->belongsTo(Store::class, 'prdct_storeId', 'id');
    }
}
