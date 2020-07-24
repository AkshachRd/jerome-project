<?php
require_once 'config.php';

$link = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

//$chatId = 401763451;

$str = '{"word":"Father","pronunciation":{"transcriptionUK":"\/\u02c8f\u0251\u02d0\u00f0\u0259(\u0279)\/","transcriptionUS":"\/\u02c8f\u0251\u00f0\u025a\/"},"definition":null,"usageExample":null,"translation":"\u043e\u0442\u0435\u0446"}';
var_dump(json_decode($str, true));