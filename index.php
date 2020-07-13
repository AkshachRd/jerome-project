<?php
require __DIR__ . '/vendor/autoload.php';

$bot_api_key = '861121918:AAE1caaPhjPytqAhgEWdXaG9azEQIyVmcJs';
$bot_username = 'Jerome Project Bot';

try {
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);
    $server_response = $telegram->handle();
    var_dump($server_response);
    $entityBody = $telegram->getCustomInput();



} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e->getMessage();
}







