<?php
require_once 'config.php';
require_once 'vendor/autoload.php';
use Telegram\Bot\Api;

$telegram = new Api(TG_BOT_TOKEN);

$reply = "Привет-привет! Не хочешь поучить немного слов? Если да, то жми на кнопку ниже.";
$inlineKeyboard = [[[ 'text' => "Learn words", 'callback_data' => "learn" ]]];
$keyboard = [ 'inline_keyboard' => $inlineKeyboard ];
$reply_markup = json_encode($keyboard);

$db = new MysqliDb(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

$chatIds = $db->get('users_data', null, 'chat_id');
foreach ($chatIds as $chatId)
{
    $telegram->sendMessage([ 'chat_id' => $chatId, 'text' => $reply, 'reply_markup' => $reply_markup ]);
}


