<?php
include ('vendor/autoload.php'); //Подключаем библиотеку
use Telegram\Bot\Api;

require_once 'getWordInfo.php';

$telegram = new Api('861121918:AAE1caaPhjPytqAhgEWdXaG9azEQIyVmcJs'); //Устанавливаем токен, полученный у BotFather
$result = $telegram -> getWebhookUpdates(); //Передаем в переменную $result полную информацию о сообщении пользователя

$text = $result["message"]["text"]; //Текст сообщения
$chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
$name = $result["message"]["from"]["username"]; //Юзернейм пользователя
$callbackQuery = $result["callback_query"];

//Временно работаю с файлом, потом переделаю по работу с БД
$definitionsFileName = 'definitions.txt';

if (!empty($callbackQuery))
{
    $callbackQueryData = $callbackQuery["data"];
    $chat_id = $callbackQuery["message"]["chat"]["id"];
    $inlineKeyboard = [[]];

    if ($callbackQueryData === 'definitions')
    {
        //Получаю определения из файла
        $fileText = file_get_contents($definitionsFileName);
        $definitionsByPartOfSpeech = unserialize($fileText);

        foreach ($definitionsByPartOfSpeech as $partOfSpeech => $lexemes)
        {
            $partOfSpeechText = $partOfSpeech;
            $partOfSpeechText[0] = strtoupper($partOfSpeechText[0]);

            array_push($inlineKeyboard[0], [ 'text' => $partOfSpeechText, 'callback_data' => $partOfSpeech ]);
        }

        $keyboard = [ 'inline_keyboard' => $inlineKeyboard ];
        $reply_markup = json_encode($keyboard);

        $reply = "What part of speech is your word?";

        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup ]);
    }
    elseif ($callbackQueryData === 'noun' || $callbackQueryData === 'verb' || $callbackQueryData === 'adjective' || $callbackQueryData === 'adverb' || $callbackQueryData === 'interjection')
    {
        //Получаю определения из файла
        $fileText = file_get_contents($definitionsFileName);
        $definitionsByPartOfSpeech = unserialize($fileText);

        foreach ($definitionsByPartOfSpeech[$callbackQueryData] as $index => $sense)
        {
            $definition = $sense["definition"];
            $usageExample = $sense["usageExample"];
            $index++;

            $reply .= "<b>$index.</b> $definition\n";
            if (!empty($usageExample))
            {
                $reply .= "Usage example: <i>$usageExample</i>\n";
            }
        }

        $reply .= "\nIf you want to add a definition with a word to the list, then choose the one that you like the most.";

        $inlineKeyboard = [[[ 'text' => "1", 'callback_data' => "first" ], [ 'text' => "2", 'callback_data' => "second" ], [ 'text' => "3", 'callback_data' => "third" ]]];
        $keyboard = [ 'inline_keyboard' => $inlineKeyboard ];
        $reply_markup = json_encode($keyboard);

        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply, 'parse_mode' => "HTML", 'reply_markup' => $reply_markup ]);
    }
}
else
{
    if (!empty($text))
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

                //Здесь 2 кнопки: 'Определение' и 'Список'
                $inlineKeyboard = [[[ 'text' => "Definitions", 'callback_data' => "definitions" ], [ 'text' => "Add to the list", 'callback_data' => "list" ]]];
                $keyboard = [ 'inline_keyboard' => $inlineKeyboard ];
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

                //Сохраняю определения слова в файл
                $fileText = serialize($wordInfo["definitionsByPartOfSpeech"]);
                file_put_contents($definitionsFileName, $fileText);
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
}


function workingWithInlineKeyboardButtons($telegram)
{
    $result = $telegram -> getWebhookUpdates();

    $callbackQueryData = $result["callback_query"]["data"];
}
?>