<?php
function textEntered(object $telegram, mysqli $link, string $tempWordInfoFile, int $chatId, string $text): void
{
    if ($text == "/start")
    {
        $sql = 'INSERT users_data(chat_id) VALUES (' . $chatId . ')'; //Помещаю пользователя в БД
        mysqli_query($link, $sql);

        $reply = "Добро пожаловать в бота!\nЭтот бот призван помочь тебе выучить английские слова и их значения.\nТы можешь создать свой список слов и повторять их, когда тебе будет удобно. Бот будет присылать тебе оповещения каждый день в 18:30 по Москве с предложением повторять слова. Удачи!\nЧтобы начать пользоваться ботом, просто напиши слово или фразовый глагол в чат.";
        $keyboard = [["Learn words"]]; //Клавиатура
        $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => false ]);
        $telegram->sendMessage([ 'chat_id' => $chatId, 'text' => $reply, 'reply_markup' => $reply_markup ]);
    }
    elseif ($text == "Learn words")
    {
        learnWords($telegram, $link, $chatId);
    }
    else
    {
        $text = strtolower($text);
        $wordInfo = getWordInfo($text);

        if ($wordInfo["wordIsCorrect"])
        {
            //Здесь 2 кнопки: 'Определение' и 'Список'
            $inlineKeyboard = [[[ 'text' => "Definitions", 'callback_data' => "definitions" ], [ 'text' => "Add to the list", 'callback_data' => "add_to_the_list" ]]];
            $keyboard = [ 'inline_keyboard' => $inlineKeyboard ];
            $replyMarkup = json_encode($keyboard);

            printWordAndTranscription($telegram, $chatId, $replyMarkup, $wordInfo);

            if (!empty($pronunciations["audioUK"]))
            {
                $telegram->sendAudio([ 'chat_id' => $chatId, 'audio' => $pronunciations["audioUK"], 'title' => "British accent" ]);
            }
            if (!empty($pronunciations["audioUS"]))
            {
                $telegram->sendAudio([ 'chat_id' => $chatId, 'audio' => $pronunciations["audioUS"], 'title' => "American accent" ]);
            }

            //Вставляю в файл временный массив
            unset($wordInfo["wordIsCorrect"]);
            file_put_contents($tempWordInfoFile, "");
            file_put_contents($tempWordInfoFile, serialize($wordInfo));
        }
        else
        {
            $reply = "Слово введено с ошибкой! Перепиши. \(★ω★)/";
            $telegram->sendMessage([ 'chat_id' => $chatId, 'text' => $reply ]);
        }
    }
}

function printWordAndTranscription(object $telegram, int $chatId, string $replyMarkup, array $wordInfo): void
{
    $word = $wordInfo["word"];
    $pronunciations = $wordInfo["pronunciations"];
    $translation = $wordInfo["translation"];

    if (!empty($pronunciations["transcriptionUK"]))
    {
        $transcriptionUK = EMOJI_UK_FLAG . ":" . $pronunciations["transcriptionUK"];
    }
    else
    {
        $transcriptionUK = "";
    }
    if (!empty($pronunciations["transcriptionUS"]))
    {
        $transcriptionUS = EMOJI_US_FLAG . ":" . $pronunciations["transcriptionUS"];
    }
    else
    {
        $transcriptionUS = "";
    }

    if ($transcriptionUK === "" && $transcriptionUS === "")
    {
        $reply = "<b>$word</b>\n<b>$translation</b>";
    }
    elseif ($transcriptionUK === "")
    {
        $reply = "<b>$word</b>\n$transcriptionUS\n<b>$translation</b>";
    }
    elseif ($transcriptionUS === "")
    {
        $reply = "<b>$word</b>\n$transcriptionUK\n<b>$translation</b>";
    }
    else
    {
        $reply = "<b>$word</b>\n$transcriptionUK   $transcriptionUS\n<b>$translation</b>";
    }

    $telegram->sendMessage([ 'chat_id' => $chatId, 'text' => $reply, 'parse_mode' => "HTML", 'reply_markup' => $replyMarkup ]);
}
