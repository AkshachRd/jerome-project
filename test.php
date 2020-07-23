<?php
require_once 'MysqliDb.php';
require_once 'config.php';

$link = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$chatId = 401763451;
$wordNum = 3;
$wordInfo = json_decode('{"pronunciations":{"transcriptionUK":"\/\u02c8f\u0251\u02d0\u00f0\u0259(\u0279)\/","transcriptionUS":"\/\u02c8f\u0251\u00f0\u025a\/","audioUK":"http:\/\/audio.linguarobot.io\/en\/father-uk.mp3","audioUS":"http:\/\/audio.linguarobot.io\/en\/father-us.mp3"},"definitionsByPartOfSpeech":{"noun":[{"definition":"A (generally human) male who begets a child.","usageExample":"My father was a strong influence on me."},{"definition":"A male ancestor more remote than a parent; a progenitor; especially, a first ancestor."},{"definition":"A term of respectful address for an elderly man.","usageExample":"Come, father; you can sit here."}],"verb":[{"definition":"To be a father to; to sire."},{"definition":"To give rise to."},{"definition":"To act as a father; to support and nurture."}]},"translation":"\u043e\u0442\u0435\u0446","word":"Father"}', true);
$sql = 'INSERT word_list(chat_id, word_num, word, transcription_uk, transcription_us, audio_uk, audio_us, translation) VALUES (' . $chatId . ', ' . $wordNum . ', "' . $wordInfo["word"] . '", "' . $wordInfo["pronunciations"]["transcriptionUK"] . '", "' . $wordInfo["pronunciations"]["transcriptionUS"] . '", "' . $wordInfo["pronunciations"]["audioUK"] . '", "' . $wordInfo["pronunciations"]["audioUS"] . '", "' . $wordInfo["translation"] . '")';

mysqli_query($link, $sql);
var_dump(mysqli_error($link));