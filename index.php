<?php
require_once "inc/lib.php";

/* ПЕРЕНАПРАВЛЯЕМ ПОЛЬЗОВАТЕЛЯ НА index.inc.php */
if(empty($_GET)){
    header("Location: $serverHTTP/index.php?id=index");
}


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
        <section class="header-pro">
            <blockquote class="wellcome">
                <h3><?= "$wellcome $wellcomeName " ?></h3>
                <div class="header-info timer">
                    <? echo "<p>Последнее посещение: $lastVisit</p>"; ?>
                </div>
            </blockquote>
            <blockquote class="header-info registration">
                <?
                    if(empty($_SESSION['admin']) && empty($_SESSION['superuser']) && empty($_SESSION['user']) && empty($_SESSION['ban']))
                        drawUsers($users, 'horizontal');
                    else
                        sessionOK($_SESSION); //Покажет в какой сессии и кто, выведет кнопку выйти
                ?>
            </blockquote>
        </section>
    </header>

    <main>

        <section class="leftmenu">
            <? drawMenu($menus); ?>
        </section>

        <section class="content">

            <!--Место для постинкга и изменения сайта-->
            <?
        if(isset($_SESSION['admin']) || isset($_SESSION['superuser']) )
                {
                    $arrayUsers = selectUsersDB ($link, $userlogin);
                ?>
            <button class="past-form">Править страницу:</button>
            <div class="form-insert-paste">
                <form method="post" action="inc/index.inc.php">
                    <fieldset>
                        <legend>
                            Править страницу:
                            <img src="/img/icons/ask.png" width="25px"
                                title="Контент будет отображаться вначале страницы, применять можно любые теги... Добавлять и редактировать каждую строку...">
                        </legend>

                        <input type="hidden" name="userlogin" value="<?= $userlogin ?>">
                        <!--Логин написавшего-->
                        <input type="hidden" name="timer" value="<?= $timer ?>">
                        <!--Время-->
                        <p>Выберите категорию:
                            <?php
                                    drawOption ($menus, $id, true);
                                    ?>
                        </p>
                        <p>
                            <? sessionOK ($_SESSION); ?>
                        </p>
                        <Label>
                            <p></p>
                            <textarea name="text"
                                class="postform-textarea"><? showContent ($titleArray, $id, 0); ?></textarea>
                        </Label>
                        <Label>
                            <p><button type="submit" name="button" value="add">Добавить</button></p>
                        </Label>
                        <Label>
                            <p><button type="submit" name="button" value="redact">Редактировать</button></p>
                        </Label>
                    </fieldset>
                </form>
                <?  }   else{
                        echo "<h3>Войдите или зарегистрируйтесь, чтоб оставлять комментарии</h3>";
                        drawUsers($users, 'horizontal');
                        }
            ?>
            </div>
            <?
                if($_SERVER['REQUEST_METHOD'] == 'GET'){
                    choose ($id); //Инклюдим страницу по id из GET
                    }
                else {
                    if( isset($titleArray) )
                        showContent ($titleArray, $id);
                }
            ?>
        </section>

        <section class="rightmenu">
            <? drawMenu($menus, 'vertical'); ?>
        </section>

    </main>

    <footer>
        <? drawMenu($menus, 'horizontal');  ?>
    </footer>

    <script src="/js/script.js"></script>
</body>

</html>