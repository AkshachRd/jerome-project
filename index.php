<?php
include ('vendor/autoload.php'); //Подключаем библиотеку
use Telegram\Bot\Api;

require_once 'getWordInfo.php';

$telegram = new Api('861121918:AAE1caaPhjPytqAhgEWdXaG9azEQIyVmcJs'); //Устанавливаем токен, полученный у BotFather
$result = $telegram -> getWebhookUpdates(); //Передаем в переменную $result полную информацию о сообщении пользователя
var_dump($result);

$text = $result["message"]["text"]; //Текст сообщения
$chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
$name = $result["message"]["from"]["username"]; //Юзернейм пользователя

if (isset($text))
{
    if ($text == "/start")
    {
        $reply = "Добро пожаловать в бота!";
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);
    }
    else
    {
        $text = strtolower($text);
        //TODO Сделать программное ограничение на количество запростов в сутки всех пользователей
        $wordInfo = getWordInfo($text);
        if ($wordInfo["wordIsCorrect"])
        {
            $pronunciations = $wordInfo["pronunciations"];

            if (!empty($pronunciations["transcriptionUK"]))
            {
                $transcriptionUK = "\xF0\x9F\x87\xAC\xF0\x9F\x87\xA7:" . $pronunciations["transcriptionUK"];
            }
            else
            {
                $transcriptionUK = "";
            }
            if (!empty($pronunciations["transcriptionUS"]))
            {
                $transcriptionUS = "\xF0\x9F\x87\xBA\xF0\x9F\x87\xB8:" . $pronunciations["transcriptionUS"];
            }
            else
            {
                $transcriptionUS = "";
            }

            $text[0] = strtoupper($text[0]);
            $reply = "<b>$text</b>\n$transcriptionUK   $transcriptionUS";
            $inlineKeyboard = [[[ 'text' => "Definition", 'callback_data' => "definition" ],[ 'text' => "List", 'callback_data' => "list" ]]];
            $keyboard = [ 'inline_keyboard' => $inlineKeyboard];
            $reply_markup = json_encode($keyboard);
            $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply, 'parse_mode' => "HTML", 'reply_markup' => $reply_markup ]);

            //TODO Сдеалть преобразование mp3 в ogg, и передавать их как sendVoice
            if (!empty($pronunciations["audioUK"]))
            {
                $telegram->sendAudio([ 'chat_id' => $chat_id, 'audio' => $pronunciations["audioUK"], 'title' => "British accent" ]);
            }
            if (!empty($pronunciations["audioUS"]))
            {
                $telegram->sendAudio([ 'chat_id' => $chat_id, 'audio' => $pronunciations["audioUS"], 'title' => "American accent" ]);
            }
        }
        else
        {
            $reply = "Слово введено с ошибкой! Перепиши. \(★ω★)/";
            $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);
        }
    }
}
else
{
    $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "Отправьте текстовое сообщение." ]);
}

function workingWithInlineKeyboardButtons($telegram)
{
    $result = $telegram -> getWebhookUpdates();
}
?>







