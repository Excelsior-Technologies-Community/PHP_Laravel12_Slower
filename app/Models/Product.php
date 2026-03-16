<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The table associated with the model.
     */

    protected $table = 'products';


    /**
     * The attributes that are mass assignable.
     */

    protected $fillable = [
        'name',
        'price'
    ];


    /**
     * The attributes that should be cast.
     */

    protected $casts = [
        'price' => 'integer',
    ];

}