<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\AbstractPipe;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class MessagePipe extends AbstractPipe
{
    public function processing(Bot $bot, Update $update): bool
    {
        $bot->sendMessage(new SendMessage(
            $update->getMessage()->getChat()->getId(),
            'Hello'
        ));

        return true;
    }

    public function isSupported(Update $update): bool
    {
        if ($update->getCallbackQuery()) return false;

        return ($update->getMessage()) ? true : false;
    }
}