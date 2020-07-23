<?php
require_once 'MysqliDb.php';

$db = new MysqliDb ('localhost', 'root', 'BJx@3~rQ_N98%tn', 'telegram_bot_db');

$db->where('chat_id', '5')->where('word', 'father');
$wordNum = $db->getOne('word_list', 'word_num')["word_num"];

var_dump($wordNum);