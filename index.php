<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use GuzzleHttp\Client;

$token = "6038247949:AAEAkQNMwsFUynBu-wxOhPEaGgSrbDELp0w";

$TgApi = "https://api.telegram.org/bot$token/";

$client = new Client(['base_uri' => $TgApi]);

// for($i =0; $i < 10; $i++){
// $response = $client->post('sendMessage', [
// 'form_params' =>[
//     'chat_id'=>"2015894982",
//     'text' => 'Hello',
//     ]
// ]);
// }


    $response = $client->post('getUpdates');
    

$json = $response->getBody()->getContents();

print_r(json_decode($json));

?>