<?php

namespace App;

use DB;

use Illuminate\Database\Eloquent\Model;

class BookGroup extends Model
{
    protected $table = 'bookgroups';

    protected $fillable = [
        'name',
    ];

    public static function get_all_bookgroups($user_id)
    {
        $books = DB::table('bookgroups')->where('user_id', $user_id)->orderBy('name', 'asc')->get();
        return $books;
    }
}
