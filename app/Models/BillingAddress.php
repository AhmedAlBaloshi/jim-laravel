<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingAddress extends Model
{
    use HasFactory;
    protected $table = 'billing_address';
    protected $fillable = [
        'customer_id',
        'first_name',
        'last_name',
        'company_name',
        'address',
        'town_city',
        'state_country',
        'post_zip',
        'email_address',
        'phone',
        'notes'
    ];
}
