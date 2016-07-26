<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends BaseModel 
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'MainCategory',
        'SubCategory'
    ];
}