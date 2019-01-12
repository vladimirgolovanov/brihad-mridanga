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

    public static function get_all_bookgroups()
    {
        $result = DB::table('bookgroups')->orderBy('name', 'asc')->get();
        $items = [];
        foreach($result as $item) {
            $items[$item->id] = $item;
        }
        return $items;
    }

    public static function get_bookgroup_name($id) {
        return DB::table('bookgroups')->where('id', $id)->first()->name;
    }
}
