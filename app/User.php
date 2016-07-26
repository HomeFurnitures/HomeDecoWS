<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Username', 
        'Password', 
        'Email',
        'Type'
    ];
}
