<?php
require_once "sessionScript.php";
include_once "cookie.php";


$serverHTTP = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST']; //Переменная сервера HTTP://war.loc

$titleH1 = "Учебная практика Варез-портал на PHP";

$menus = [
    ['menu' => 'Главная', 'href' => "$serverHTTP/index.php?id=index", 'id' => 'index'],
    ['menu' => 'Софт', 'href' => "$serverHTTP/index.php?id=soft", 'id' => 'soft'],
    ['menu' => 'Фильмы', 'href' => "$serverHTTP/index.php?id=films", 'id' => 'films'],
    ['menu' => 'Музыка', 'href' => "$serverHTTP/index.php?id=music", 'id' => 'music'],
    ['menu' => 'Игры', 'href' => "$serverHTTP/index.php?id=games", 'id' => 'games'],
    ['menu' => 'Добавить', 'href' => "$serverHTTP/index.php?id=add", 'id' => 'add'],
    ['menu' => 'Админка', 'href' => "$serverHTTP/inc/admin.php", 'id' => 'admin']
];

$users = [
    ['menu' => 'Регистрация', 'href' => "$serverHTTP/inc/sessionForm.php?id=registration", 'id' => 'registration'],
    ['menu' => 'Авторизация', 'href' => "$serverHTTP/inc/sessionForm.php?id=autorise", 'id' => 'autorise']
];

$usersAttachment = [ //Для админки, управление статусами 
    ['type' => 'Админ', 'attachment' => 'admin'],
    ['type' => 'Модератор', 'attachment' => 'superuser'],
    ['type' => 'Простой пользователь', 'attachment' => 'user'],
    ['type' => 'Бан', 'attachment' => 'ban']
];

if($_SERVER['REQUEST_METHOD'] == 'GET')
    $id = $_GET['id'];
else
    $id = 'index';

if(isset($_SESSION['admin']))
    $userlogin = $_SESSION['admin']; //Логин юзера из БД
elseif(isset($_SESSION['superuser']))
    $userlogin = $_SESSION['superuser']; //Логин юзера из БД
elseif(isset($_SESSION['user']))
    $userlogin = $_SESSION['user']; //Логин юзера из БД


setlocale(LC_ALL, "utf-8");
$timer = date("Сегодня Y-m-i Сейчас H:i:s");
$hour = strftime("%H");
$wellcome = "";


    if($hour<6)
        $wellcome = "Good night ";
    elseif ($hour >= 7 && $hour <=12) 
        $wellcome = "Good morning ";
    elseif ($hour >= 13 && $hour <= 17) 
        $wellcome = "Good day ";
    else 
        $wellcome = "Good evening ";


///
//Переменные и свойства для работы с БД
const DB_HOST = 'localhost';
const DB_LOGIN = 'root';
const DB_PASSWORD = '';
const DB_NAME = 'war';

$link = mysqli_connect(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_NAME);
        if(!$link) {
            echo "Ошибка соединения с БД: "
            . mysqli_connect_errno()
            . " : " 
            . mysqli_connect_error();
    }
