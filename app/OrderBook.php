<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderBook extends Model
{
    public $timestamps = false;

    protected $table = 'order_books';

    protected $fillable = [
        'order_id',
        'book_id',
        'price',
        'pack',
        'box',
        'byone'
    ];
}
