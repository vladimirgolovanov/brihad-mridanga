<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $table = 'operations';
    public static function get_operation_milestones($personid)
    {
        // если нет milestones?
        $milestones = DB::table('operations')
            ->select('datetime', 'book_id', 'quantity')
            ->where('person_id', $personid)
            ->where('operation_type', 3)
            ->groupBy('datetime')
            ->orderBy('datetime', 'desc')
            ->get();
        $dates = [];
        foreach($milestones as $key => $milestone) {
            if(!isset($prevmilestone)) $prevmilestone = date("Y-m-d H:i:s");
            $rmilestones[] = [$milestone->datetime, $prevmilestone]; // здесь порядок может показаться нелогичным + аналогично такая же внизу
            if($key == 0) { // плохой ход, лучше объединить со следующим, так как много кода повторяется
                $ops = DB::table('operations')
                    ->select('datetime')
                    ->where('person_id', $personid)
                    ->where('datetime', '>', $milestone->datetime)
                    ->groupBy('datetime')
                    ->orderBy('datetime', 'desc')
                    ->get();
                $prevmilestone = $milestone->datetime;
                foreach($ops as $op) $dates[$milestone->datetime][] = $op->datetime;
            } else {
                $ops = DB::table('operations')
                    ->select('datetime')
                    ->where('person_id', $personid)
                    ->where('datetime', '>', $milestone->datetime)
                    ->where('datetime', '<', $prevmilestone)
                    ->groupBy('datetime')
                    ->orderBy('datetime', 'desc')
                    ->get();
                $prevmilestone = $milestone->datetime;
                foreach($ops as $op) $dates[$milestone->datetime][] = $op->datetime;
            }
        }
        if(!isset($prevmilestone)) $prevmilestone = date("Y-m-d H:i:s");
        $firstdate = DB::table('operations')
            ->select('datetime')
            ->where('person_id', $personid)
            ->orderBy('datetime', 'asc')
            ->first();
        // Исправляем ошибку при с пустой базой
        if(isset($firstdate->datetime)) $firstdate = date("Y-m-d H:i:s", strtotime($firstdate->datetime.' -1 days'));
        else $firstdate = date("Y-m-d H:i:s", strtotime(0));
        $rmilestones[] = [$firstdate, $prevmilestone];
        $ops = DB::table('operations')
            ->select('datetime')
            ->where('person_id', $personid)
            ->where('datetime', '<', $prevmilestone)
            ->groupBy('datetime')
            ->orderBy('datetime', 'desc')
            ->get();
        //foreach($ops as $op) $dates['0000-00-00 00:00:00'][] = $op->datetime;
        foreach($ops as $op) $dates[$firstdate][] = $op->datetime;

        return [$rmilestones, $dates];
    }

    // Взять самую свежую цену книги на определенную дату
    public static function get_book_price_by_date($book_id, $datetime = 0)
    {
        if(!$datetime) $datetime = date("Y-m-d H:i:s");
        $price = DB::table('bookprice')
            ->select('price')
            ->where('book_id', $book_id)
            ->where('created_at', '<', $datetime)
            ->orderBy('created_at', 'desc')
            ->first();
        if(!count($price)) $price = 0; // необходимо протестировать еще раз с пустой ценой
        else $price = $price->price;
        return $price;
    }
    public static function find_books_to_return($person_id, $book_id, $datetime, $quantity, $backbooks) {
        // на самом деле эта функция не учитывает ранее сданные книги
        $recentbooks = DB::table('operations')
            ->where('book_id', $book_id)
            ->where('person_id', $person_id)
            ->where('datetime', '<', $datetime)
            ->where('operation_type', 1)
            ->orderBy('datetime', 'desc')
            ->first();
//        $backbooks[] = [
//            'datetime' => $recentbooks->datetime,
//            'quantity' => ($recentbooks->quantity >= $quantity) ? $quantity : $recentbooks->quantity
//        ];
        if(isset($recentbooks->quantity) and $recentbooks->quantity < $quantity) {
            $quantity = $quantity-$recentbooks->quantity;
            $backbooks = self::find_books_to_return($person_id, $book_id, $recentbooks->datetime, $quantity, $backbooks);
        }
        return $backbooks;
    }
    public static function get_operation_in_milestone($person_id, $milestone) {
        $os = DB::table('operations')
            ->where('person_id', $person_id)
            ->where('datetime', '>', $milestone[0])
            ->where('datetime', '<', $milestone[1])
            ->orderBy('datetime', 'desc')
            ->get();
        return $os;
    }
    public static function get_milestone_data($person_id, $milestones) {
        $milestonedata = [];
        foreach($milestones as $milestone) {
            $milestonedata[$milestone[0]] = [];
            $os = DB::table('operations')
                ->where('person_id', $person_id)
                ->where('datetime', $milestone[0])
                ->get();
            $nice_date = self::convert_to_nice_date($milestone[0]);
            foreach($os as $o) {
                $milestonedata[$milestone[0]][] = ['book_id' => $o->book_id, 'quantity' => $o->quantity, 'nice_date' => $nice_date, 'datetime' => $o->datetime];
            }
        }
        return $milestonedata;
    }
    public static function get_operations_by_milestone($personid, $milestones, $dates)
    {
        // пока не умеет показывать отдельную операцию
        $books = [];
        $operations = [];
        $summ = [];
        foreach($milestones as $milestone) {
            $summ[$milestone[0]] = 0;
            $os = self::get_operation_in_milestone($personid, $milestone);
            foreach($os as $o) {
                $nice_date = self::convert_to_nice_date($o->datetime); // мне кажется, что это тут лишнее
                if($o->operation_type == 1) {
                    $price = self::get_book_price_by_date($o->book_id, $o->datetime);
                    // формируем массив данных
                    $operations[$milestone[0]][$o->datetime]['data'][] = [
                        'book_id' => $o->book_id,
                        'quantity' => $o->quantity,
                        'operation_type' => $o->operation_type,
                        'price' => $price,
                        'nice_date' => $nice_date,
                    ];
                    // отдельно пишем сумму каждой строчки, чтобы считать
                    $operations[$milestone[0]][$o->datetime]['quantity'][] = $o->quantity*$price;
                    $operations[$milestone[0]][$o->datetime]['description'] = $o->description;
                } elseif($o->operation_type == 2) {
                    $operations[$milestone[0]][$o->datetime]['data'][] = [
                        'laxmi' => $o->laxmi,
                        'operation_type' => $o->operation_type,
                        'nice_date' => $nice_date,
                    ];
                    $operations[$milestone[0]][$o->datetime]['description'] = $o->description;
                } elseif($o->operation_type == 3) { // не отображается! - потому что это информация майлстоуна, а не подстроки - поэтому этот кусок трубуется убрать
                    $operations[$milestone[0]][$o->datetime]['data'][] = [
                        'book_id' => $o->book_id,
                        'quantity' => $o->quantity,
                        'operation_type' => $o->operation_type,
                        'nice_date' => $nice_date,
                    ];
                    $operations[$milestone[0]][$o->datetime]['description'] = $o->description;
                } elseif($o->operation_type == 4) {
                    $backbooks = self::find_books_to_return($personid, $o->book_id, $o->datetime, $o->quantity, []);
                    $operations[$milestone[0]][$o->datetime]['data'][] = [
                        'book_id' => $o->book_id,
                        'quantity' => $o->quantity,
                        'operation_type' => $o->operation_type,
                        //'price' => $price, // здесь нельзя однозначно писать, хотя интерфейс должен требовать
                        'nice_date' => $nice_date,
                    ];
                    $operations[$milestone[0]][$o->datetime]['description'] = $o->description;
                }
                if(in_array($o->operation_type, [1,3])) if(!isset($books[$milestone[0]][$o->book_id])) $books[$milestone[0]][$o->book_id] = 0;
                if($o->operation_type == 1) {
                    $summ[$milestone[0]] = $summ[$milestone[0]]+($price*$o->quantity);
                    $books[$milestone[0]][$o->book_id] = $books[$milestone[0]][$o->book_id]+$o->quantity; // возможно упростить += или как-то так
                } elseif($o->operation_type == 2) {
                    $summ[$milestone[0]] = $summ[$milestone[0]]-($o->laxmi);
                    // тут необходим просто учет внесенного laxmi
                } elseif($o->operation_type == 3) {
                } elseif($o->operation_type == 4) {
                    foreach($backbooks as $backbook) {
                        $backprice = self::get_book_price_by_date($o->book_id, $backbook['datetime']);
                        $summ[$milestone[0]] = $summ[$milestone[0]]-($backprice*$backbook['quantity']);
                    }
                }
            }
        }
        return [$milestones, $operations, $summ, $books];
    }

    public static function get_all_operations($personid, $operationid) // Требует рефакторинга
    {
        // переписать на работу с одной операцией
        $operations = [];
        $summ = 0;
        $books = [];
        $os = DB::table('operations')->where('person_id', $personid)->where('datetime', $operationid)->orderBy('datetime', 'desc')->get();
        foreach($os as $o) {
            $nice_date = self::convert_to_nice_date($o->datetime);
            if($o->operation_type == 1) {
                $price = self::get_book_price_by_date($o->book_id, $o->datetime);
                $operations[$o->datetime]['data'][] = [
                    'book_id' => $o->book_id,
                    'quantity' => $o->quantity,
                    'operation_type' => $o->operation_type,
                    'price' => $price,
                    'nice_date' => $nice_date,
                ];
                $operations[$o->datetime]['description'] = $o->description;
            } elseif($o->operation_type == 2) {
                $operations[$o->datetime]['data'][] = [
                    'laxmi' => $o->laxmi,
                    'operation_type' => $o->operation_type,
                    'nice_date' => $nice_date,
                ];
                $operations[$o->datetime]['description'] = $o->description;
            } elseif($o->operation_type == 3) {
                $operations[$o->datetime]['data'][] = [
                    'book_id' => $o->book_id,
                    'quantity' => $o->quantity,
                    'operation_type' => $o->operation_type,
                    'nice_date' => $nice_date,
                ];
                $operations[$o->datetime]['description'] = $o->description;
            } elseif($o->operation_type == 4) {
                $backbooks = self::find_books_to_return($personid, $o->book_id, $o->datetime, $o->quantity, []);
                // формируем массив данных в двух видах
                $operations[$o->datetime]['data'][] = [
                    'book_id' => $o->book_id,
                    'quantity' => $o->quantity,
                    'operation_type' => $o->operation_type,
                    //'price' => $price,
                    'nice_date' => $nice_date,
                ];
                $operations[$o->datetime]['description'] = $o->description;
            }
            if(in_array($o->operation_type, [1,3,4])) if(!isset($books[$o->book_id])) $books[$o->book_id] = 0;
            if($o->operation_type == 1) {
                $summ = $summ+($price*$o->quantity);
                $books[$o->book_id] = $books[$o->book_id]+$o->quantity;
            } elseif($o->operation_type == 2) {
                $summ = $summ-($o->laxmi);
            } elseif($o->operation_type == 3) {
            } elseif($o->operation_type == 4) {
                $books[$o->book_id] = $books[$o->book_id]-$o->quantity;
                foreach($backbooks as $backbook) {
                    $backprice = self::get_book_price_by_date($o->book_id, $backbook['datetime']);
                    $summ = $summ-($backprice*$backbook['quantity']);
                }

            }
        }
        return [$operations, $summ, $books];
    }

    /**
     * @param $personid
     * @return mixed
     */
    public static function get_operations($personid) // Требует рефакторинга
    {
        $books = array();
        $books_left = array();
        $used = 0;
        $laxmi = 0;
        $os = DB::table('operations AS o')
            ->leftJoin('books AS b', 'o.book_id', '=', 'b.id')
            ->where('o.person_id', $personid)
            ->orderBy('o.datetime', 'asc')
            ->select(
                'o.datetime',
                'o.person_id',
                'o.book_id',
                'o.quantity',
                'o.operation_type',
                'o.created_at',
                'o.updated_at',
                'o.laxmi',
                'o.description',
                'b.name'
            )
            ->get();
        $prevcase = 0;
        $prevop = 0;
        $lxm = 0;
        $oss = [];
        foreach($os as $o) {
            if($prevcase == 3 && ($o->operation_type != 3 || ($o->operation_type == 3 && $prevop != $o->datetime))) {
                foreach($books as $k => $v) {
                    foreach(array_slice($v, 1) as $b) {
                        $lxm += $b[0] * $b[1];
                    }
                    unset($books[$k]);
                }
                $books = $books_left;
                $books_left = [];
                $oss[] = array('type' => 'info', 'text' => 'Распространено на', 'o' => $lxm);
                $oss[] = array('type' => 'info', 'text' => 'Получено', 'o' => $laxmi);
                if($laxmi > $lxm) {
                    $oss[] = array('type' => 'info', 'text' => 'Сверхпожертвование', 'o' => $laxmi - $lxm);
                    $lxm = 0;
                } elseif($laxmi < $lxm) {
                    $oss[] = array('type' => 'info', 'text' => 'Долг', 'o' => $lxm - $laxmi);
                    $lxm -= $laxmi;
                } else {
                    $lxm = 0;
                }
            }
            if($prevop != $o->datetime) {
                $oss[] = array('type' => 'operation', 'o' => $o);
            }
            switch($o->operation_type) {
                case 3:
                    $prevcase = 3;
                    $prevop = $o->datetime;
                    if(isset($books[$o->book_id])) {
                        $used = $books[$o->book_id][0] - $o->quantity;
                        foreach (array_slice($books[$o->book_id], 1) as $b) {
                            if ($b[0] <= $used) {
                                $shifted_b = array_splice($books[$o->book_id], 1, 1);
                                $used -= $shifted_b[0][0];
                                $lxm += $b[0] * $b[1];
                            } else {
                                $books[$o->book_id][1][0] -= $used;
                                $lxm += $used * $b[1];
                                break;
                            }
                        }
                        if ($o->quantity > 0) {
                            $books_left[$o->book_id] = [$o->quantity, [$o->quantity, self::get_book_price_by_date($o->book_id, $o->updated_at)]];
                        }
                        unset($books[$o->book_id]);
                    } else {
                        $oss[] = array('type' => 'warning', 'o' => 'Остаток книг, которых не было');
                    }
                    $oss[] = array('type' => 'subop', 'o' => $o);
                    break;
                case 1:
                    $prevcase = 1;
                    $prevop = $o->datetime;
                    if(isset($books[$o->book_id])) {
                        $books[$o->book_id][0] += $o->quantity;
                        $books[$o->book_id][] = array($o->quantity, self::get_book_price_by_date($o->book_id, $o->updated_at));
                    } else {
                        $books[$o->book_id] = [$o->quantity, [$o->quantity, self::get_book_price_by_date($o->book_id, $o->updated_at)]];
                    }
                    $oss[] = array('type' => 'subop', 'o' => $o);
                    break;
                case 2:
                    $prevcase = 2;
                    $prevop = $o->datetime;
                    $laxmi += $o->laxmi;
                    $oss[] = array('type' => 'subop', 'o' => $o);
                    break;
                case 4:
                    $prevcase = 4;
                    $prevop = $o->datetime;
                    if(isset($books[$o->book_id])) {
                        $books[$o->book_id][0] -= $o->quantity;
                        $qty = $o->quantity;
                        $complete = 0;
                        foreach(array_slice($books[$o->book_id], 1) as $b2) {
                            if($b2[0] <= $qty) {
                                $shifted_b = array_splice($books[$o->book_id], 1, 1);
                                $qty -= $shifted_b[0][0];
                            } else {
                                $books[$o->book_id][1][0] -= $qty;
                                $qty = $books[$o->book_id][1][0];
                                $complete = 1;
                                break;
                            }
                        }
                        if($qty == 0) {
                            unset($books[$o->book_id]);
                        } elseif($complete) {
                            $books[$o->book_id] = [$qty, [$qty, self::get_book_price_by_date($o->book_id, $o->updated_at)]];
                        } else {
                            unset($books[$o->book_id]);
                            $oss[] = array('type' => 'warning', 'o' => 'Вернули лишние книги');
                        }
                    }
                    else {
                        $oss[] = array('type' => 'warning', 'o' => 'Сданы книги, которые не выдавались');
                        //$books[$o->book_id] = [$o->quantity, [$o->quantity, self::get_book_price_by_date($o->book_id, $o->updated_at)]];
                    }
                    $oss[] = array('type' => 'subop', 'o' => $o);
                    break;
            }
        }
        if($prevcase == 3) {
            foreach($books as $k => $v) {
                foreach(array_slice($v, 1) as $b) {
                    $lxm += $b[0] * $b[1];
                }
                unset($books[$k]);
            }
            $books = $books_left;
            $books_left = [];
            $oss[] = array('type' => 'info', 'text' => 'Распространено на', 'o' => $lxm);
            $oss[] = array('type' => 'info', 'text' => 'Получено', 'o' => $laxmi);
            if($laxmi > $lxm) {
                $oss[] = array('type' => 'info', 'text' => 'Сверхпожертвование', 'o' => $laxmi - $lxm);
                $lxm = 0;
            } elseif($laxmi < $lxm) {
                $oss[] = array('type' => 'info', 'text' => 'Долг', 'o' => $lxm - $laxmi);
                $lxm -= $laxmi;
            } else {
                $lxm = 0;
            }
        }
        return $oss;
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
        if($datetime > 0) {
            $months = ["01" => "января", "02" => "февраля", "03" => "марта", "04" => "апреля", "05" => "мая", "06" => "июня", "07" => "июля", "08" => "августа", "09" => "сентября", "10" => "октября", "11" => "ноября", "12" => "декабря"];
            $year = substr($datetime, 0, 4);
            $month = $months[substr($datetime, 5, 2)];
            $day = intval(substr($datetime, 8, 2));
            if($year == date("Y")) {
                return $day."&nbsp;".$month;
            } else {
                return $day."&nbsp;".$month."&nbsp;".$year;
            }
        } else {
            return "";
        }
    }
    public static function get_current_operation($personid, $operationid, $bookid) {
        $operation = DB::table('operations')
            ->where('person_id', $personid)
            ->where('datetime', $operationid)
            ->where('book_id', $bookid)
            ->first();
        return $operation;
    }
}
