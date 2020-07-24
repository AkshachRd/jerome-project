<?php
require_once 'config.php';

$link = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

//$chatId = 401763451;

$str = '123456';
$str = str_replace($str[0], '', $str);
var_dump($str);