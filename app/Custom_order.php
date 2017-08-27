<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
class Custom_order extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'part1',
        'part2',
        'part3',
        'part4',
        'part5',
        'quantity'
    ];
}