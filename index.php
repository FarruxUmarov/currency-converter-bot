<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;

$token = "6038247949:AAEAkQNMwsFUynBu-wxOhPEaGgSrbDELp0w";
$tgApi = "https://api.telegram.org/bot$token/";

$client = new Client(['base_uri' => $tgApi]);

$update = json_decode(file_get_contents('php://input'));

if (isset($update)) {
    if (isset($update->message)) {
        $message = $update->message;
        $chat_id = $message->chat->id ?? null;
        $type = $message->chat->type ?? null;
        $miid = $message->message_id ?? null;
        $name = $message->from->first_name ?? null;
        $user = $message->from->username ?? '';
        $fromid = $message->from->id ?? null;
        $text = $message->text ?? null;
        $title = $message->chat->title ?? null;
        $chatuser = $message->chat->username ?? null;
        $chatuser = $chatuser ? $chatuser : "Shaxsiy Guruh!";
        $caption = $message->caption ?? null;
        $entities = $message->entities[0] ?? null;
        $left_chat_member = $message->left_chat_member ?? null;
        $new_chat_member = $message->new_chat_member ?? null;
        $photo = $message->photo ?? null;
        $video = $message->video ?? null;
        $audio = $message->audio ?? null;
        $voice = $message->voice ?? null;
        $reply = $message->reply_markup ?? null;
        $fchat_id = $message->forward_from_chat->id ?? null;
        $fid = $message->forward_from_message_id ?? null;
    }
}

function convertCurrency($amount, $from_currency, $to_currency) {
    $apiKey = 'YOUR_API_KEY_HERE'; // Bu yerga exchangerate-api.com API kalitini kiriting
    $client = new Client();
    $response = $client->get("https://v6.exchangerate-api.com/v6/$apiKey/latest/$from_currency");
    $data = json_decode($response->getBody(), true);
    $rate = $data['conversion_rates'][$to_currency];
    return $amount * $rate;
}

if (isset($chat_id)) {
    if (preg_match('/(\d+)\s*(uzs|usd)\s*->\s*(uzs|usd)/i', $text, $matches)) {
        $amount = $matches[1];
        $from_currency = strtoupper($matches[2]);
        $to_currency = strtoupper($matches[3]);
        $converted_amount = convertCurrency($amount, $from_currency, $to_currency);
        $response_text = "$amount $from_currency = $converted_amount $to_currency";
    } else {
        $response_text = 'Iltimos, miqdorni va valyutalarni to\'g\'ri formatda kiriting, masalan: 1000 UZS -> USD yoki 10 USD -> UZS';
    }

    $client->post('sendMessage', [
        'form_params' => [
            'chat_id' => $chat_id,
            'text' => $response_text
        ]
    ]);
} else {
    error_log('chat_id is not set');
}
