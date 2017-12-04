От: <?php echo $data->name; ?><br>
Округ/Сектор БВ: <?php echo $data->bv; ?><br>
Телефон: <?php echo $data->phone; ?><br>
E-mail: <?php echo $data->email; ?><br>
Доп. информация:<br>
<?php echo nl2br($data->descr); ?><br><br>
<?php
$total = 0;
$totalprice = 0;
foreach($data->order as $book) {
    echo $book['name'].': '.($book['box']?($book['box'].' x '.$book['pack'].($book['byone']?(' + '.$book['byone'].' = '.($book['box']*$book['pack']+$book['byone'])):(' = '.($book['box']*$book['pack'])))):($book['byone'])).' шт. * '.$book['price'].' р. = '.(($book['box']*$book['pack']+$book['byone'])*$book['price']).' р.<br>';
    $total += $book['box']*$book['pack']+$book['byone'];
    $totalprice += ($book['box']*$book['pack']+$book['byone'])*$book['price'];
}
?><br><br>
Всего книг: <?php echo $total; ?><br>
Общая стоимость: <?php echo $totalprice; ?> р.
