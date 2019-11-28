<?
require_once "lib.php";


if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    $iv = $_GET['iv']; //$iv - уникальный id новости для удаления
    $id = $_GET['id']; //$id - таблица (страница) из которой удалять
    $content = $_GET['content'];

    if(empty($id)) 
        $id = 'index';
        
    if ( $result = delNewDB($iv, $id, $content) ) {
        header("Location: $_SERVER[HTTP_REFERER]");
        exit;
    }
    else
        echo "Ошибка удаления";
}
else
    echo "Ошибка передачи данных для удаления";