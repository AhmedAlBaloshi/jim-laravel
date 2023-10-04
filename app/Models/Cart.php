<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';

    protected $fillable = ['customer_id', 'pid', 'pqty', 'price_type', 'price', 'datetime'];

    public function products()
    {
        return $this->belongsTo(Product::class, 'pid', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
}
