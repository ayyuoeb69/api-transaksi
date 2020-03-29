<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $fillable = [
        'name', 
        'logo', 
        'user_id',
        'address',
    ];
}
