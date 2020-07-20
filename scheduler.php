require_once 'MysqliDb.php';
require_once 'config.php';
require_once 'vendor/autoload.php';
use Telegram\Bot\Api;

$db = new MysqliDb (DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

$telegram = new Api(TG_BOT_TOKEN);

$chatId = $result["message"]["chat"]["id"];

$inlineKeyboard = [[[ 'text' => "Learn words", 'callback_data' => "learn" ]]];
$keyboard = [ 'inline_keyboard' => $inlineKeyboard ];
$reply_markup = json_encode($keyboard);

$telegram->sendMessage([ 'chat_id' => $chatId, 'text' => $reply, 'reply_markup' => $reply_markup ]);
