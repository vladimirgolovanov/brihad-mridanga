<?php

namespace App;

use DB;

use Illuminate\Database\Eloquent\Model;

class PersonGroup extends Model
{
    protected $table = 'persongroups';

    protected $fillable = [
        'name',
    ];

    public static function get_all_persongroups($user_id)
    {
        $result = DB::table('persongroups')->where('user_id', $user_id)->orderBy('name', 'asc')->get();
        $items = [];
        foreach($result as $item) {
            $items[$item->id] = $item;
        }
        return $items;
    }

    public static function get_persongroup_name($id) {
        return DB::table('persongroups')->where('id', $id)->first()->name;
    }
}
