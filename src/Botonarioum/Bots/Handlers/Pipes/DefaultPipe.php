<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes;

use Exception;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class DefaultPipe extends AbstractPipe
{
    protected const MESSAGE = 'Что-то пошло не так :(';

    public function processing(Bot $bot, Update $update): bool
    {
        // todo: fix thix
        return true;
        try {
            $chatId = ($update->getCallbackQuery())
                ? $update->getCallbackQuery()->getMessage()->getChat()->getId()
                : $update->getMessage()->getChat()->getId();

            $message = new SendMessage(
                $chatId,
                $this::MESSAGE
            );

            $bot->sendMessage($message);
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }


        return true;
    }

    public function isSupported(Update $update): bool
    {
        return true;
    }
}