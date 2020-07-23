<?php
require_once 'MysqliDb.php';
require_once 'config.php';

$link = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$chatId = 401763451;

$sql = 'INSERT word_list(chat_id, word_num, word, transcription_uk, transcription_us, audio_uk, audio_us, translation) VALUES (' . $chatId . ', ' . $wordNum . ', "' . $wordInfo["word"] . '", "' . $wordInfo["pronunciations"]["transcriptionUK"] . '", "' . $wordInfo["pronunciations"]["transcriptionUS"] . '", "' . $wordInfo["pronunciations"]["audioUK"] . '", "' . $wordInfo["pronunciations"]["audioUS"] . '", "' . $wordInfo["translation"] . '")';

mysqli_query($link, $sql);
var_dump(mysqli_error($link));