<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orderdetail extends BaseModel
{
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
