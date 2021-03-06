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
    public static function get_all_books()
    {
        $books = DB::table('books AS b')
            ->leftJoin('bookgroups AS bg', 'b.bookgroup_id', '=', 'bg.id')
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
    public static function get_books_db()
    {
        $books = DB::table('books AS b')
            ->leftJoin('bookgroups AS bg', 'b.bookgroup_id', '=', 'bg.id')
            ->orderBy(DB::raw('-bg.id'), 'desc')
            ->orderBy('b.name', 'asc')
            ->select(
                'b.id',
                'b.name',
                'b.pack',
                'b.price',
                'b.price_shop',
                'b.book_type',
                'b.bookgroup_id',
                'bg.name AS bookgroup_name')
            ->get();
        $bks = [];
        foreach($books as $b) {
            if(!array_key_exists($b->bookgroup_id, $bks)) $bks[$b->bookgroup_id] = ["name" => $b->bookgroup_name, "books" => []];
            $bks[$b->bookgroup_id]["books"][] = $b;
        }
        return $bks;
    }
    public static function get_books_info()
    {
        $books = DB::table('books')->orderBy('books.id', 'asc')->get();
        $book_names = [];
        foreach($books as $k => $v) {
            $book_names[$v->id] = $v;
        }
        return $book_names;
    }
}
