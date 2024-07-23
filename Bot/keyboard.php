<?php

declare(strict_types=1);

class keyboard {

    public function keyboard ()
    {
        $keyboard = [
            'keyboard' => [
                [['text' => '➕ Add task'], ['text' => '➖ Delete']],
                [['text' => '✅ Check'], ['text' => '🟩 Uncheck']],
                [['text' => '🗑️ Truncate'], ['text' => '📋 Get task']],
                [['text' => '🔄 Change']]
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ];
    }

}