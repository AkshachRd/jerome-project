<?php
require_once 'config.php'; //Константы
require_once 'apiRequestHandler.php'; //Функции для работы с API
require_once 'textHandler.php'; //Функции, работающие при вводе и выводе текста
require_once 'buttonsHandler.php'; //Функции, работающие при нажатии на кнопки
require_once 'vendor/autoload.php';
use Telegram\Bot\Api;

$link = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME); //Подключаюсь к базе данных

$telegram = new Api(TG_BOT_TOKEN); //Устанавливаем токен, полученный у BotFather
$result = $telegram -> getWebhookUpdates(); //Передаем в переменную $result полную информацию о сообщении пользователя

$text = $result["message"]["text"]; //Текст сообщения
$chatId = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
$name = $result["message"]["from"]["username"]; //Юзернейм пользователя
$callbackQuery = $result["callback_query"]; //Запрос, возвращенный кнопкой

$tempWordInfoFile = 'tempWordInfoFile.txt'; //Временный файл для хранения массива с информацией о слове
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                                /* Основаная программа */
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (!empty($callbackQuery))
{
    getButtonAnswer($telegram, $link, $tempWordInfoFile, $callbackQuery);
}
elseif (!empty($text))
{
    textEntered($telegram, $link, $tempWordInfoFile, $chatId, $text);
}
else
{
    $telegram->sendMessage([ 'chat_id' => $chatId, 'text' => "Отправьте текстовое сообщение." ]);
}