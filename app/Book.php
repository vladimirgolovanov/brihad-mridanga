<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'shortname',
        'name',
        'bookgroup_id',
        'book_type',
        'pack',
        'price_buy',
        'price',
        'price_shop',
    ];
    public static function get_all_books($user_id)
    {
        $books = DB::table('books AS b')
            ->leftJoin('bookgroups AS bg', 'b.bookgroup_id', '=', 'bg.id')
            ->where('b.user_id', $user_id)
            ->orderBy(DB::raw('-bg.id'), 'desc')
            ->orderBy('b.name', 'asc')
            ->select('b.*', 'bg.name AS bookgroup_name')
            ->get();
        $bks = [];
        foreach($books as $b) {
            $bks[$b->id] = $b;
        }
        return $bks;
    }
    public static function get_books_db($user_id)
    {
        $books = DB::table('books AS b')
            ->leftJoin('bookgroups AS bg', 'b.bookgroup_id', '=', 'bg.id')
            ->where('b.user_id', $user_id)
            ->orderBy(DB::raw('-bg.id'), 'desc')
            ->orderBy('b.name', 'asc')
            ->select('b.*', 'bg.name AS bookgroup_name')
            ->get();
        $bks = [];
        foreach($books as $b) {
            if(!array_key_exists($b->bookgroup_id, $bks)) $bks[$b->bookgroup_id] = ["name" => $b->bookgroup_name, "books" => []];
            $bks[$b->bookgroup_id]["books"][] = $b;
        }
        return $bks;
    }
    public static function get_books_info($user_id)
    {
        $books = DB::table('books')->where('user_id', $user_id)->orderBy('books.id', 'asc')->get();
        $book_names = [];
        foreach($books as $k => $v) {
            $book_names[$v->id] = $v;
        }
        return $book_names;
    }
}
