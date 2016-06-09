<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Userdetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'FirstName',
        'LastName',
        'Birthday',
        'Address',
        'PostalCode',
        'City',
        'State',
        'Country',
        'Phone',
        'MobilePhone'
    ];
}