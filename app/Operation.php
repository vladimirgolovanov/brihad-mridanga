<?php

namespace App;

use Auth;

use DB;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $table = 'operations';

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
                $price = $o->price;
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
            } elseif($o->operation_type == 10) {
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
            if(in_array($o->operation_type, [1,10,4])) if(!isset($books[$o->book_id])) $books[$o->book_id] = 0;
            if($o->operation_type == 1) {
                $summ = $summ+($price*$o->quantity);
                $books[$o->book_id] = $books[$o->book_id]+$o->quantity;
            } elseif($o->operation_type == 2) {
                $summ = $summ-($o->laxmi);
            } elseif($o->operation_type == 10) {
            } elseif($o->operation_type == 4) {
                $books[$o->book_id] = $books[$o->book_id]-$o->quantity;
                foreach($backbooks as $backbook) {
                    $backprice = $o->price;
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
        $books_info = Book::get_books_info(Auth::user()->id);
        $books_left = array();
        $books_distr = array();
        $used = 0;
        $laxmi = 0;
        $os = DB::table('operations AS o')
            ->leftJoin('books AS b', 'o.book_id', '=', 'b.id')
            ->where('o.person_id', $personid)
            ->orderBy('o.custom_date', 'asc')
            ->orderBy('o.operation_type', 'asc')
            ->orderBy('o.datetime', 'asc')
            ->select(
                'o.datetime',
                'o.custom_date',
                'o.person_id',
                'o.book_id',
                'o.price',
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
        $os[] = 1;
        foreach($os as $o) {
            if($prevcase == 10 && (gettype($o) != 'object' || ($o->operation_type != 10 || ($o->operation_type == 10 && $prevop != $o->datetime)))) {
                foreach($books as $k => $v) {
                    if(!isset($books_distr[$k])) $books_distr[$k] = 0;
                    foreach(array_slice($v, 1) as $b) {
                        $lxm += $b[0] * $b[1];
                        $books_distr[$k] += $b[0];
                    }
                    unset($books[$k]);
                }
                $books = $books_left;
                $books_left = [];
                $oss[] = array('type' => 'info', 'text' => 'Распространенные книги:', 'o' => '');
                $gain = 0;
                $total_books = 0;
                $points = 0;
                foreach($books_distr as $k => $v) {
                    $oss[] = array('type' => 'info', 'text' => $books_info[$k]->name, 'o' => $v);
                    $gain += ($books_info[$k]->price - $books_info[$k]->price_buy) * $v;
                    $total_books += $v;
                    switch($books_info[$k]->book_type) {
                        case 1: $points += 2 * $v; break;
                        case 2: $points += 1 * $v; break;
                        case 3: $points += 0.5 * $v; break;
                        case 4: $points += 0.25 * $v; break;
                    }
                }
                $oss[] = array('type' => 'info', 'text' => 'Всего распространено книг', 'o' => $total_books);
                $oss[] = array('type' => 'info', 'text' => 'Всего очков', 'o' => $points);
                $oss[] = array('type' => 'info', 'text' => 'Распространено на', 'o' => $lxm.' р.');
                $oss[] = array('type' => 'info', 'text' => 'Прибыль', 'o' => $gain.' р.');
                $oss[] = array('type' => 'info', 'text' => 'Получено', 'o' => $laxmi.' р.');
                if($laxmi > $lxm) {
                    $oss[] = array('type' => 'info', 'text' => 'Сверхпожертвование', 'o' => ($laxmi - $lxm).' р.');
                    $lxm = 0;
                } elseif($laxmi < $lxm) {
                    $lxm = $laxmi - $lxm;
                    $oss[] = array('type' => 'info', 'text' => 'Долг', 'o' => (-$lxm).' р.');
                } else {
                    $lxm = 0;
                }
                $laxmi = 0;
            }
            if(gettype($o) != 'object') break;
            if($prevop != $o->datetime || !$prevop) {
                $oss[] = array('type' => 'operation', 'o' => $o);
            }
            switch($o->operation_type) {
                case 10:
                    $prevcase = 10;
                    $prevop = $o->datetime;
                    if(!$o->book_id) {

                    } elseif(isset($books[$o->book_id])) {
                        $used = $books[$o->book_id][0] - $o->quantity;
                        if($used < 0) {
                            $oss[] = array('type' => 'warning', 'o' => 'Сдано больше чем было ('.$books[$o->book_id][0].')');
                            $used = 0;
                        }
                        if(!isset($books_distr[$o->book_id]) && $used) $books_distr[$o->book_id] = 0;
                        if($used) $books_distr[$o->book_id] += $used;
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
                            $books_left[$o->book_id] = [$o->quantity, [$o->quantity, $o->price]];
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
                        $books[$o->book_id][] = array($o->quantity, $o->price);
                    } else {
                        $books[$o->book_id] = [$o->quantity, [$o->quantity, $o->price]];
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
                            $books[$o->book_id] = [$qty, [$qty, $o->price]];
                        } else {
                            unset($books[$o->book_id]);
                            $oss[] = array('type' => 'warning', 'o' => 'Вернули лишние книги');
                        }
                    }
                    else {
                        $oss[] = array('type' => 'warning', 'o' => 'Сданы книги, которые не выдавались');
                    }
                    $oss[] = array('type' => 'subop', 'o' => $o);
                    break;
            }
        }
        array_pop($os);
        foreach($os as $o) {
            if($o->book_id && isset($books[$o->book_id])) {
                $books[$o->book_id]['name'] = $o->name;
            }
        }
        return [$oss, $books, $lxm, $laxmi];
    }

    public static function operation_type_name() {
        // переместить это в БД миграциями
        $operation_type_name = [
            1 => 'Выдача книг',
            2 => 'Сдача лакшми',
            10 => 'Отчет об остатке книг',
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
