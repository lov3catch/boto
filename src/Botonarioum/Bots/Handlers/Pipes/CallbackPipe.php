<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes;

use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class CallbackPipe extends AbstractPipe
{
    public function isSupported(Update $update): bool
    {
        return (bool)$update->getCallbackQuery();
    }

    public function processing(Bot $bot, Update $update): bool
    {
        $message = new SendMessage(
            $update->getCallbackQuery()->getMessage()->getChat()->getId(),
            $update->getCallbackQuery()->getData()
        );

        $bot->sendMessage($message);

        return true;
    }
}