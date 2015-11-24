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
        return $books;
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
