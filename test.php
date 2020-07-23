<?php
require_once 'MysqliDb.php';

$db = new MysqliDb ('localhost', 'root', 'BJx@3~rQ_N98%tn', 'telegram_bot_db');

function addWordToList(object $db, int $chatId, string $word): void
{
    $wordNum = $db->rawQueryOne("SELECT MAX(word_num) FROM word_list WHERE chat_id=$chatId")["MAX(word_num)"];

    if ($wordNum !== null)
    {
        //В $wordNum номер последнего слова. Добавляю следующее слово в список
        addWordToDBList($db, $chatId, ++$wordNum, $word);
    }
    else
    {
        //Номера последнего слова нет. Добавляю первое слово в списке
        addWordToDBList($db, $chatId, 1, $word);
    }
}

function addWordToDBList(object $db, int $chatId, int $wordNum, string $word): void
{
    $data = array(
        "chat_id" => $chatId,
        "word_num" => $wordNum,
        "word" => $word
    );
    $db->insert('word_list', $data);
}

