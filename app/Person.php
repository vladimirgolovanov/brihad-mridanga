<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'persons';

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
