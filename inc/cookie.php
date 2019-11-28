<?
//Работа с куками////////
$lastVisit = "";

    if(isset($_COOKIE['lastVisit']))
        $lastVisit = date("Y-m-d | H:m:s", $_COOKIE['lastVisit']); //Вначале запишем в переменную для показа последнее посещение
    setcookie('lastVisit', time(), 0x7FFFFFFF); //Положим в КУКИ новое время
