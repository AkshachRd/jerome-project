<?php
const TOKEN = '861121918:AAE1caaPhjPytqAhgEWdXaG9azEQIyVmcJs';
$url = 'https://api.telegram.org/bot' . TOKEN . '/getUpdates';
$response = file_get_contents($url);
echo $response;
var_dump($response);