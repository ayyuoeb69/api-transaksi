<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'customer_id', 
        'merchant_id',
        'payment',
        'address',
        'postal_fee',
        'total_price',
        'status'
    ];
}
