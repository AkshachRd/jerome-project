<?php
require_once 'MysqliDb.php';
require_once 'config.php';

//$db = new MysqliDb ('localhost', 'root', 'BJx@3~rQ_N98%tn', 'telegram_bot_db');
$db = new MysqliDb (DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

$db->where('chat_id', 401763451);
$maxWordNum = end($db->get('word_list', null, 'word_num'))["word_num"];

var_dump($maxWordNum);