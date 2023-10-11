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
        'full_name',
        'company_name',
        'address_type',
        'address',
        'town_city',
        'state_country',
        'post_zip',
        'email_address',
        'phone',
        'notes'
    ];
}
