<?php

$client = new Client();
$acquire = new Acquire();
$db = new User();


if(isset($update->message)) {
    $message = $update->message;
    $chat_id = $message->chat->id;
    $text = $message->text;

    switch($text) {
        case '/start':
            $client->startHandler($chat_id);
            break;
        case '➕ Add task':
            $db->sendText('add');
            $client->addHandler($chat_id);
            break;
        case '📋 Get task':
            $client->getHandler($chat_id);
            break;
        case '✅ Check':
            $db->saveCheck('check');
            $client->checkHandler($chat_id);
            break;
        case '🟩 Uncheck':
            $db->saveUncheck('uncheck');
            $client->uncheckHandler($chat_id);
            break;
        case '🗑️ Truncate':
            $db->saveDelete('delete');
            $client->deleteHandler($chat_id);
            break;
        case '➖ Delete':
            $db->saveDelete('delete');
            $client->deleteHandler($chat_id);
            break;
        default:
            echo "Not found";
    }


}


if ($text) {
    
    $add = $$this->db->getText();
    if ($add[0]['add'] === 'add') {
        $acquire->saveAdd($text, $chat_id);
        return;
    }
    $check = $this->db->getCheck();
    if ($check[0]['check'] == 'check') {
        $acquire->saveCheck((int)$text - 1, $chat_id);
        return;
    }

    $uncheck = $this->db->getUncheck();
    if ($uncheck[0]['uncheck'] == 'uncheck') {
        $acquire->saveUncheck((int)$text - 1, $chat_id);
        return;
    }

    $delete = $this->db->getDelete();
    if ($delete[0]['delete'] == 'delete') {
        $acquire->deletePlanUser((int)$text - 1, $chat_id);
        return;
    }
    

    
}



// if ($text === '🔄 Change') {
//     $db->saveChenge('chenge');
//     $client->post('sendMessage', [
//         'form_params' => [
//             'chat_id' => $chat_id,
//             'text' => 'Enter the change text'
//         ]
//     ]);
//     return;
// }




    // if ($text === '🔄 Change') {
//     $db->saveChenge('chenge');
//     $client->post('sendMessage', [
//         'form_params' => [
//             'chat_id' => $chat_id,
//             'text' => 'Enter the change text'
//         ]
//     ]);
//     return;
// }



    // $chenge = $db->getChenge();
    // if ($chenge[0]['chenge'] == 'chenge') {
    //     $db->chengeTask();
    // }