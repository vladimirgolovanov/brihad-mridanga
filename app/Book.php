<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'name',
        'group_id'
    ];
	public static function asdf()
	{
		print 'asdf';
	}
    //
}
