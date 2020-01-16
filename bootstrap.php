<?php
require 'vendor/autoload.php';
use Dotenv\Dotenv;
use Src\Sys\db_conn;

$dotenv = new DotEnv(__DIR__);
$dotenv->load();

$dbConn = (new db_conn())->getConnection();


?>