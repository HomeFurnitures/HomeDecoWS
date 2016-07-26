<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'SKU',
        'Name',
        'Price',
        'DiscountPrice',
        'Weight',
        'Description',
        'ShortDescription',
        'Thumbnail',
        'Image',
        'Stock',
        'CategoryID'
    ];
}
