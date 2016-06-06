<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orederdetail extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'OrderID',
        'ProductID',
        'Quantity'
    ];
}