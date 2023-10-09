<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $guard = ['id'];
    protected $fillable = ['name', 'email', 'password', 'contact', 'address'];
    protected $hidden = ['password'];



    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
