<?php

declare(strict_types=1);

$update = json_decode(file_get_contents('php://input'));

require_once "src/Task.php";

$task = new User();

$path = parse_url($_SERVER['REQUEST_URI'])['path'];

if (isset($update->update_id)) {
    require 'bot.php';
} else {
    switch($path) {
        case '/add':
            $task->SaveUserTodo($update->text);
            break;
        case '/delete':
            $task->deleteTaskUser(($update->text) - 1);
            break;
        case '/check':
            $task->checkTask(($update->text) - 1);
            break;
        case '/uncheck':
            $task->uncheckTask(($update->text) - 1);
            break;
        default:
            echo "Not found";
            break;
    }
}

// require_once "View.php";
