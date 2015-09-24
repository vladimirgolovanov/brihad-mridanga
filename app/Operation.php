<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $table = 'operations';
    public static function get_all_operations($personid) // Требует рефакторинга
    {
        $operations = [];
        $summ = 0;
        $os = DB::table('operations')->where('person_id', $personid)->get();
        foreach($os as $o) {
            $nice_date = self::convert_to_nice_date($o->datetime); // проверить правильность self::
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
                    'nice_date' => $nice_date,
                ];
                // отдельно пишем сумму каждой строчки, чтобы считать
                $operations[$o->datetime]['quantity'][] = $o->quantity*$price;
                // необходимо преобразовывать даты
                // если этот год: дата и месяц
                // если пред год: год, дата, месяц
            } elseif($o->operation_type == 2) {
                $operations[$o->datetime]['data'][] = [
                    'laxmi' => $o->laxmi,
                    'operation_type' => $o->operation_type,
                    'nice_date' => $nice_date,
                ];
            } elseif($o->operation_type == 3) {
                $operations[$o->datetime]['data'][] = [
                    'operation_type' => $o->operation_type,
                    'nice_date' => $nice_date,
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
                    'nice_date' => $nice_date,
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
    public static function book_names_by_id() {
        $book_names_by_id = [];
        $books = DB::table('books')->select('id', 'name')->get();
        foreach($books as $book) $book_names_by_id[$book->id] = $book->name;
        return $book_names_by_id;
    }
    public static function convert_to_nice_date($datetime) {
        $months = ["01" => "января", "02" => "февраля", "03" => "марта", "04" => "апреля", "05" => "мая", "06" => "июня", "07" => "июля", "08" => "августа", "09" => "сентября", "10" => "октября", "11" => "ноября", "12" => "декабря"];
        $year = substr($datetime, 0, 4);
        $month = $months[substr($datetime, 5, 2)];
        $day = intval(substr($datetime, 8, 2));
        if($year == date("Y")) {
            return $day."&nbsp;".$month;
        } else {
            return $day."&nbsp;".$month."&nbsp;".$year;
        }
    }
}
