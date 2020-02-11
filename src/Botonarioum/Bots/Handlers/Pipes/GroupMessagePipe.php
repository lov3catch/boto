<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes;

use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\Update;

class GroupMessagePipe extends AbstractPipe
{
    public function isSupported(Update $update): bool
    {
        if ($update->getCallbackQuery()) return false;

        return $update->getMessage()->getChat()->getId() < 0;
    }

    public function processing(Bot $bot, Update $update): bool
    {
        throw new \Exception('Implement me, bitch');
    }
}