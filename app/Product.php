<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 
        'slug', 
        'img',
        'price',
        'description',
        'stock',
        'size',
        'merchant_id'
    ];
}
