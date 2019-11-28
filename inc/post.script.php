<?php
require "data.php";
require_once "lib.php";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim(strip_tags($_POST['name']));
    $option = strip_tags($_POST['option']);
    $text = strip_tags($_POST['text']);

    foreach ($menus as $arr) { //Перебор динамик-меню
        if ($option == $arr['href']) {
            $optionName = $arr['menu']; //Присвоить норм значение, на русском
            break;
        }
    }

    insertDB ($link, $name, $optionName, $text);
    mysqli_close($link);
}
header ("Location: $serverHTTP/index.php?id=add");
exit;