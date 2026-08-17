<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class Customer extends Model implements AuthenticatableContract
{
    use Authenticatable;

    protected $table = 'customers';

    public $timestamps = false;

    protected $fillable = [
        'first_name',
        'last_name',
        'full_name',
        'email',
        'phone',
        'municipality',
        'address',
        'password_hash',
        'remember_token',
        'reset_code',
        'reset_code_expires',
        'reset_code_attempts',
        'reset_token',
        'reset_expires',
    ];

    protected $hidden = ['password_hash', 'remember_token'];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'customer_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function savedCustomizations()
    {
        return $this->hasMany(SavedCustomization::class, 'customer_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'customer_id');
    }
}
