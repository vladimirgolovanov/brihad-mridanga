<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email'
    ];
}
