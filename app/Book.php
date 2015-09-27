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
    ];
    public static function get_all_books($user_id)
    {
        /*$results = DB::select( DB::raw("`bookprice`.`book_id`, `bookprice`.`price` FROM `bookprice` WHERE `book_id` IN (SELECT `book_id` FROM `bookprice` GROUP BY `book_id` HAVING `created_at`=MAX(`created_at`))"), array(
            'somevariable' => $someVariable,
        ));*/
        $books = DB::table('books')->where('user_id', $user_id)->orderBy('books.id', 'asc')->get();
        $bookprice = DB::select("SELECT `bookprice`.`book_id`, `bookprice`.`price` FROM `bookprice` WHERE `book_id` IN (SELECT `book_id` FROM `bookprice` GROUP BY `book_id` HAVING `created_at`=MAX(`created_at`))");
        if(count($bookprice)) foreach($bookprice as $bp) $price[$bp->book_id] = $bp->price;
        else $price = [];
        foreach($books as $b) if(!in_array($b->id, array_keys($price))) $price[$b->id] = "0";
        return [$books, $price];
    }
}
