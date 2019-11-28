<? 
session_start();

require_once "lib.php";

//Проводим проверку с какой страницы пиришли и записываем в куки
if($_SERVER['HTTP_REFERER'] != "$serverHTTP/inc/sessionScript.php" &&
    $_SERVER['HTTP_REFERER'] != "$serverHTTP/inc/sessionForm.php?id=autorise" && 
    $_SERVER['HTTP_REFERER'] != "$serverHTTP/inc/sessionForm.php?id=registration")

    {
    $httpref = serialize($_SERVER['HTTP_REFERER']);
    setcookie('httpref', $httpref);
}
//Запишем в файл Страницу перехода с которой перешли
/* В файл записывать нельзя - будут конфликты, если 2 человека зайдут на сайт надо в Куки
if ( !file_exists('ref.txt') ) {
    file_put_contents('ref.txt', HTTPREF);
}
*/


if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $login = strip_tags(trim($_POST['user']));
    $password = strip_tags(trim($_POST['password']));

    if ($_POST['autorise'] == 'autorise'){
///Проверка логина - пароля
        $arrayUsers = selectUsersDB ($link);
        foreach ($arrayUsers as $hash){
            
            if($login == $hash['login']) {
                $pass = $hash['password'];
                if(!password_verify($password, $pass)){
                    echo "Не верная пара Логин или Пароль";
                    //continue;
                }
                else{
                    $_SESSION[$hash['attachment']] = $login;
                    }
            }
            else 
                continue;
        }
/* Меняем файл на куки
        $reffile = trim( file_get_contents('ref.txt') ); //Читаем содержимое файла с реф. ссылкой и записываем в переменную для заголовка
        unlink ('ref.txt'); //Удаляем файл с реф-ссылкой, он не нужен
*/
        header ("Location: " . unserialize($_COOKIE['httpref']));
        exit;
    }

    elseif ($_POST['destroy'] == 'destroy'){
        session_destroy();

        header ("Location: " . unserialize($_COOKIE['httpref']));
        exit;
    }
    else
        return;
}