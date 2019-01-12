<?php

namespace App\Http\Controllers;

use Mail;
use App\Book;
use App\Order;
use App\OrderBook;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Get all books with prices.
     *
     * @return Response
     */
    public function get_books_db($id)
    {
        $books = Book::get_books_db($id);
        return $books;
    }

    public function make_order(Request $request)
    {
        $order = new Order;
        $order->bv = $request->bv;
        $order->name = $request->name;
        $order->phone = $request->phone;
        $order->email = $request->email;
        $order->descr = $request->descr;
        $order->save();
        $request->id = $order->id;
        foreach($request->order as $book) {
            $orderbook = new OrderBook;
            $orderbook->order_id = $order->id;
            $orderbook->book_id = $book['id'];
            $orderbook->price = $book['price'];
            $orderbook->pack = $book['pack'];
            $orderbook->box = $book['box'];
            $orderbook->byone = $book['byone'];
            $orderbook->save();
        }
        Mail::send('emailOrder', ['data' => $request], function($message) use ($request)
        {
            $message->subject('Заказ #'.$request->id);
            $message->from(config('mail.from.address'), config('mail.from.name'));
            $message->to(env('MAIL_TO'));
        });
        return json_encode($order);
    }
}
