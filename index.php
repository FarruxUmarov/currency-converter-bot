<?php

require 'vendor/autoload.php';

require 'Currency.php';

require 'DB.php';

use GuzzleHttp\Client;

class TelegramBot
{
    private $token;
    private $tgApi;
    private $client;
    private $update;

    public function __construct($token)
    {
        $this->token = $token;
        $this->tgApi = "https://api.telegram.org/bot{$token}/";
        $this->client = new Client(['base_uri' => $this->tgApi]);
        $this->update = json_decode(file_get_contents('php://input'));
    }

    public function handleRequest()
    {
        if (isset($this->update) && isset($this->update->message)) {
            $message = $this->update->message;
            $chat_id = $message->chat->id;
            $text = $message->text;

            if (!empty($text)) {
                $responseText = $this->processMessage($text);
                $this->sendMessage($chat_id, $responseText);
            } else {
                error_log("Xabar matni bo'sh.");
            }
        } else {
            error_log("Update yoki xabar mavjud emas.");
        }
    }

    private function processMessage($text)
    {
        if (strpos($text, "/convert") === 0) {
            $params = explode(" ", $text);
            if (count($params) == 4) {
                $amount = $params[1];
                $from_currency = strtoupper($params[2]);
                $to_currency = strtoupper($params[3]);

                require_once "Currency.php";

                $currencyConverter = new Currency();
                $converted = $currencyConverter->exchange((float)$amount, $from_currency, $to_currency);

                if ($converted !== null) {
                    return "Konvertatsiya natijasi: $amount $from_currency = $converted $to_currency";
                } else {
                    return "Valyuta kursini olishda xatolik yuz berdi.";
                }
            } else {
                return "Noto'g'ri format. To'g'ri format: /convert <miqdor> <from_valyuta> <to_valyuta>";
            }
        } else {
            return "Salom! Men valyuta konvertatsiyasi qilish uchun mo'ljallanganman. /convert buyrug'ini ishlatib valyutalarni konvertatsiya qiling.";
        }
    }

    private function sendMessage($chat_id, $text)
    {
        $this->client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => $text
            ]
        ]);
    }
}

$token = "6038247949:AAEAkQNMwsFUynBu-wxOhPEaGgSrbDELp0w";
$bot = new TelegramBot($token);
$bot->handleRequest();

