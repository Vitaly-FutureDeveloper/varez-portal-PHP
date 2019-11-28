<?php
require_once "lib.php";

if (empty($_SESSION['admin'])) {
    echo "<h1>Админ не дал вам доступ к управлению)) не хороший админ)))</h1>";
    echo "<a href='$serverHTTP'>Перейти на главную</a>";
    exit;
}

$usersArray = selectUsersDB ($link);

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    if($_REQUEST['submit'] == "search"){
        $searchIn = $_POST['searchuser'];
        
        foreach($usersArray as $arr) {
            if($arr['login'] != $searchIn)
                continue;
            else {
                echo "Пользователь найден по логину";
                $sql = "SELECT id, login, password, name, attachment, mail
                        FROM users WHERE login = '$searchIn'";

                $use = sortUsersSearch ($link, $sql);
                showUsers ($usersAttachment, $use);
            }
        }

        foreach($usersArray as $arr) {
            if($arr['name'] != $searchIn)
                continue;
            else {
                $sql = "SELECT id, login, password, name, attachment, mail
                        FROM users WHERE name = '$searchIn'";
                echo "Пользователь найден по имени";
                $use = sortUsersSearch ($link, $sql);
                showUsers ($usersAttachment, $use);
            }
            break; //Без этого ищет дважды
        }

        foreach($usersArray as $arr) {
            if($arr['mail'] != $searchIn)
                continue;
            else {
                $sql = "SELECT id, login, password, name, attachment, mail
                        FROM users WHERE mail = '$searchIn'";
                echo "Пользователь найден по емейл";
                $use = sortUsersSearch ($link, $sql);
                showUsers ($usersAttachment, $use);
            }
        }
        exit;
    }

    if($_REQUEST['submit'] == "change"){
        echo $id = (int) $_REQUEST['id'];
        $searchTo = $_POST['changeuser'];
        $sql = "UPDATE users SET attachment = \"$searchTo\" WHERE id = $id";

        if( !$result = mysqli_query($link, $sql) )
            echo "Ошибка изменения";
        echo "Статус пользователя стал: $searchTo";
    }

    if($_REQUEST['submit'] == "remove"){
        echo $id = $_REQUEST['id'];

        $sql = "DELETE FROM users WHERE id = '$id'";

        if( !$result = mysqli_query($link, $sql) )
            echo "Ошибка удаления";
    }
}


?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="refresh"; content="50">
        <title>Админ-панель</title>
        <link href="/css/main.css" type="text/css" rel="stylesheet">
    </head>

    <body>
        <h1><?= $titleH1 ?></h1>
        <div class="header">
            <h3><?= $wellcome, $wellcomeName ?></h3>
            <blockquote class="timer"><? echo "Последнее посещение: $lastVisit"; ?></blockquote>
            <?
                //drawUsers($users, 'horizontal');
            ?>
            </form>
        </div>

        <main>
        
        <section class="leftmenu">
            <? drawMenu($menus); ?>
        </section>

        <section class="content">

        <form method="POST" action="<?$_SERVER['REQUEST_URI']?>">
            <input type="hidden" name="search" value="search">

            <input type="search" name="searchuser">
            <button type="submit" name='submit' value='search'>Поиск пользователей</button>
        </form>

        <?php
            showUsers ($usersAttachment, $usersArray);
        ?>
        </section>
        
        </main>
    <script src="../js/script.js"></script>
</body>
</html>


