<?php

require "data.php"; // Почему-то не работает без этого

$titleArray = selectRedactorDB ($link, $id); // Без этого на главной не сработает

if( isset($titleArray) )
    showContent ($titleArray, $id);

if ( isset($_SESSION['ban']) ) {
    echo "<h1>Вы забанены и не можете добавлять новости... Напишите админу, если считаете, что безосновательно!</h1>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim(strip_tags($_POST['name']));
    $option = strip_tags($_POST['option']);
    $text = strip_tags($_POST['text']);
    $textfull = strip_tags($_POST['textfull']);

    foreach ($menus as $arr) { //Перебор динамик-меню
        if ($option == $arr['href']) {
            $optionName = $arr['menu']; //Присвоить норм значение, на русском
            break;
        }
    }

    insertDB ($link, $name, $optionName, $text, $textfull);
    mysqli_close($link);

    header ("Location: $serverHTTP/index.php?id=add");
    exit;
}

?>

<h2>Форма для заливки и юзанья БД</h2>
<form method="POST" action="inc/posts.php" class="form-varez">
    <fieldset><fieldset>
        <legend>Внесите данные в форму</legend>
            <p><label>Введите Название:
                <input type="text" name="name"></label></p>
            <p>Выберите категорию:
                <?php
                drawOption ($menus);
                ?>
            </p>
            <label><p>Введите краткую версию поста:<br>
            <textarea name='text' class="postform-textarea"></textarea></p></label>
            <label><p>Введите весь пост целиком:<br>
            <textarea name='textfull' class="postform-textarea"></textarea></p></label>
        </fieldset>
        <input type="submit" value="Добавить пост">
    </fieldset>
</form>