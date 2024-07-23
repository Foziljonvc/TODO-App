<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';
require_once 'keyboard.php';

use GuzzleHttp\Client as GuzzleClient;

class Client 
{
    private $keyboard;
    private $token;
    private $tgApi;
    private $client;
    private $db;

    public function __construct()
    {
        $this->keyboard = new keyboard();
        $this->token = '7448038287:AAE95bOvBJbgulctsyL-WXKoJiRiv3Ej0Ao';
        $this->tgApi = "https://api.telegram.org/bot{$this->token}/";
        $this->client = new GuzzleClient(['base_uri' => $this->tgApi]);
        $this->db = new User();
    }

    public function startHandler(int $chat_id)
    {
        $this->client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'Select one of the buttons below',
                'reply_markup' => json_encode($this->keyboard)
            ]
        ]);
    }

    public function addHandler(int $chat_id)
    {
        $this->client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'Please, Enter your task'
            ]
        ]);
    }

    public function getHandler (int $chat_id)
    {
        $tasks = $this->db->SendAllUsers();
        $responseText = '';
        $count = 1;

        foreach ($tasks as $task) {
            if ($task['status'] == 1) {
                $responseText .= $count . ': <del>' . $task['todos'] . '</del>' . "\n";
            } else {
                $responseText .= $count . ': ' . $task['todos'] . "\n";
            }
            $count++;
        }

        $this->client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => $responseText,
                'parse_mode' => 'HTML'
            ]
        ]);
    }

    public function checkHandler (int $chat_id)
    {
        $this->client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'Enter the ID number of the task you want to check'
            ]
        ]);
    }

    public function uncheckHandler (int $chat_id)
    {
        $this->client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'Enter the ID number of the task you want to uncheck'
            ]
        ]);
    }

    public function truncateHandler (int $chat_id)
    {
        $this->client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'All tasks have been truncated.'
            ]
        ]);
    }

    public function deleteHandler (int $chat_id)
    {
        $this->client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'Enter the ID number of the task you want to delete'
            ]
        ]);
    }
}