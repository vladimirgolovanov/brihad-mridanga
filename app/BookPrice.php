<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookPrice extends Model
{
    protected $table = 'bookprice';
    protected $fillable = [
        'price',
        'book_id'
    ];
}
