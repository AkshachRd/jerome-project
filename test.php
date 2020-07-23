<?php
require_once 'MysqliDb.php';
require_once 'config.php';

$chatId = 401763451;
$db = new MysqliDb (DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

$db->where('chat_id', $chatId);
$word = json_decode($db->getOne('users_data', 'temp_word_info')["temp_word_info"], true)["word"];



$db->where('chat_id', $chatId)->where('word', $word);
$wordNum = $db->getOne('word_list', 'word_num')["word_num"];

var_dump($wordNum);