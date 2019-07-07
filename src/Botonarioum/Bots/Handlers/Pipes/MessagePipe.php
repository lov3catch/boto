<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes;

use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class MessagePipe extends AbstractPipe
{
    public function processing(Bot $bot, Update $update): bool
    {
        $message = new SendMessage(
            $update->getMessage()->getChat()->getId(),
            $update->getMessage()->getText()
        );

        $bot->sendMessage($message);

        return true;
    }

    public function isSupported(Update $update): bool
    {
        if ($update->getCallbackQuery()) return false;

        return true;
    }
}