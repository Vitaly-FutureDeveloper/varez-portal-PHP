<?php
include_once "data.php";

//Работа со страницей
function drawMenu($menus, $horizontal=false) {
    if((string)$horizontal == "horizontal")
        $horizontal = 'menu-horizontal';

    echo "<ul class='menu'>";
    foreach ($menus as $arr) {
        // Проверка на зареганность пользователя
        if ( isset($_SESSION['user']) xor isset($_SESSION['superuser']) xor isset($_SESSION['admin']) xor !isset($_SESSION['ban'])) { 
            if ($arr['id'] == 'add') {
                continue;
            }
        }
        // КОНЕЦ Проверка на зареганность пользователя
        if ( $arr['id'] == 'admin' && !isset($_SESSION['admin']) ) { // Скрываем админку от всех кроме авторизованного админа
            continue;
        }
        echo "<li class='menu-item $horizontal'><a href='{$arr['href']}'>{$arr['menu']}</a></li>";
    }
    echo "</ul>";
}

function drawUsers($users, $horizontal=false) {
    if((string)$horizontal == "horizontal")
        $horizontal = 'menu-horizontal';

    echo "<ul class='menu'>";
    foreach ($users as $arr) {
                //чтоб отображалось одно из исключений напишем прерывание
                if ($id == 'autorise' && $arr['id'] != 'registration')
                    continue;
                elseif ($id == 'registration' && $arr['id'] != 'autorise')
                    continue;
        echo "<li class='menu-item $horizontal'><a href='{$arr['href']}'>{$arr['menu']}</a></li>";
    }
    echo "</ul>";
}

function drawOption ($menus, $id='', $NOadminPanel=false) {

    echo "<select class='options' name='option'>";
    foreach ($menus as $arr) {

        if($NOadminPanel){  //Исключение админки из меню выбора для изменения сайта
            if ( $arr['href'] == "$serverHTTP/admin.php")
                continue;
        }
        else {
            //Исключение эл-тов меню для постинга новостей в Гл. стр, в стр. добавления и адмнку
            if ( $arr["id"] == "index" || $arr['id'] == 'add' || $arr['id'] == 'admin') 
                continue;
                /////////////////////////////////
        }
        echo "<option ";
        
        if($id == $arr['id']) // Для админа и редакторов контента
            echo "selected "; // выставит автоматом куда добавлять основной контент на сайт

        echo "value='{$arr['id']}'>{$arr['menu']}</option>";
    }
    echo "</select>";
}

function choose ($id='index') {

    switch ($id) {

        case "soft" :
        include "inc/soft.php";
        break;

        case "films" :
        include "inc/films.php";
        break;

        case "music" :
        include "inc/music.php";
        break;

        case "games" :
        include "inc/games.php";
        break;

        case "add" :
        include "inc/posts.php";
        break;

        default :
            include "inc/index.inc.php";
    }
}

//Работа с куками////////
//установка кук на посещение




//Взаимодействие с БД///////
//Положить в БД, для постинга новостей
function insertDB ($link, $name, $optionName, $text, $textfull) {
    global $link;
    $optInsert = ''; //Переменная для задания: в какую таблицу пихать значения

    switch ($optionName) {
        case 'Фильмы' : $optInsert='films'; break;
        case 'Игры' : $optInsert='games'; break;
        case 'Музыка' : $optInsert='music'; break;
        case 'Софт' : $optInsert='soft'; break;

        default: 
            echo "Ошибка при добавлении: нет таблицы в базе или кода switch lib.php " . __LINE__ . " строка";
    }

    $sql = "INSERT INTO $optInsert (title, opt, description, descend) VALUES (?, ?, ?, ?)";
    //$result = mysqli_query($link, $sql);
    if ( !$result = mysqli_prepare ($link, $sql) ) {
        echo "Ошибка добавления в БД "
            . mysqli_errno($link)
            . " --- " 
            . mysqli_error($link);

        return false;
}
    mysqli_stmt_bind_param($result, 'ssss', $name, $optionName, $text, $textfull);
    mysqli_stmt_execute($result);
    mysqli_stmt_close($result);

    return true;
}

//Вынуть из БД
function selectDB ($link, $name, $optionName, $text, $id) { //id это от GET
    global $link;

    $optSelect = ''; //Переменная для задания: из какой таблицы брать значения, хотя можно просто взять id из GET

    switch ($id) {
        case 'films' : $optSelect='films'; break;
        case 'games' : $optSelect='games'; break;
        case 'music' : $optSelect='music'; break;
        case 'soft' : $optSelect='soft'; break;

        default: 
            echo "Ошибка при выборке: нет таблицы в базе или кода switch lib.php " . __LINE__ . " строка";
    }

    $sql = "SELECT id, title, opt, description
        FROM $optSelect
        ORDER BY id DESC";

    $result = mysqli_query($link, $sql);
    if (!$result)
        return false;

    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $items[] = $row;
    }
    return $items;
}

//Выборка одного поста для files.php
function selectOneDB ($link, $id, $iv) {
    global $link;

    $sql = "SELECT id, title, opt, description, descend
    FROM $id WHERE id = $iv ORDER BY id DESC";

    if ( !$result = mysqli_query ($link, $sql) ){
        echo "Ошибка выборки из БД - lib " . __LINE__;
        return false;
    }

    while ( $row = mysqli_fetch_array($result, MYSQLI_ASSOC) ) {
        $items[] = $row;
    }
    return $items;
}


//Добавление регистрируемых пользователей в БД
function insertUsersDB ($link, $login, $password, $name, $mail) {
    global $link;

    //Проверка есть ли такие юзвери в базе
    foreach(selectUsersDB ($link) as $arrs) //Цикл для прохода всех юзеров по логину и емейлу
    if ($login == $arrs['login'] || $name == $arrs['name']) { //Проверка каждого юзера в цикле
        echo "<h3>Пользователь с таким Логином или e-mail уже есть в базе</h3>";
        return false;
    }
/////////////////////////
    $password = getHash($password);
    $sql = "INSERT INTO users (login, password, name, mail) VALUES (?, ?, ?, ?)";

    if ( !$result = mysqli_prepare ($link, $sql) ) {
        echo "Ошибка добавления в БД "
            . mysqli_errno($link)
            . " --- " 
            . mysqli_error($link);

        return false;
}
    mysqli_stmt_bind_param($result, 'ssss', $login, $password, $name, $mail);
    mysqli_stmt_execute($result);
    mysqli_stmt_close($result);

    return true;
}
//Выборка юзеров из БД
function selectUsersDB ($link, $userlogin=false) {
    global $link;

    if($userlogin == false){ //Выборка всех юзеров
        $sql = "SELECT id, login, password, name, attachment, mail
                FROM users";
    }
    else { //Выборка одного юзера по логину
        $sql = "SELECT id, login, password, name, attachment, mail
                FROM users WHERE login = '$userlogin'";
    }

    if ( !$result = mysqli_query($link, $sql) ) {
        echo "Ошибка выборки юзеров из БД";
        return false;
    }

    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $items[] = $row;
    }
    return $items;
}
//Запрос на поиск юзеров
function sortUsersSearch ($link, $sql) {
    global $link;

    if ( !$result = mysqli_query($link, $sql) ) 
        return false;
    
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $items[] = $row;
    }
    return $items;
}

//Отображение админки
function drawChangeAttachment ($usersAttachment, $items, $id){ //Смена статуса пользователя
    echo "<form method='post' action='{$_SERVER['REQUEST_URI']}'>";
    echo "<input type='hidden' name='id' value='$id'>";
        echo "<select name='changeuser' class='changeuser'>";
        foreach($usersAttachment as $arr) {
            echo "<option ";
            
            if($arr['attachment'] == $items) //выбор по умолчанию, что отображать
                echo 'selected';

            echo " value='{$arr['attachment']}'>{$arr['type']}</option>";
        }
        echo "</select>";
        echo "<button type='submit' name='submit' value='change'>Изменить статус</button>";
        echo "<button type='submit' name='submit' value='remove'>Удалить юзера</button>";
    echo "</form>";
}
//Изменение статуса пользователя в БД

//Показ юзеров в контенте
function showUsers ($usersAttachment, $usersArray) {
    ?>
    <?php

    foreach ($usersArray as $items) {
    ?>
    <ul class='adminform'>
        <li><? drawChangeAttachment ($usersAttachment, $items['attachment'], $items['id']); ?>
        №: <?= $items["id"];
        ?></li>
        <li>Логин: <?= $items['login'] ?></li>
        <li>Имя: <?= $items['name'] ?></li>
        <li>Статус: <?= $items['attachment'] ?></li>
        <li>e-mail: <?= $items['mail'] ?></li>
    </ul>
    <?
    }
}

//Проверки паролей и авторизация
function getHash ($password) { //Кодирование пароля в md-5
    return strip_tags(
            trim(
                password_hash($password, PASSWORD_BCRYPT)));
}

//Удаление новостей
function delNewDB ($iv, $id='index', $content=0) {
    global $link;
//$id - таблица (страница) из которой удалять
//$iv - уникальный id новости для удаления
    $optSelect = ''; //Переменная для задания: из какой таблицы брать значения, хотя можно просто взять id из GET

    switch ($id) {
        case 'index' : $optSelect='index'; break;
        case 'films' : $optSelect='films'; break;
        case 'games' : $optSelect='games'; break;
        case 'music' : $optSelect='music'; break;
        case 'soft' : $optSelect='soft'; break;
        case 'add' : $optSelect='add'; break;

        default: 
            echo "Ошибка при удалении из $id: нет таблицы в базе или кода switch lib.php " . __LINE__ . " строка";
    }

    if ( empty($content) )
        $sql = "DELETE FROM $optSelect WHERE id = '$iv'";
    else
        $sql = "DELETE FROM $content WHERE id = $iv AND iv = '$id'";

    if ( !$result = mysqli_query($link, $sql) ){
        echo "Ошибка удаления";
        return false;
    }
    return true;
}

function sessionOK ($session){ //Покажет в какой сессии и кто, выведет кнопку выйти
    foreach ($session as $key => $arr){
        switch ($key){
            case 'user': $user = 'простой пользователь'; break;
            case 'superuser': $user = 'Модератор'; break;
            case 'admin': $user = 'АДМИН'; break;
            case 'ban': $user = 'нежелательная персона'; break;
            }
        echo "Вы в системе как $user $arr";
}
?>
<form action="<?= "$serverHTTP/inc/sessionScript.php" ?>" method="POST">
<button type="submit" name="destroy" value="destroy">Выйти</button>
</form>
<?
}

//Добавление комментария под постами
function insertCommentsDB ($link, $userlogin, $id, $iv, $text) { 
    global $link;

    $sql = "INSERT INTO comments (titlecategory, titleid, userlogin, text) 
            VALUES (?, ?, ?, ?)";

    if( !$result = mysqli_prepare($link, $sql) ) {
        echo "Ошибка добавления сообщения"
        . mysqli_errno($link)
        . " --- " 
        . mysqli_error($link);

    return false;
    }
    mysqli_stmt_bind_param($result, 'siss', $id, $iv, $userlogin, $text);
    mysqli_stmt_execute($result);
    mysqli_stmt_close($result);
    
    return true;
}
//Добавление комментария под постами
function selectCommentsDB ($link, $iv, $id) {
    global $link;

    $sql = "SELECT titlecategory, titleid, userlogin, text
            FROM comments WHERE titlecategory = '$id' AND titleid = $iv";

    if( !$result = mysqli_query($link, $sql) ) {
        //echo "Ошибка выборки из БД";
        return false;
    }

    while( $row = mysqli_fetch_array($result, MYSQLI_ASSOC) ) {
        $items[] = $row;

    }

    return $items;
}

///////////////Редактор страниц для админа
//Добавление комментария под постами
function insertRedactorDB ($link, $iv, $text, $redactor, $datetime, $button='add') { //$iv - Страница на которой отображать: index, films...
    global $link;
    global $showContentID;

    if($button == 'add'){
        $sql = "INSERT INTO content (iv, text, redactor, datetime) 
            VALUES (?, ?, ?, ?)";

            if( !$result = mysqli_prepare($link, $sql) ) {
                echo "Ошибка добавления сообщения"
                . mysqli_errno($link)
                . " --- " 
                . mysqli_error($link);
        
            return false;
            }
            mysqli_stmt_bind_param($result, 'ssss', $iv, $text, $redactor, $datetime);
            mysqli_stmt_execute($result);
            mysqli_stmt_close($result);
            
            echo "Сообщение отправлено!";
            return true;
    }
    elseif($button == 'redact'){
        $sql = "UPDATE content SET iv = '$iv', text = '$text', redactor = '$redactor', datetime = '$datetime' WHERE id = '$showContentID'";

        if( !$result = mysqli_query($link, $sql) ) {
            echo "Ошибка добавления сообщения "
            . mysqli_errno($link)
            . " --- " 
            . mysqli_error($link);

            return false;
        }

        echo "Сообщение отправлено!";

        return true;
    }


}
//Отображение комментариев из БД

function selectRedactorDB ($link, $iv) {
    global $link;

    $sql = "SELECT id, iv, text, redactor, datetime
            FROM content 
            WHERE iv = '$iv'
            ORDER BY id DESC";

    if( !$result = mysqli_query($link, $sql) ) {
        //echo "Ошибка выборки из БД";
        return false;
    }

    while( $row = mysqli_fetch_array($result, MYSQLI_ASSOC) ) {
        $items[] = $row;

    }

    return $items;
}
//Отображение контента на страницах
function showContent ($titleArray, $id='index', $show=true) {
    foreach($titleArray as $items) { 
        $showContentID = $items['id']; //Чтоб редактировать

        echo parse_bb($items['text']);

        if($show == true){
            if( isset($_SESSION['superuser']) || isset($_SESSION['admin']) ){
                echo "Добавлено редактором: {$items['redactor']} {$items['datetime']}";
                echo "<a href='$serverHTTP/inc/delNewsDB.php?id=$id&iv={$items['id']}&content=content'>Удалить</a>";
            }
        }
    }
return $showContentID;
}

function parse_bb ($str) { //Парсер bb кода
    $pattern = ['[h1]', '[/h1]',
                '[h2]', '[/h2]',
                '[b]', '[/b]',
                '[url]', '[/url]',
                '[u]', '[/u]',
                '[img]', '[/img]',
                '[blockquote]', '[/blockquote]'
            ];

    $replase = ['<h1>', '</h1>',
                '<h2>', '</h2>',
                '<b>', '</b>',
                '<a class="bb_link" href="', '">ССЫЛКА</a>',
                '<u>', '</u>',
                '<img src="', '"><br>',
                '<blockquote>', '</blockquote>'
    ];

    return str_replace($pattern, $replase, $str);
}