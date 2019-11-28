<?php
require_once "lib.php";
/*
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $titleArray = selectRedactorDB ($link, $id);
}
else*/
    $titleArray = selectRedactorDB ($link, 'index'); // Без этого на главной не сработает



if($_SERVER['REQUEST_METHOD'] == 'POST'){

    if(isset($_SESSION['admin']))
        $userlogin = $_POST['userlogin']; //Логин юзера из БД
    elseif(isset($_SESSION['superuser']))
        $userlogin = $_POST['userlogin']; //Логин юзера из БД



    $datetime = trim(strip_tags($_POST['timer']));
    $text = trim($_POST['text']);
    $iv = trim(strip_tags($_POST['option'])); //Страница на которой отображать: index, films...
    $button = $_POST['button'];

    if($button == 'add'){ //Если нажали на кнопку ДОБАВИТЬ
        if( isset($datetime) && isset($text) && isset($iv) )
            insertRedactorDB ($link, $iv, $text, $userlogin, $datetime);
        else
            echo "<h2>Заполните все поля</h2>";
    }
    elseif($button == 'redact'){ ////Если нажали на кнопку РЕДАКТИРОВАТЬ
        if( isset($datetime) && isset($text) && isset($iv) ){
            insertRedactorDB ($link, $iv, $text, $userlogin, $datetime, $button);
            echo "<pre>";
            print_r($_REQUEST);
            echo "</pre>";
            }
        else
            echo "<h2>Ошибка редактирования</h2>";
    }
}
/*
echo "<pre>";
var_dump($_POST);
echo "</pre>";*/
?>


<? 
if( isset($titleArray) ){
    showContent ($titleArray, $id);
}

 ?>


<?echo $optionName . "<br> " . $name . "<br> " . $option . "<br> " . $text;?>