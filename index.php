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
    'keyboard' => [
        [['text' => '🔵 Add task'], ['text' => '🔵 Get task']],
        [['text' => '🟢 Check'], ['text' => '🔴 Uncheck']],
        [['text' => '🗑️ Truncate'], ['text' => '❌ Delete']]
    ],
    'resize_keyboard' => true,
    'one_time_keyboard' => true
];

if (isset($update->message)) {
    $message = $update->message;
    $chat_id = $message->chat->id;
    $text = $message->text;

    if ($text === '/start') {
        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'Select one of the buttons below',
                'reply_markup' => json_encode($keyboard)
            ]
        ]);
        return;
    }

    if ($text === '🔵 Add task') {
        $db->sendText('add');
        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'Please, Enter your task'
            ]
        ]);
        return;
    }

    if ($text === '🔵 Get task') {
        $tasks = $db->SendAllUsers();
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

        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => $responseText,
                'parse_mode' => 'HTML'
            ]
        ]);
        return;
    }

    if ($text === '🟢 Check') {
        $db->saveCheck('check');
        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'Enter the ID number of the task you want to check'
            ]
        ]);
        return;
    }

    if ($text === '🔴 Uncheck') {
        $db->saveUncheck('uncheck');
        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'Enter the ID number of the task you want to uncheck'
            ]
        ]);
        return;
    }

    if ($text === '🗑️ Truncate') {
        $db->TruncateTodo();
        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'All tasks have been truncated.'
            ]
        ]);
        return;
    }

    if ($text === '❌ Delete') {
        $db->saveDelete('delete');
        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'Enter the ID number of the task you want to delete'
            ]
        ]);
        return;
    }
}

if ($text) {
    $add = $db->getText();
    if ($add[0]['add'] === 'add') {
        $db->saveTeleText($text);
        $db->deleteAddText();
        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'Task added successfully!',
                'reply_markup' => json_encode($keyboard)
            ]
        ]);
    }

    $check = $db->getCheck();
    if ($check[0]['check'] == 'check') {
        $checkNumber = (int)$text - 1;
        if ($checkNumber >= 0) {
            $db->checkTask($checkNumber);
            $db->deleteCheck();
            $client->post('sendMessage', [
                'form_params' => [
                    'chat_id' => $chat_id,
                    'text' => 'Task checked successfully!',
                    'reply_markup' => json_encode($keyboard)
                ]
            ]);
        }
    }

    $uncheck = $db->getUncheck();
    if ($uncheck[0]['uncheck'] == 'uncheck') {
        $checkNumber = (int)$text - 1;
        if ($checkNumber >= 0) {
            $db->uncheckTask($checkNumber);
            $db->deleteUncheck();
            $client->post('sendMessage', [
                'form_params' => [
                    'chat_id' => $chat_id,
                    'text' => 'Task unchecked successfully!',
                    'reply_markup' => json_encode($keyboard)
                ]
            ]);
        }
    }

    $delete = $db->getDelete();
    if ($delete[0]['delete'] == 'delete') {
        $db->deleteTaskUser((int)$text - 1);
        $db->deleteTask();
        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'Task deleted successfully!',
                'reply_markup' => json_encode($keyboard)
            ]
        ]);
    }
}