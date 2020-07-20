<?php
require_once 'MysqliDb.php'; //Библиотека для подключения к БД
require_once 'config.php'; //Константы
require_once 'getWordInfo.php'; //Функции для работы с API
require_once 'vendor/autoload.php';
use Telegram\Bot\Api;

$db = new MysqliDb (DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME); //Подключаюсь к базе данных

$telegram = new Api(TG_BOT_TOKEN); //Устанавливаем токен, полученный у BotFather
$result = $telegram -> getWebhookUpdates(); //Передаем в переменную $result полную информацию о сообщении пользователя

$text = $result["message"]["text"]; //Текст сообщения
$chatId = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
$name = $result["message"]["from"]["username"]; //Юзернейм пользователя
$callbackQuery = $result["callback_query"];

//Временно работаю с файлом, потом переделаю по работу с БД
$definitionsFileName = 'definitions.txt';

if (!empty($callbackQuery))
{
    $callbackQueryData = $callbackQuery["data"];
    $chatId = $callbackQuery["message"]["chat"]["id"];
    $inlineKeyboard = [[]];

    if ($callbackQueryData === 'definitions')
    {
        //Получаю из БД временный массив
        $definitionsByPartOfSpeech = getTempWordInfoFromDB($db, $chatId)["definitionsByPartOfSpeech"];

        foreach ($definitionsByPartOfSpeech as $partOfSpeech => $lexemes)
        {
            $partOfSpeechText = $partOfSpeech;
            $partOfSpeechText[0] = strtoupper($partOfSpeechText[0]);

            array_push($inlineKeyboard[0], [ 'text' => $partOfSpeechText, 'callback_data' => $partOfSpeech ]);
        }


        //Здесь возможные кнопки 'существительное', 'глагол', 'прилагательное', 'наречие', 'междометие'
        $keyboard = [ 'inline_keyboard' => $inlineKeyboard ];
        $reply_markup = json_encode($keyboard);

        $reply = "What part of speech is your word?" . $reply_markup;

        $telegram->sendMessage([ 'chat_id' => $chatId, 'text' => $reply, 'reply_markup' => $reply_markup ]);
    }
    elseif ($callbackQueryData === 'noun' || $callbackQueryData === 'verb' || $callbackQueryData === 'adjective' || $callbackQueryData === 'adverb' || $callbackQueryData === 'interjection')
    {
        //Получаю из БД временный массив
        $definitionsByPartOfSpeech = getTempWordInfoFromDB($db, $chatId)["definitionsByPartOfSpeech"];

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
            $inlineKeyboard[0][$index - 1] = [ 'text' => "$index", 'callback_data' => "$index" ];
        }

        $reply .= "\nIf you want to add a definition with a word to the list, then choose the one that you like the most.";

        //Здесь разлчное количество кнопок: от 1 до 3, в виде '1', '2', '3'
        $keyboard = [ 'inline_keyboard' => $inlineKeyboard ];
        $reply_markup = json_encode($keyboard);

        $telegram->sendMessage([ 'chat_id' => $chatId, 'text' => $reply, 'parse_mode' => "HTML", 'reply_markup' => $reply_markup ]);
    }
}
elseif (!empty($text))
{
    if ($text == "/start")
    {
        $reply = "Добро пожаловать в бота!\nЭтот бот призван помочь тебе выучить ОнГлИйСкИе слова. Ты можешь создать 
            свой список слов и повторять их, когда тебе будет удобно. Бот будет присылать тебе оповещения ";
        $telegram->sendMessage([ 'chat_id' => $chatId, 'text' => $reply ]);
    }
    else
    {
        $text = strtolower($text);
        //TODO Сделать программное ограничение на количество запростов в сутки всех пользователей
        $wordInfo = getWordInfo($text);

        if ($wordInfo["wordIsCorrect"])
        {
            $pronunciations = $wordInfo["pronunciations"];
            $definitionsByPartOfSpeech = $wordInfo["definitionsByPartOfSpeech"];
            $translation = $wordInfo["translation"];

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
            if ($transcriptionUK === "" && $transcriptionUS === "")
            {
                $reply = "<b>$text</b>\n<b>$translation</b>";
            }
            elseif ($transcriptionUK === "")
            {
                $reply = "<b>$text</b>\n$transcriptionUS\n<b>$translation</b>";
            }
            elseif ($transcriptionUS === "")
            {
                $reply = "<b>$text</b>\n$transcriptionUK\n<b>$translation</b>";
            }
            else
            {
                $reply = "<b>$text</b>\n$transcriptionUK   $transcriptionUS\n<b>$translation</b>";
            }

            //Здесь 2 кнопки: 'Определение' и 'Список'
            $inlineKeyboard = [[[ 'text' => "Definitions", 'callback_data' => "definitions" ], [ 'text' => "Add to the list", 'callback_data' => "list" ]]];
            $keyboard = [ 'inline_keyboard' => $inlineKeyboard ];
            $reply_markup = json_encode($keyboard);

            $telegram->sendMessage([ 'chat_id' => $chatId, 'text' => $reply, 'parse_mode' => "HTML", 'reply_markup' => $reply_markup ]);

            //TODO Сдеалть преобразование mp3 в ogg, и передавать их как sendVoice
            if (!empty($pronunciations["audioUK"]))
            {
                $telegram->sendAudio([ 'chat_id' => $chatId, 'audio' => $pronunciations["audioUK"], 'title' => "British accent" ]);
            }
            if (!empty($pronunciations["audioUS"]))
            {
                $telegram->sendAudio([ 'chat_id' => $chatId, 'audio' => $pronunciations["audioUS"], 'title' => "American accent" ]);
            }

            //Вставляю в БД временный массив
            insertTempWordInfoToDB($db, $chatId, $wordInfo);
        }
        else
        {
            $reply = "Слово введено с ошибкой! Перепиши. \(★ω★)/";
            $telegram->sendMessage([ 'chat_id' => $chatId, 'text' => $reply ]);
        }
    }
}
else
{
    $telegram->sendMessage([ 'chat_id' => $chatId, 'text' => "Отправьте текстовое сообщение." ]);
}

function insertTempWordInfoToDB(object $db, int $chatId, array $tempWordInfo): void
{
    $data = array(
        "chat_id" => $chatId,
        "temp_word_info" => json_encode($tempWordInfo)
    );

    //$db->update('users_data', [ "temp_word_info" => null ]);
    $db->insert('users_data', $data);
}

function getTempWordInfoFromDB(object $db, int $chatId): ?array
{
    $db->where('chat_id', $chatId);

    return json_decode($db->getOne('users_data', 'temp_word_info')["temp_word_info"], true);

    $db->disconnect();
}