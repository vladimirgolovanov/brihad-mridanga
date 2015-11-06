<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'shortname',
        'name',
        'pack',
        'price_buy',
        'price',
        'price_shop',
    ];
    public static function get_all_books($user_id)
    {
        $books = DB::table('books')->where('user_id', $user_id)->orderBy('books.id', 'asc')->get();
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
