<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes;

use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class DefaultPipe extends AbstractPipe
{
    protected const MESSAGE = 'Что-то пошло не так :(';

    public function processing(Bot $bot, Update $update): bool
    {
        $message = new SendMessage(
            $update->getMessage()->getChat()->getId(),
            $this::MESSAGE
        );

        $bot->sendMessage($message);

        return true;
    }

    public function isSupported(Update $update): bool
    {
        return true;
    }
}