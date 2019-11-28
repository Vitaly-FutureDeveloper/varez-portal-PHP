<?php
require_once "inc/lib.php";

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    $id = $_GET['id'];
    $iv = $_GET['iv'];
}

//Берём из БД все комменты на эту страницу и заносим в массив для форыча
$arrayComments = selectCommentsDB ($link, $iv, $id); 
/*
if(isset($_SESSION['admin']))
    $userlogin = $_SESSION['admin']; //Логин юзера из БД
elseif(isset($_SESSION['superuser']))
    $userlogin = $_SESSION['superuser']; //Логин юзера из БД
elseif(isset($_SESSION['user']))
    $userlogin = $_SESSION['user']; //Логин юзера из БД
*/
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $iv = $_POST['iv'];
    $userlogin = $_POST['userlogin'];
    $text = trim(strip_tags($_POST['text']));
//Вносим комментарий к посту в базу 
    insertCommentsDB ($link, $userlogin, $id, $iv, $text);

    //Перезагрузка
    header ("Location: {$_SERVER['REQUEST_URI']}");
    exit;
}
/*
echo "<pre>";
var_dump(selectCommentsDB ($link, $iv, $id));
echo "</pre>";
*/
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <!--<meta http-equiv="refresh"; content="50">-->
        <title></title>
        <link href="css/main.css" type="text/css" rel="stylesheet">
    </head>

    <body>

        <header div class="header">
        <h1><?= $titleH1 ?></h1>
            <h3><?= $wellcome, $wellcomeName ?></h3>
            
            <blockquote class="timer"><? echo "Последнее посещение: $lastVisit"; ?>
            
            <?
                if(empty($_SESSION))
                    drawUsers($users, 'horizontal');
                else
                    sessionOK ($_SESSION);
            ?>
            </blockquote>

            
        </header>

        <main>

        <section class="leftmenu">
            <? drawMenu($menus); ?>
        </section>

        <section class="content">
        
        <div class="product">
            <?
            if ( !$_SERVER['REQUEST_METHOD'] == 'GET' ) {
                echo "Ошибка передачи параметров";
                exit;
            }
            
            $id = $_GET['id'];
            $iv = $_GET['iv'];
            
            $arraySelect = selectOneDB ($link, $id, $iv);

            foreach($arraySelect as $arr) {
            ?>
                <h2><?= $arr['title'] ?></h2>
                <p>Категория: <?= $arr['opt'] ?></p>
                <p>Описание: <?= $arr['id'] . " " . parse_bb($arr['description']); ?></p>
                <p><?= parse_bb($arr['descend']); ?></p>
            <? } ?>
        </div>

        <div class="form-insert-comments">
                <!--Место для комментов под каждым постом-->
                <?
                if(isset($_SESSION['ban']))
                    echo "<h3>Вы забанены и не можете оставлять комментарии</h3>";

                elseif(isset($_SESSION['admin']) || isset($_SESSION['superuser']) || isset($_SESSION['user']))
                {
                    $arrayUsers = selectUsersDB ($link, $userlogin);
                ?>
                    <form method="post" action="<?= $_SERVER['REQUEST_URI'] ?>">
                        <fieldset><legend>Оставить комментарий:</legend>
                            <input type="hidden" name="userlogin" value="<?= $userlogin ?>"><!--Логин написавшего-->
                            <input type="hidden" name="id" value="<?= $id ?>"><!--В какую таблицу написал-->
                            <input type="hidden" name="iv" value="<?= $iv ?>"><!--Под каким постом-->
                            <p><?sessionOK ($_SESSION); ?> </p>
                            <Label><p></p>
                            <textarea name="text" class="postform-textarea"></textarea></Label>
                            <Label><p><button type="submit">Добавить</button></p></Label>
                        </fieldset>
                    </form>
            <?  }   else{
                        echo "<h3>Войдите или зарегистрируйтесь, чтоб оставлять комментарии</h3>";
                        drawUsers($users, 'horizontal');
                        }
            ?>
        </div>

        <div class="comments">
        <h3>Комментарии:</h3>
            <?
            if ( isset($arrayComments) ) {
                foreach($arrayComments as $item) {
                ?>
                <div class="comment">
                    <h4><?= $item['userlogin']?> написал:</h4>
                    <p><?= $item['text']?></p>
                </div>
                <?
                }
            }
            else {
                echo "<h4>Пока нет комментариев</h4>";
            }
            ?>
        
        
        
        </div>
        </section>

        <section class="rightmenu">
            <? drawMenu($menus, 'vertical'); ?>
        </section>

        </main>

        <footer>
            <? drawMenu($menus, 'horizontal'); ?>
        </footer>

        <script src="/js/script.js"></script>
    </body>
</html>
