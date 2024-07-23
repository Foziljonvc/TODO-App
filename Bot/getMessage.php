<?php

declare(strict_types=1);

require 'vendor/autoload.php';
require_once 'keyboard.php';

use GuzzleHttp\Client as GuzzleClient;

class Acquire {

    private $db;
    private $keyboard;
    private $client;
    private $token;
    private $tgApi;

    private function __construct()
    {
        $this->db = new User();
        $this->keyboard = new keyboard();
        $this->token = '7448038287:AAE95bOvBJbgulctsyL-WXKoJiRiv3Ej0Ao';
        $this->tgApi = "https://api.telegram.org/bot{$this->token}/";
        $this->client = new GuzzleClient(['base_uri' => $this->tgApi]);
    }

    public function saveAdd (string $text, int $chat_id) {
        $$this->db->saveTeleText($text);
        $this->db->deleteAddText();
        $this->client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'Task added successfully!',
                'reply_markup' => json_encode($this->keyboard)
            ]
        ]);
    }

    public function saveCheck (int $text, int $chat_id) {
        $checkNumber = (int)$text - 1;
        if ($checkNumber >= 0) {
            $this->db->checkTask($checkNumber);
            $this->db->deleteCheck();
            $this->client->post('sendMessage', [
                'form_params' => [
                    'chat_id' => $chat_id,
                    'text' => 'Task checked successfully!',
                    'reply_markup' => json_encode($this->keyboard)
                ]
            ]);
        }
    }

    public function saveUncheck (int $text, int $chat_id){
        $checkNumber = (int)$text - 1;
        if ($checkNumber >= 0) {
            $this->db->uncheckTask($checkNumber);
            $this->db->deleteUncheck();
            $this->client->post('sendMessage', [
                'form_params' => [
                    'chat_id' => $chat_id,
                    'text' => 'Task unchecked successfully!',
                    'reply_markup' => json_encode($this->keyboard)
                ]
            ]);
        }
    }

    public function deletePlanUser(int $text, $chat_id) {
        $this->db->deleteTaskUser((int)$text - 1);
        $this->db->deleteTask();
        $this->client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'Task deleted successfully!',
                'reply_markup' => json_encode($this->keyboard)
            ]
        ]);
    }
}