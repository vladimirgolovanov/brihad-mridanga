<?php

namespace App;

use Log;

use Auth;

use DB;
use Illuminate\Database\Eloquent\Model;
use MyProject\Proxies\__CG__\stdClass;

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

    public static function get_last_remains_date($personid) {
        $o = DB::table('operations')
            ->where('person_id', $personid)
            ->where('operation_type', 10)
            ->orderBy('custom_date', 'desc')
            ->first();
        return $o?$o->custom_date:'';
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
    public static function get_operations($personid, $tilldate = null, $except = null) // Требует рефакторинга
    {
        $books = array();
        $books_info = Book::get_books_info();
        $books_left = array();
        $books_distr = array();
        $used = 0;
        $laxmi = 0;
        $osgrp = array();
        $osg = [];
        $os = DB::table('operations AS o')
            ->leftJoin('books AS b', 'o.book_id', '=', 'b.id')
            ->where('o.person_id', $personid);
        if($tilldate) $os->where('o.custom_date', '<=', $tilldate);
        $os ->orderBy('o.custom_date', 'asc')
            ->orderBy('o.operation_type', 'asc')
            ->orderBy('o.datetime', 'asc')
            ->select(
                'o.datetime',
                'o.custom_date',
                'o.person_id',
                'o.book_id',
                'o.price',
                'o.price_buy',
                'o.quantity',
                'o.operation_type',
                'o.created_at',
                'o.updated_at',
                'o.laxmi',
                'o.description',
                'b.name'
            );
        if($except) $os->where('o.datetime', '<>', $except);
        $os = $os->get();
        $prevcase = 0;
        $prevop = 0;
        $lxm = 0;
        $gain = 0;
        $lxmprice = 0;
        $debt = 0;
        $oss = [];
        $os[] = 1;
        foreach($os as $o) {
            if($prevcase == 10 && (gettype($o) != 'object' || ($o->operation_type != 10 || ($o->operation_type == 10 && $prevop != $o->datetime)))) {
                foreach($books as $k => $v) {
                    if(!isset($books_distr[$k])) $books_distr[$k] = 0;
                    foreach(array_slice($v, 1) as $b) {
                        $lxm += $b[0] * $b[1];
                        if($books_info[$k]->book_type) $lxmprice += $b[0] * $b[2];
                        $gain += $b[0] * (($b[1] > $b[2])?($b[1] - $b[2]):0);
                        $books_distr[$k] += $b[0];
                    }
                    unset($books[$k]);
                }
                $books = $books_left;
                $books_left = [];
                $oss[] = array('type' => 'info', 'text' => 'Распространенные книги:', 'o' => '');
                $book_types = ['Махабиги' => 0, 'Биги' => 0, 'Средние' => 0, 'Маленькие' => 0];
                $total_books = 0;
                $total_non_bbt = 0;
                $points = 0;
                $osg['books_distr'] = [];
                foreach($books_distr as $k => $v) {
                    $oss[] = array('type' => 'info', 'text' => $books_info[$k]->name, 'o' => $v);
                    $osg['books_distr'][] = ['name' => $books_info[$k]->name, 'shortname' => $books_info[$k]->shortname, 'o' => $v];
                    switch($books_info[$k]->book_type) {
                        case 1: $points += 2 * $v; $book_types['Махабиги'] += $v; $total_books += $v; break;
                        case 2: $points += 1 * $v; $book_types['Биги'] += $v; $total_books += $v; break;
                        case 3: $points += 0.5 * $v; $book_types['Средние'] += $v; $total_books += $v; break;
                        case 4: $points += 0.25 * $v; $book_types['Маленькие'] += $v; $total_books += $v; break;
                        default: $total_non_bbt += $v; break;
                    }
                }
                $books_distr = [];
                $oss[] = array('type' => 'info', 'text' => 'Всего распространено книг', 'o' => $total_books);
                $osg['total_books'] = $total_books;
                $osg['total_non_bbt'] = $total_non_bbt;
                foreach($book_types as $k => $v) {
                    $oss[] = array('type' => 'info', 'text' => $k, 'o' => $v);
                }
                $oss[] = array('type' => 'info', 'text' => 'Всего очков', 'o' => $points);
                $osg['total_points'] = $points;
                $oss[] = array('type' => 'info', 'text' => 'Распространено на', 'o' => $lxm);
                $oss[] = array('type' => 'info', 'text' => 'Закупочная', 'o' => $lxmprice);
                $oss[] = array('type' => 'info', 'text' => 'Прибыль', 'o' => $gain);
                $osg['total_gain'] = $gain;
                $osg['donation_gain'] = 0;
                $oss[] = array('type' => 'info', 'text' => 'Получено', 'o' => $laxmi);
                if($laxmi - $debt > $lxm) {
                    $oss[] = array('type' => 'info', 'text' => 'Сверхпожертвование', 'o' => sprintf("%.2f", $laxmi - $debt - $lxm));
//                    $osg['total_gain'] += $laxmi - $debt - $lxm;
                    $osg['donation_gain'] = sprintf("%.2f", $laxmi - $debt - $lxm);
                    $debt = 0;
                } elseif($laxmi - $debt < $lxm) {
                    $debt -= $laxmi - $lxm;
                    $oss[] = array('type' => 'info', 'text' => 'Долг', 'o' => $debt);
                    $osg['debt'] = $debt;
                } else {
                    $debt = 0;
                }
                $lxm = 0;
                $laxmi = 0;
                $gain = 0;
                $lxmprice = 0;
                $osgrp[] = $osg;
            }
            if($prevcase == 1 && (gettype($o) != 'object' || ($o->operation_type != 1 || ($o->operation_type == 1 && $prevop != $o->datetime)))) {
                $total_books = 0;
                $total_non_bbt = 0;
                $points = 0;
                $Laxmi = 0;
                foreach($books_left as $k => $v) {
                    $osg['books'][] = ['name' => $books_info[$k]->name, 'shortname' => $books_info[$k]->shortname, 'o' => $v->quantity];
                    switch($books_info[$k]->book_type) {
                        case 1: $points += 2 * $v->quantity; $total_books += $v->quantity; $Laxmi += $v->price * $v->quantity; break;
                        case 2: $points += 1 * $v->quantity; $total_books += $v->quantity; $Laxmi += $v->price * $v->quantity; break;
                        case 3: $points += 0.5 * $v->quantity; $total_books += $v->quantity; $Laxmi += $v->price * $v->quantity; break;
                        case 4: $points += 0.25 * $v->quantity; $total_books += $v->quantity; $Laxmi += $v->price * $v->quantity; break;
                        default: $total_non_bbt += $v->quantity; $Laxmi += $v->price * $v->quantity; break;
                    }
                }
                $osg['total_books'] = $total_books;
                $osg['total_non_bbt'] = $total_non_bbt;
                $osg['total_points'] = $points;
                $osg['total_Laxmi'] = $Laxmi;
                $osgrp[] = $osg;
            }
            if($prevcase == 3 && (gettype($o) != 'object' || ($o->operation_type != 3 || ($o->operation_type == 3 && $prevop != $o->datetime)))) {
                $total_books = 0;
                $total_non_bbt = 0;
                $points = 0;
                $Laxmi = 0;
                foreach($books_left as $k => $v) {
                    $osg['books'][] = ['name' => $books_info[$k]->name, 'shortname' => $books_info[$k]->shortname, 'o' => $v->quantity];
                    switch($books_info[$k]->book_type) {
                        case 1: $points += 2 * $v->quantity; $total_books += $v->quantity; $Laxmi += $v->price * $v->quantity; break;
                        case 2: $points += 1 * $v->quantity; $total_books += $v->quantity; $Laxmi += $v->price * $v->quantity; break;
                        case 3: $points += 0.5 * $v->quantity; $total_books += $v->quantity; $Laxmi += $v->price * $v->quantity; break;
                        case 4: $points += 0.25 * $v->quantity; $total_books += $v->quantity; $Laxmi += $v->price * $v->quantity; break;
                        default: $total_non_bbt += $v->quantity; $Laxmi += $v->price * $v->quantity; break;
                    }
                }
                $osg['total_books'] = $total_books;
                $osg['total_non_bbt'] = $total_non_bbt;
                $osg['total_points'] = $points;
                $osg['total_Laxmi'] = $Laxmi;
                $osgrp[] = $osg;
            }
            if($prevcase == 4 && (gettype($o) != 'object' || ($o->operation_type != 4 || ($o->operation_type == 4 && $prevop != $o->datetime)))) {
                $total_books = 0;
                foreach($books_left as $k => $v) {
                    $osg['books'][] = ['name' => $books_info[$k]->name, 'shortname' => $books_info[$k]->shortname, 'o' => $v->quantity];
                    switch($books_info[$k]->book_type) {
                        case 1: $total_books += $v->quantity; break;
                        case 2: $total_books += $v->quantity; break;
                        case 3: $total_books += $v->quantity; break;
                        case 4: $total_books += $v->quantity; break;
                    }
                }
                $osg['total_books'] = $total_books;
                $osgrp[] = $osg;
            }
            if(gettype($o) != 'object') break;
            if($prevop != $o->datetime || !$prevop) {
                $oss[] = array('type' => 'operation', 'o' => $o);
                $books_left = [];
                $osg = [];
            }
            $prevcase = $o->operation_type;
            $prevop = $o->datetime;
            $osg['id'] = $o->datetime;
            $osg['date'] = $o->custom_date;
            $osg['description'] = $o->description;
            switch($o->operation_type) {
                case 10:
                    $osg['type'] = 'remains';
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
                                if($books_info[$o->book_id]->book_type) $lxmprice += $b[0] * $b[2];
                                $gain += $b[0] * (($b[1] > $b[2])?($b[1] - $b[2]):0);
                            } else {
                                $books[$o->book_id][1][0] -= $used;
                                $lxm += $used * $b[1];
                                if($books_info[$o->book_id]->book_type) $lxmprice += $used * $b[2];
                                $gain += $used * (($b[1] > $b[2])?($b[1] - $b[2]):0);
                                break;
                            }
                        }
                        $prices = array_pop($books[$o->book_id]);
                        if ($o->quantity > 0) {
                            $books_left[$o->book_id] = [$o->quantity, [$o->quantity, $prices[1], $prices[2]]];
                        }
                        unset($books[$o->book_id]);
                    } else {
                        $oss[] = array('type' => 'warning', 'o' => 'Остаток книг, которых не было');
                    }
                    $oss[] = array('type' => 'subop', 'o' => $o);
                    break;
                case 1:
                    $osg['type'] = 'make';
                    if(isset($books[$o->book_id])) {
                        $books[$o->book_id][0] += $o->quantity;
                        $books[$o->book_id][] = array($o->quantity, $o->price, $o->price_buy);
                    } else {
                        $books[$o->book_id] = [$o->quantity, [$o->quantity, $o->price, $o->price_buy]];
                    }
                    $books_left[$o->book_id] = $o;
                    $oss[] = array('type' => 'subop', 'o' => $o);
                    break;
                case 3:
                    $osg['type'] = 'order';
//                    if(isset($books[$o->book_id])) {
//                        $books[$o->book_id][0] += $o->quantity;
//                        $books[$o->book_id][] = array($o->quantity, $o->price, $o->price_buy);
//                    } else {
//                        $books[$o->book_id] = [$o->quantity, [$o->quantity, $o->price, $o->price_buy]];
//                    }
                    $books_left[$o->book_id] = $o;
                    $oss[] = array('type' => 'subop', 'o' => $o);
                    break;
                case 2:
                    $laxmi += $o->laxmi;
                    $oss[] = array('type' => 'subop', 'o' => $o);
                    $osg['type'] = 'Laxmi';
                    $osg['Laxmi'] = $o->laxmi;
                    $osgrp[] = $osg;
                    break;
                case 4:
                    if(isset($books[$o->book_id])) {
                        $osg['type'] = 'return';
                        $books[$o->book_id][0] -= $o->quantity;
                        $qty = $o->quantity;
                        $complete = 0;
                        foreach(array_slice($books[$o->book_id], 1) as $b2) {
                            if($b2[0] <= $qty) {
                                $shifted_b = array_splice($books[$o->book_id], 1, 1);
                                $qty -= $shifted_b[0][0];
                            } else {
                                $books[$o->book_id][1][0] -= $qty;
                                $complete = 1;
                                break;
                            }
                        }
                        if($books[$o->book_id][0] == 0) {
                            unset($books[$o->book_id]);
                        } elseif($complete) {
                            $books[$o->book_id] = [$books[$o->book_id][0], [$books[$o->book_id][0], $books[$o->book_id][1][1], $books[$o->book_id][1][2]]];
                        } else {
                            unset($books[$o->book_id]);
                            $oss[] = array('type' => 'warning', 'o' => 'Вернули лишние книги');
                        }
                    }
                    else {
                        $oss[] = array('type' => 'warning', 'o' => 'Сданы книги, которые не выдавались');
                    }
                    $oss[] = array('type' => 'subop', 'o' => $o);
                    $books_left[$o->book_id] = $o;
                    break;
            }
        }
        array_pop($os);
        foreach($os as $o) {
            if($o->book_id && isset($books[$o->book_id])) {
                $books[$o->book_id]['name'] = $o->name;
                $books[$o->book_id]['shortname'] = $books_info[$o->book_id]->shortname;
            }
        }
        $current_books_price = 0;
        foreach($books as $k => $v) {
            foreach(array_slice($v, 1, -2) as $b) { // Очень опасное место
                $current_books_price += $b[0] * $b[1];
            }
        }
        $osgrp = array_reverse($osgrp);
        if($laxmi > $debt) {
            $laxmi -= $debt;
            $debt = 0;
        } else {
            $debt -= $laxmi;
            $laxmi = 0;
        }
        return [$oss, $books, $lxm, $laxmi, $current_books_price, $debt, $osgrp];
    }

    public static function reports($tilldate = null) {
        $ps = DB::table('persons AS p')
            ->leftJoin('persongroups AS pg', 'p.persongroup_id', '=', 'pg.id')
            ->select('p.id AS id', 'p.name AS name', 'p.hide AS hide', 'pg.name AS persongroup_name')
            ->get();
        $persons = [];
        $totals = [
            'total' => 0,
            'points' => 0,
            'buying_price' => 0,
            'gain' => 0,
            'donation' => 0,
            'debt' => 0,
            'maha' => 0,
            'big' => 0,
            'middle' => 0,
            'small' => 0
        ];
        foreach($ps as $p) {
            $last_remains_date = self::get_last_remains_date($p->id);
            list($oss, $books, $lxm, $laxmi, $current_books_price, $debt) = self::get_operations($p->id, $tilldate);
            $p->remains_date = $last_remains_date;
            $p->books = $books;
            $p->laxmi = $laxmi;
            $p->current_books_price = $current_books_price;
            $p->donation = 0;
            $p->total = 0;
            $p->points = 0;
            $p->gain = 0;
            $p->buying_price = 0;
            $p->maha = 0;
            $p->big = 0;
            $p->middle = 0;
            $p->small = 0;
            $p->reports = [];
            foreach($oss as $os) {
                if($os['type'] == 'operation' && $os['o']->operation_type == 10) {
                    $report_date = $os['o']->custom_date;
                    $p->reports[$report_date] = new \stdClass();
                    $p->reports[$report_date]->id = $p->id;
                    $p->reports[$report_date]->name = $p->name;
                    $p->reports[$report_date]->pgroup = $p->persongroup_name;
                    $p->reports[$report_date]->donation = 0;
                    $p->reports[$report_date]->debt = 0;
                    $p->reports[$report_date]->total = 0;
                    $p->reports[$report_date]->points = 0;
                    $p->reports[$report_date]->gain = 0;
                    $p->reports[$report_date]->buying_price = 0;
                    $p->reports[$report_date]->maha = 0;
                    $p->reports[$report_date]->big = 0;
                    $p->reports[$report_date]->middle = 0;
                    $p->reports[$report_date]->small = 0;
                    $balance = false;
                } elseif($os['type'] == 'info') {
                        switch($os['text']) {
                            case 'Всего распространено книг':
                                $p->total += $os['o'];
                                $p->reports[$report_date]->total += $os['o'];
                                $totals['total'] += $os['o'];
                                break;
                            case 'Всего очков':
                                $p->points += $os['o'];
                                $p->reports[$report_date]->points += $os['o'];
                                $totals['points'] += $os['o'];
                                break;
                            case 'Закупочная':
                                $p->buying_price += $os['o'];
                                $p->reports[$report_date]->buying_price += $os['o'];
                                $totals['buying_price'] += $os['o'];
                                break;
                            case 'Прибыль':
                                $p->gain += $os['o'];
                                $p->reports[$report_date]->gain += $os['o'];
                                $totals['gain'] += $os['o'];
                                break;
                            case 'Сверхпожертвование':
                                $p->donation += $os['o'];
                                $p->reports[$report_date]->donation += $os['o'];
                                $totals['donation'] += $os['o'];
                                $p->debt = 0;
                                $p->reports[$report_date]->debt = 0;
                                $balance = true;
                                break;
                            case 'Долг':
                                $p->debt = $os['o'];
                                $p->reports[$report_date]->debt = $os['o'];
                                $balance = true;
                                break;
                            case 'Махабиги':
                                $p->maha += $os['o'];
                                $p->reports[$report_date]->maha += $os['o'];
                                $totals['maha'] += $os['o'];
                                break;
                            case 'Биги':
                                $p->big += $os['o'];
                                $p->reports[$report_date]->big += $os['o'];
                                $totals['big'] += $os['o'];
                                break;
                            case 'Средние':
                                $p->middle += $os['o'];
                                $p->reports[$report_date]->middle += $os['o'];
                                $totals['middle'] += $os['o'];
                                break;
                            case 'Маленькие':
                                $p->small += $os['o'];
                                $p->reports[$report_date]->small += $os['o'];
                                $totals['small'] += $os['o'];
                                break;
                        }
                        if(!$balance) {
                            $p->debt = 0;
                            $p->reports[$report_date]->debt = 0;
                        }
                }
            }
            $p->debt = $debt;
            $totals['debt'] += $p->debt;
            if($p->total || $p->donation || $p->debt || !$p->hide) $persons[] = $p;
        }
        return ['persons' => $persons, 'totals' => $totals];
    }

    public static function monthly_report($begin_date, $end_date, $persons) {
        if(count($persons)) {
            $ps = DB::table('persons')
                ->select('id', 'name', 'hide')
                ->whereIn('id', $persons)
                ->orderBy('name')
                ->get();
        } else {
            $ps = DB::table('persons')
                ->select('id', 'name', 'hide')
                ->orderBy('name')
                ->get();
        }
        $report = [];
        $totals = [
            'total' => 0,
            'points' => 0,
            'buying_price' => 0,
            'gain' => 0,
            'donation' => 0,
            'debt' => 0,
            'maha' => 0,
            'big' => 0,
            'middle' => 0,
            'small' => 0
        ];
        foreach($ps as $p) {
            $last_remains_date = self::get_last_remains_date($p->id);
//            if($last_remains_date && (!$begin_date || (strcmp($last_remains_date, $begin_date) >= 0 && strcmp($last_remains_date, $end_date) <= 0))) {
            if(1) {
                list($oss, $books, $lxm, $laxmi, $current_books_price) = self::get_operations($p->id);
                $r = new \stdClass();
                $r->person_id = $p->id;
                $r->name = $p->name;
                $r->hide = $p->hide;
                $r->remains_date = $last_remains_date;
                $r->donation = 0;
                $r->debt = 0;
                $r->total = 0;
                $r->points = 0;
                $r->gain = 0;
                $r->buying_price = 0;
                $r->maha = 0;
                $r->big = 0;
                $r->middle = 0;
                $r->small = 0;
                $r->balance = $laxmi - $current_books_price;
                $r->reports = [];
                $state = 0;
                foreach($oss as $os) {
                    if($state == 0 && $os['type'] == 'operation' && $os['o']->operation_type == 10 && (!$begin_date || (strcmp($os['o']->custom_date, $begin_date) >= 0 && strcmp($os['o']->custom_date, $end_date) <= 0))) {
                        $state = 1;
                        $report_date = $os['o']->custom_date;
                        $r->reports[$report_date] = new \stdClass();
                        $r->reports[$report_date]->donation = 0;
                        $r->reports[$report_date]->debt = 0;
                        $r->reports[$report_date]->total = 0;
                        $r->reports[$report_date]->points = 0;
                        $r->reports[$report_date]->gain = 0;
                        $r->reports[$report_date]->maha = 0;
                        $r->reports[$report_date]->big = 0;
                        $r->reports[$report_date]->middle = 0;
                        $r->reports[$report_date]->small = 0;
                    } elseif($state > 0) {
                        if($os['type'] == 'info') {
                            $state = 2;
                            switch($os['text']) {
                                case 'Всего распространено книг':
                                    $r->total += $os['o'];
                                    $r->reports[$report_date]->total += $os['o'];
                                    $totals['total'] += $os['o'];
                                    break;
                                case 'Всего очков':
                                    $r->points += $os['o'];
                                    $r->reports[$report_date]->points += $os['o'];
                                    $totals['points'] += $os['o'];
                                    break;
                                case 'Закупочная':
                                    $r->buying_price += $os['o'];
                                    $totals['buying_price'] += $os['o'];
                                    break;
                                case 'Прибыль':
                                    $r->gain += $os['o'];
                                    $r->reports[$report_date]->gain += $os['o'];
                                    $totals['gain'] += $os['o'];
                                    break;
                                case 'Сверхпожертвование':
                                    $r->donation += $os['o'];
                                    $r->reports[$report_date]->donation += $os['o'];
                                    $totals['donation'] += $os['o'];
                                    $r->balance += $r->donation;
                                    $r->debt = 0;
                                    $r->reports[$report_date]->debt = 0;
                                    break;
                                case 'Долг':
                                    $r->debt = $os['o'];
                                    $r->reports[$report_date]->debt = $os['o'];
                                    break;
                                case 'Махабиги':
                                    $r->maha += $os['o'];
                                    $r->reports[$report_date]->maha += $os['o'];
                                    $totals['maha'] += $os['o'];
                                    break;
                                case 'Биги':
                                    $r->big += $os['o'];
                                    $r->reports[$report_date]->big += $os['o'];
                                    $totals['big'] += $os['o'];
                                    break;
                                case 'Средние':
                                    $r->middle += $os['o'];
                                    $r->reports[$report_date]->middle += $os['o'];
                                    $totals['middle'] += $os['o'];
                                    break;
                                case 'Маленькие':
                                    $r->small += $os['o'];
                                    $r->reports[$report_date]->small += $os['o'];
                                    $totals['small'] += $os['o'];
                                    break;
                            }
                        } elseif($state == 2) {
                            $state = 0;
                        }
                    }
                }
                $totals['debt'] += $r->debt;
                $r->balance -= $r->debt;
                if($r->total || $r->donation || $r->debt || !$r->hide) $report[] = $r;
            }
        }
        return [$report, $totals];
    }

    public static function get_first_operation_date() {
        return DB::table('operations')->orderBy('custom_date')->first()->custom_date;
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
