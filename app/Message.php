<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Message',
        'Image',
        'UserID',
        'IsUser',
        'Date'
    ];
}
