<?php
require_once 'MysqliDb.php';

$db = new MysqliDb ('localhost', 'root', 'BJx@3~rQ_N98%tn', 'telegram_bot_db');

$wordNum = $db->rawQueryOne("SELECT word_num FROM word_list WHERE word='buy'")["word_num"];

var_dump($wordNum);