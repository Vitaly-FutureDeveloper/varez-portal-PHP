<?php
require_once "lib.php";
require_once "sessionScript.php";

//Обработка запросов
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($_POST['registration']){
        $login = strip_tags(trim($_POST['user']));
        $password = strip_tags(trim($_POST['password']));
        $name = strip_tags(trim($_POST['name']));
        $mail = strip_tags(trim($_POST['mail']));

        insertUsersDB ($link, $login, $password, $name, $mail);
        }
}

?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <!--<meta http-equiv="refresh"; content="50">-->
        <title></title>
        <link href="../css/main.css" type="text/css" rel="stylesheet">
    </head>

    <body>
        <h1><?= $titleH1 ?></h1>
        <div class="header">
            <h3><?= $wellcome, $wellcomeName ?></h3>
            <blockquote class="timer"><? echo "Последнее посещение: $lastVisit"; ?>
            <?
                drawUsers($users, 'horizontal');
            ?>
            </blockquote>
        </div>

        <main>

        <section class="leftmenu">
            <? drawMenu($menus); ?>
        </section>
        
        <section class="content">
        <?
        if(empty($_SESSION)){
            $id = $_GET['id'];
            if($id == 'autorise') { //Ловим и проверяем на что нажал пользователь
                drawUsers($users);
        ?>
                <form action="sessionScript.php" method="POST">
                    <input type="hidden" name="autorise" value="autorise">

                    <label><p>Логин:<input type="text" name="user"></p></label>
                    <label><p>Пароль:<input type="text" name="password"></p></label>
                    <button type="submit">Авторизация</button>
                </form>
            <?} elseif ($id == 'registration') { // Если нажал регистрация 
                drawUsers($users);
                ?> 
                <form action="<?= $_SERVER['REQUEST_URI']?>" method="POST">
                    <input type="hidden" name="registration" value="registration">

                    <label><p>Логин:<input type="text" name="user"></p></label>
                    <label><p>Пароль:<input type="text" name="password"></p></label>
                    <label><p>Реальное имя:<input type="text" name="name"></p></label>
                    <label><p>E-mail:<input type="text" name="mail"></p></label>
                    <p>Нажимая кнопку, ты согласен со всем</p>
                    <button type="submit">Регистрация</button>
                </form>
            <?}?>
        <?
        }
    else{
        sessionOK ($_SESSION);
        }
    ?>
            </section>
</main>

        <script src="../js/script.js"></script>
    </body>
</html>


