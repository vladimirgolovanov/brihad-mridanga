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

    public static function get_all_persongroups()
    {
        $result = DB::table('persongroups')->orderBy('name', 'asc')->get();
        $items = [];
        foreach($result as $item) {
            $items[$item->id] = $item;
        }
        return $items;
    }

    public static function get_persongroup_name($id) {
        return DB::table('persongroups')->where('id', $id)->first()->name;
    }

    public static function get_persongroup_names($id) {
        $persons = DB::table('persons AS p')
            ->leftJoin('persongroups AS pg', 'p.persongroup_id', '=', 'pg.id')
            ->select('p.id as id', 'pg.name AS persongroup_name')
            ->get();
        $pgs = [];
        foreach($person as $p) {
            $pgs[$p-id] = $p->persongroup_name;
        }
        return $pgs;
    }
}
