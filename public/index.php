<?php
/*
Подключение  файлов
*/
require "../bootstrap.php";
use Src\Ctrl\IssueCtrl;

/*
Установка заголовков http ответа
*/
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");

/*
Получение и парсинг url запроса
*/
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );
if ($uri[1] !== 'public') {
    header("HTTP/1.1 404 Not Found");
   echo json_encode( $uri[1]);
    exit();
}


/*
Получение id записи, если есть
*/
$issueid = null;
if (isset($uri[2])) {
    $issueid = (int) $uri[2];
}

/*
Получение Типа http запроса
*/
$requestMethod = $_SERVER["REQUEST_METHOD"];
/*
Передача, соединения с БД, тип запроса и id и запуск обработчика (контроллера)
*/
$controller = new IssueCtrl($dbConn, $requestMethod, $issueid);
$controller->processRequest();


?>