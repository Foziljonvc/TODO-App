<?php

declare(strict_types=1);

use GuzzleHttp\Client;

require 'vendor/autoload.php';
require 'DB.php';

$db = new DB();

$token = '7448038287:AAE95bOvBJbgulctsyL-WXKoJiRiv3Ej0Ao';
$tgApi = "https://api.telegram.org/bot$token/";

$client = new Client(['base_uri' => $tgApi]);

$update = json_decode(file_get_contents('php://input'));

$keyboard = [
    'inline_keyboard' => [
        [
            ['text' => '🔵 Add task', 'callback_data' => 'add'],
            ['text' => '🔵 Get task', 'callback_data' => 'get'],
        ],
        [
            ['text' => '🟢 Check', 'callback_data' => 'check'],
            ['text' => '🔴 Uncheck', 'callback_data' => 'uncheck'],
        ],
        [
            ['text' => '🗑️ Truncate', 'callback_data' => 'truncate'],
            ['text' => '❌ Delete', 'callback_data' => 'delete'],
        ]
    ]
];

if (isset($update->message)) {
    $message = $update->message;
    $chat_id = $message->chat->id;
    $text = $message->text;

    if ($text === '/start') {
        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'Select one of the inline buttons',
                'reply_markup' => json_encode($keyboard)
            ]
        ]);
        return;
    }

    if ($text === '/truncate') {
        $db->TruncateTodo();
        return;
    }

    if ($text === '/add') {
        $db->sendText('add');
        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'Please, Enter your task'
            ]
        ]);
        return;
    }

    if ($text === '/get') {
        $tasks = $db->SendAllUsers();
        $text = '';
        $count = 1;

        foreach ($tasks as $task) {
            if ($task['status'] == 1) {
                $text .= $count . ': <del>' . $task['todos'] . '</del>' . "\n";
            } else {
                $text .= $count . ': ' . $task['todos'] . "\n";
            }
            $count += 1;
        }

        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => $text,
                'parse_mode' => 'HTML'
            ]
        ]);
        return;
    }
}

if (isset($update->callback_query)) {
    $callback_query = $update->callback_query;
    $callback_data = $callback_query->data;
    $chat_id = $callback_query->message->chat->id;
    
    switch ($callback_data) {
        case 'add':
            $db->sendText('add');
            $client->post('sendMessage', [
                'form_params' => [
                    'chat_id' => $chat_id,
                    'text' => 'Please, Enter your task'
                ]
            ]);
            break;
        case 'get':
            $tasks = $db->SendAllUsers();
            $text = '';
            $count = 1;
            
            foreach ($tasks as $task) {
                if ($task['status'] == 1) {
                    $text .= $count . ': <del>' . $task['todos'] . '</del>' . "\n";
                } else {
                    $text .= $count . ': ' . $task['todos'] . "\n";
                }
                $count += 1;
            }

            $client->post('sendMessage', [
                'form_params' => [
                    'chat_id' => $chat_id,
                    'text' => $text,
                    'parse_mode' => 'HTML'
                ]
            ]);
            break;
        case 'check':
            $db->saveCheck('check');
            $client->post('sendMessage', [
                'form_params' => [
                    'chat_id' => $chat_id,
                    'text' => 'Enter the id number of your choice '
                ]
            ]);
            break;
        case 'uncheck':
            $db->saveUncheck('uncheck');
            $client->post('sendMessage', [
                'form_params' => [
                    'chat_id' => $chat_id,
                    'text' => 'Enter the id number of your choice '
                ]
            ]);
            break;
        case 'delete':
            $db->saveDelete('delete');
            $client->post('sendMessage', [
                'form_params' => [
                    'chat_id' => $chat_id,
                    'text' => 'Enter the id number of your choice'
                ]
            ]);
            break;
        case 'truncate':
            $db->TruncateTodo();
            $client->post('sendMessage', [
                'form_params' => [
                    'chat_id' => $chat_id,
                    'text' => 'All tasks have been truncated.'
                ]
            ]);
            break;
    }
    return;
}

if ($text) {
    $add = $db->getText();
    if ($add[0]['add'] === 'add') {
        $db->saveTeleText($text);
        $db->deleteAddText();
    }

    $check = $db->getCheck();
    if ($check[0]['check'] == 'check') {
        $checkNumber = (int)$text - 1;
        if ($checkNumber >= 0) {
            $db->checkTask($checkNumber);
            $db->deleteCheck();
        }
    }

    $uncheck = $db->getUncheck();
    if ($uncheck[0]['uncheck'] == 'uncheck') {
        $checkNumber = (int)$text - 1;
        if ($checkNumber >= 0) {
            $db->uncheckTask($checkNumber);
            $db->deleteUncheck();
        }
    }

    $delete = $db->getDelete();
    if ($delete[0]['delete'] == 'delete') {
        $db->deleteTaskUser((int)$text - 1);
        $db->deleteTask();
    }
}
