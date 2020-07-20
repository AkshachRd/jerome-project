<?php
require_once 'MysqliDb.php';
require_once 'config.php';

$db = new MysqliDb (DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME); //Подключаюсь к базе данных

$chatId = 5;
$str = '{"wordIsCorrect": false}';

$data = array(
    "chat_id" => $chatId,
    "temp_word_info" => $str
);

$id = $db->insert('users_data', $data);
if ($id)
{
    echo 'Всё ок!';
}

