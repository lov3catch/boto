<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class InfoPipe extends MessagePipe
{
    public function isSupported(Update $update): bool
    {
        if ($update->getMessage()) return true;

        return false;
    }

    public function processing(Bot $bot, Update $update): bool
    {
//        $bot->sendMessage(new SendMessage(
//            $update->getMessage()->getChat()->getId(),
//            'Общая информация о боте...'
//        ));

        return true;
    }
}