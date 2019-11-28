<h2>Тут есть во что поиграть</h2>

<?php

$arraySelect = selectDB ($link, $name, $optionName, $text, $id);

$titleArray = selectRedactorDB ($link, $id); // Без этого на главной не сработает

if( isset($titleArray) )
    showContent ($titleArray, $id);

?>

<?php
foreach ($arraySelect as $items) {
?>
    <h3><?= "<a href='files.php?id=$id&iv={$items['id']}'>{$items["title"]}</a>"; ?></h3>
    <h4>Категория: <?= $items['opt'] ?></h4>
    <p><?= parse_bb($items['description']) ?></p>
    <p><?= $items['id'] ?></p>

    <?
        if(isset($_SESSION['admin']) || isset($_SESSION['superuser']) ){
    ?>
            <p>Удалить:<?= "<a href='inc/delNewsDB.php?iv={$items['id']}&id=$id'>Go</a>"; ?></p>

<?
    }
}