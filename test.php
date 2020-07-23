<?php
require_once 'MysqliDb.php';
require_once 'config.php';

$link = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$chatId = 401763451;

$sql = 'SELECT MAX(word_num) FROM word_list WHERE chat_id = ' . $chatId;
$sqlResult = mysqli_query($link, $sql);

echo $maxWordNum = (int)mysqli_fetch_array($sqlResult)["MAX(word_num)"];
var_dump(mysqli_error($link));