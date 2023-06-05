<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Subscription;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','price','stripe_price_id', 'subscription_name'
    ];


    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'stripe_id', 'stripe_price_id');
    }
}
