<?php
require_once 'MysqliDb.php';
require_once 'config.php';

//$db = new MysqliDb ('localhost', 'root', 'BJx@3~rQ_N98%tn', 'telegram_bot_db');
$db = new MysqliDb (DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

function getNumOfWordInList(object $db, int $chatId, string $word): ?int
{
    $db->where('chat_id', $chatId)->where('word', $word);

    return  $db->getOne('word_list', 'word_num')["word_num"];
}

var_dump(getNumOfWordInList($db, 401763451, 'Father'));