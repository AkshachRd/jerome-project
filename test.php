<?php
require_once 'config.php';

$link = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

$chatId = 401763451;

$sql = 'SELECT which_words_to_learn FROM users_data WHERE chat_id = ' . $chatId;
$sqlResult = mysqli_query($link, $sql);

$str = mysqli_fetch_array($sqlResult)["which_words_to_learn"];
$whichWordsToLearn = explode(' ', $str);
var_dump($whichWordsToLearn);