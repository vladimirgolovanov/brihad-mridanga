<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $table = 'operations';
    public static function get_all_operations($personid)
    {
        $operations = [];
        $summ = 0;
        $os = DB::table('operations')->where('person_id', $personid)->get();
        foreach($os as $o) {
            // для каждой книги каждый раз берем цену
            $result = DB::select("SELECT `price` FROM `bookprice` WHERE `book_id`=:book_id AND `created_at` < :datetime ORDER BY `created_at` DESC LIMIT 1", [
                'datetime' => $o->datetime,
                'book_id' => $o->book_id,
                ]); // вообще это необязательно делать raw запросом, надо перевести в кверибилдер
            // проверяем если нет цены
            if(!count($result)) $price = 0;
            else $price = $result[0]->price;
            // формируем массив данных в двух видах
            $operations[$o->datetime]['data'][] = [
                'book_id' => $o->book_id,
                'quantity' => $o->quantity,
                'operation_type' => $o->operation_type,
                'price' => $price,
            ];
            /*$operations[$o->datetime]['books'][] = $o->book_id;*/
            $operations[$o->datetime]['quantity'][] = $o->quantity*$price;
            /*$operations[$o->datetime]['price'][] = $price;*/
            /*$operations[$o->datetime]['operation_type'] = $o->operation_type;*/
            // собираем отдельно итоговую цифру
            if($o->operation_type == 1) {
                $summ = $summ+($price*$o->quantity);
            } elseif($o->operation_type == 2) {
                // тут необходимо просто учет внесенного laxmi
            }
            // список остальных операций по номерам
        }
        return [$operations, $summ];
    }
}
