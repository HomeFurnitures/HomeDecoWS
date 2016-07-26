<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ShipAddress',
        'BilAddress',
        'PostalCode',
        'City',
        'State',
        'Country',
        'MobilePhone',
        'Phone',
        'ShippingMethod',
        'Email',
        'FullName',
        'Price'
    ];
}