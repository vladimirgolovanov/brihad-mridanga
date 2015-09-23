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
            if($o->operation_type == 1) {
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
                // отдельно пишем сумму каждой строчки, чтобы считать
                $operations[$o->datetime]['quantity'][] = $o->quantity*$price;
            } elseif($o->operation_type == 2) {
                $operations[$o->datetime]['data'][] = [
                    'laxmi' => $o->laxmi,
                    'operation_type' => $o->operation_type,
                ];
            } elseif($o->operation_type == 4) {
                // скорей всего мы берем тут неправильный прайс
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
                // отдельно пишем сумму каждой строчки, чтобы считать
                $operations[$o->datetime]['quantity'][] = $o->quantity*$price;
            }
            // операции 3 и 4
            // 3 = ремейн
            // 4 = ретёрн
            // собираем отдельно итоговую цифру
            if($o->operation_type == 1) {
                $summ = $summ+($price*$o->quantity);
            } elseif($o->operation_type == 2) {
                $summ = $summ-($o->laxmi);
                // тут необходим просто учет внесенного laxmi
            } elseif($o->operation_type == 4) {
                $summ = $summ-($price*$o->quantity); // здесь учитывается цена на момент возврата, а должна быть цена на момент взятия?
            }
            // список остальных операций по номерам
        }
        return [$operations, $summ];
    }
    public static function operation_type_name() {
        // переместить это в БД миграциями
        $operation_type_name = [
            1 => 'Выдача книг',
            2 => 'Сдача лакшми',
            3 => 'Отчет об остатке книг',
            4 => 'Возврат книг',
        ];
        return $operation_type_name;
    }
}
