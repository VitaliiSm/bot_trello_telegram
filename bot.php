<?php
require_once 'database.php';
header('Content-Type: text/html; charset=utf-8');

$bot_token = '';
$data = file_get_contents('php://input');
$data = json_decode($data, true);
//file_put_contents(__DIR__ . '/message.txt', print_r($data, true));
if (!empty($data['message']['text'])) {
    $chat_id = $data['message']['chat']['id'];
    $first_name = $data['message']['from']['first_name'];
    $last_name = $data['message']['from']['last_name'];
    $text = trim($data['message']['text']);
    $user_name = $data['message']['from']['username'];
    if ($text == '/start') {
        $checkUsers = "SELECT * from users WHERE first_name='$first_name' and user_name='$user_name'";
        $checkQuery = mysqli_query(Database::getConnection(), $checkUsers);
        if (mysqli_num_rows($checkQuery) === 0) {
        $sql = "INSERT INTO users (first_name, last_name, user_name)
                    VALUES ('$first_name', '$last_name', '$user_name')";
        $query = mysqli_query(Database::getConnection(), $sql);
        $text_return = "Привет, $first_name $last_name";
        message_to_telegram($bot_token, $chat_id, $text_return);}
    
    }
}
//    elseif {};

     if (!empty($data['action']['data']['listAfter']['name'])) {
    $chat_id = '';
    $card_name = $data['action']['data']['card']['name'];
    $list_name = $data['action']['data']['listAfter']['name'];
        $text_return = "task: $card_name, status:  $list_name";
        message_to_telegram($bot_token, $chat_id, $text_return);
}

        
function message_to_telegram($bot_token, $chat_id, $text, $reply_markup = '')
{
    $ch = curl_init();
    $ch_post = [
        CURLOPT_URL => 'https://api.telegram.org/bot' . $bot_token . '/sendMessage',
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_POSTFIELDS => [
            'chat_id' => $chat_id,
            'parse_mode' => 'HTML',
            'text' => $text,
            'reply_markup' => $reply_markup,
        ]
    ];

    curl_setopt_array($ch, $ch_post);
    curl_exec($ch);
}
