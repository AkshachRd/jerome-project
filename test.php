<?php
require_once 'MysqliDb.php';
require_once 'config.php';

$link = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

$tempWordInfo = '{"pronunciations":{"transcriptionUK":"\/m\u00e6n\/","transcriptionUS":"\/m\u00e6n\/","audioUK":"http:\/\/audio.linguarobot.io\/en\/man-uk.mp3","audioUS":"http:\/\/audio.linguarobot.io\/en\/man-us.mp3"},"definitionsByPartOfSpeech":{"noun":[{"definition":"An adult male human.","usageExample":"The show is especially popular with middle-aged men."},{"definition":"(collective) All human males collectively: mankind."},{"definition":"A human, a person of either gender, usually an adult. (See usage notes.)","usageExample":"every man for himself"}],"interjection":[{"definition":"Used to place emphasis upon something or someone; sometimes, but not always, when actually addressing a man.","usageExample":"Man, that was a great catch!"}]},"translation":"\u0447\u0435\u043b\u043e\u0432\u0435\u043a","word":"Man"}';
$wordInfo = json_decode($tempWordInfo, true);
$chatId = 401763451;
$word = 'Father';

$sql = 'SELECT word_num FROM word_list WHERE chat_id = ' . $chatId . ' AND word = "' . $word . '"';
$sqlResult = mysqli_query($link, $sql);

echo $wordNum = (int)mysqli_fetch_array($sqlResult)["word_num"];