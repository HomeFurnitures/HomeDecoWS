<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Custom_product extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'part',
        'image'
    ];
}
