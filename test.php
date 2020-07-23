<?php
require_once 'MysqliDb.php';
require_once 'config.php';

$link = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$chatId = 40176345;

$sql = 'SELECT * FROM users_data WHERE chat_id = ' . $chatId;
$sqlResult = mysqli_query($link, $sql);

var_dump(mysqli_fetch_array($sqlResult));