<!DOCTYPE html>
<html>
<head>
	<meta charset="utf8" />
	<title>Store books</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.1/css/materialize.min.css">
	<script src="/static/jquery-2.1.4.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.1/js/materialize.min.js"></script>
	<script src="/static/functions.js"></script>
	<link rel="stylesheet" href="/static/style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<style>
	</style>
</head>
<body>

<div class="container">

<nav>
<div class="nav-wrapper">
<span class="brand-logo right hide-on-med-and-down">Brihad Mridanga</span>
<ul id="nav-mobile" class="left">
<li class="active"><a href="#">Books</a></li>
<li><a href="#">Persons</a></li>
<li><a href="#">Logout</a></li>
</ul>
</div>
</nav>


<?php
$books = [
    0 => ['header', 'Популярные'],
    1 => ['Наука Самоосознания', rand(10, 100)],
    2 => ['Бесценный дар', rand(10, 100)],
    3 => ['Бхагавад-гита как она есть (большая)', rand(10, 100)],
    4 => ['Бхагавад-гита как она есть (средняя)', rand(10, 100)],
    5 => ['В поисках просветления', rand(10, 100)],
    6 => ['Веда', rand(10, 100)],
    7 => ['Грихастха-ашрам', rand(10, 100)],
    8 => ['Духовный учитель и ученик', rand(10, 100)],
    9 => ['Еще один шанс', rand(10, 100)],
    10 => ['Жизнь происходит из жизни', rand(10, 100)],
    11 => ['header', 'Кришна'],
    12 => ['Кришна Арт', rand(10, 100)],
    13 => ['Кришна Делюкс', rand(10, 100)],
    14 => ['Кришна однотомник', rand(10, 100)],
    15 => ['Кришна-1', rand(10, 100)],
    16 => ['Кришна-2', rand(10, 100)],
    17 => ['Легкое путешествие на другие планеты', rand(10, 100)],
    18 => ['Махабхарата том 1', rand(10, 100)],
    19 => ['Махабхарата том 2', rand(10, 100)],
    20 => ['Молитвы царицы Кунти', rand(10, 100)],
    21 => ['На пути к Кришне', rand(10, 100)],
    22 => ['header', 'Рандомный подзаголовок'],
    23 => ['Нарада Бхакти Сутры', rand(10, 100)],
    24 => ['Неизвестное сокровище Индии', rand(10, 100)],
    25 => ['Нектар Наставлений', rand(10, 100)],
    26 => ['Нектар Преданности', rand(10, 100)],
    27 => ['Очерки Ведической Литературы', rand(10, 100)],
    28 => ['Песни ачарьев-вайшнавов', rand(10, 100)],
    29 => ['По ту сторону рождения и смерти', rand(10, 100)],
    30 => ['Послание Бога', rand(10, 100)],
    31 => ['Прабхупада', rand(10, 100)],
    32 => ['Прабхупада Лиламрита том 1', rand(10, 100)],
    33 => ['Прабхупада Лиламрита том 2', rand(10, 100)],
    34 => ['Путешествие вглубь себя', rand(10, 100)],
    35 => ['Путь к совершенству', rand(10, 100)],
    36 => ['Раджа Видья - царь знаний', rand(10, 100)],
    37 => ['Свет Бхагаваты', rand(10, 100)],
    38 => ['Совершенство йоги', rand(10, 100)],
    39 => ['Сознание Кришны - высшая сиситема йоги', rand(10, 100)],
    40 => ['Учение Господа Капилы', rand(10, 100)],
    41 => ['header', 'Чайтанья'],
    42 => ['Учение Шри Чайтаньи', rand(10, 100)],
    43 => ['Чайтанья Чаритамрита Ади-лила том 1', rand(10, 100)],
    44 => ['Чайтанья Чаритамрита Ади-лила том 2', rand(10, 100)],
    45 => ['Чайтанья Чаритамрита Антья-лила том 1', rand(10, 100)],
    46 => ['Чайтанья Чаритамрита Антья-лила том 2', rand(10, 100)],
    47 => ['Чайтанья Чаритамрита Мадхья-лила том 1', rand(10, 100)],
    48 => ['Чайтанья Чаритамрита Мадхья-лила том 2', rand(10, 100)],
    49 => ['Чайтанья Чаритамрита Мадхья-лила том 3', rand(10, 100)],
    50 => ['Чайтанья Чаритамрита Мадхья-лила том 4', rand(10, 100)],
    51 => ['Чайтанья Чаритамрита Мадхья-лила том 5', rand(10, 100)],
    52 => ['Шримад Бхагаватам 1-9', rand(10, 100)],
    53 => ['Шримад Бхагаватам 10.1', rand(10, 100)],
    54 => ['Шримад Бхагаватам 10.2', rand(10, 100)],
    55 => ['Шримад Бхагаватам 10.3', rand(10, 100)],
    56 => ['Шримад Бхагаватам 11.1', rand(10, 100)],
    57 => ['Шри Ишопанишад', rand(10, 100)],
    58 => ['Совершенные вопросы совершенные ответы', rand(10, 100)],
    59 => ['Высший вкус', rand(10, 100)],
    60 => ['Царский пир всем по карману', rand(10, 100)],
    61 => ['Основные молитвы (Фил. книга)', rand(10, 100)],
    62 => ['СДГ - 26 качеств преданного', rand(10, 100)],
];
?>
<div>
<div>

<h1>Make operation</h1>
<form method="POST" action="/operation" accept-charset="UTF-8">
<input name="_token" type="hidden" value="hCsMeyrnKslzcz2PxRNKneBqgw1fg8BLWr2LEdQ9">
<input type="hidden" name="datetime" id="datetime" value=""/>

<input type="date" class="datepicker" id="custom_date" value="2015-11-06" />
<script>$('.datepicker').pickadate({ selectMonths: true, selectYears: 5 });</script>

<ul class="collection with-header">
<?php
foreach($books as $id => $book) {
    if($book[0] == 'header') {
?>
<li class="collection-header"><h5><?=$book[1]?></h5></li>
<?php
        if($id == 11) $other = 1;
    } elseif(!isset($other)) {
?>
<li class="collection-item active-disactivated">
    <div class="row">
    <div class="col l6 m6 s12" style="margin-bottom:10px;">
        <?=$book[0]?>
    </div>
    <div class="col l3 m3 s8">
        <span field="quantity<?=$id?>" id="less<?=$id?>" data-move="less" class="stepqty btn-floating btn-small waves-effect waves-light red"><i class="material-icons">arrow_drop_down</i></span>
        <input data-step="10" id="quantity<?=$id?>" name="bookcount[<?=$id?>]" type="number" style="width:40px;text-align:center;">
        <span field="quantity<?=$id?>" id="more<?=$id?>" data-move="more" class="stepqty btn-floating btn-small waves-effect waves-light red"><i class="material-icons">arrow_drop_up</i></span>
    </div>
    <div class="col l3 m3 s4">
        <nobr><input id="price<?=$id?>" name="price[<?=$id?>]" type="text" value="<?=$book[1]?>" style="width:40px;text-align:right;">&nbsp;₽</nobr>
    </div>
    </div>
</li>
<?php
    } else {
?>
<li class="collection-item active-disactivated">
    <div class="row">
    <div class="col l6 m6 s12" style="margin-bottom:10px;">
        <?=$book[0]?>
    </div>
    <div class="col l3 m3 s8">
        <span field="quantity<?=$id?>" id="less<?=$id?>" data-move="less" class="stepqty"><i class="material-icons">arrow_drop_down</i></span>
        <input data-step="10" id="quantity<?=$id?>" name="bookcount[<?=$id?>]" type="number" style="width:40px;text-align:center;height:auto;">
        <span field="quantity<?=$id?>" id="more<?=$id?>" data-move="more" class="stepqty"><i class="material-icons">arrow_drop_up</i></span>
    </div>
    <div class="col l3 m3 s4">
        <nobr><input id="price<?=$id?>" name="price[<?=$id?>]" type="text" value="<?=$book[1]?>" style="width:40px;text-align:right;height:auto;">&nbsp;₽</nobr>
    </div>
    </div>
</li>
<?php
    }
}
?>
</ul>

<p>Description: <textarea rows="3" style="width:100%" name="description" cols="50"></textarea></p>
<input name="personid" type="hidden" value="5">
<input name="operation_type" type="hidden" value="1">
<br><br>
<input type="submit" value="Operate">
</form>


<h1>Person name</h1>
<div>
<a href="/persons/11/edit">Редактировать</a>
</div>
<div>
<a href="/operation/11/make">Выдача книг</a>
<a href="/operation/11/laxmi">Сдача лакшми</a>
<a href="/operation/11/remain">Отчет об остатке</a>
<a href="/operation/11/return">Возврат книг</a>
</div>

<div class="row">

<div class="col l4 s12">
<table class="bordered">
<thead><tr><th colspan="2">Книги на руках</th></tr></thead>
<tbody>
<?php
$onhands = [];
$count = rand(1, 15);
while(count($onhands) < $count) {
    $addbook = rand(1, count($books));
    if(!in_array($addbook, $onhands) and $books[$addbook][0] != 'header') $onhands[] = $addbook;
}
foreach($onhands as $bookid) {
?><tr><td><?=$books[$bookid][0]?></td><td><?=rand(10, 100)?></td></tr>
<?php } ?>
</tbody>
</table>
</div>

<div class="col l8 s12">
<table class="bordered">
<thead>
<tr><th><a href="#">Выдача книг</a></th><th>30 Nov</th></tr>
</thead>
<tbody>
<?php
$count = rand(1, 5);
while(count($onhands) < $count) {
    $addbook = rand(1, count($books));
    if(!in_array($addbook, $onhands) and $books[$addbook][0] != 'header') $onhands[] = $addbook;
}
foreach($onhands as $bookid) {
?><tr><td><?=$books[$bookid][0]?></td><td><?=rand(10, 100)?></td></tr>
<?php } ?>
</tbody>
<thead>
<tr><th><a href="#">Выдача книг</a></th><th>15 Nov</th></tr>
</thead>
<tbody>
<?php
$count = rand(1, 5);
while(count($onhands) < $count) {
    $addbook = rand(1, count($books));
    if(!in_array($addbook, $onhands) and $books[$addbook][0] != 'header') $onhands[] = $addbook;
}
foreach($onhands as $bookid) {
?><tr><td><?=$books[$bookid][0]?></td><td><?=rand(10, 100)?></td></tr>
<?php } ?>
</tbody>
</table>
</div>

</div>

</body>
</html>

