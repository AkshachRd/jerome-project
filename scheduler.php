<?php
require_once 'config.php';
require_once 'vendor/autoload.php';
use Telegram\Bot\Api;

$link = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

$telegram = new Api(TG_BOT_TOKEN);

$reply = "Привет-привет! Не хочешь поучить немного слов? Если да, то жми на кнопку ниже.";

$sql = 'SELECT chat_id FROM users_data';
$sqlResult = mysqli_query($link, $sql);

while ($chatId = mysqli_fetch_array($sqlResult))
{
    $telegram->sendMessage([ 'chat_id' => $chatId, 'text' => $reply ]);
}