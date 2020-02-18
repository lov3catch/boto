<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers;

use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\RepostException;
use App\Entity\ModeratorSetting;
use Formapro\TelegramBot\Message;
use Formapro\TelegramBot\Update;
use function Formapro\Values\get_values;

class RepostChecker
{
    public function check(Update $update, ModeratorSetting $setting): void
    {
        if (!$update->getMessage() instanceof Message) return;
        $messageData = get_values($update->getMessage());

//        var_dump($messageData['forward_from_chat']);die;

        if (isset($messageData['forward_from_chat'])) {
            throw new RepostException();
        }
//        var_dump(get_values($update->getMessage()));die;
//
//
//        $message = $update->getMessage()->getText() ?? '';
//
//        if (count(explode(' ', $message)) > $setting->getMaxMessageWordsCount()) {
//            throw new WordsCountException();
//        }
    }
}