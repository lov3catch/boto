<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\MusicDealer;

use App\Botonarioum\Bots\Handlers\Pipes\MessagePipe;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class BotonarioumPipe extends MessagePipe
{
    public function processing(Bot $bot, Update $update): bool
    {
        $message = new SendMessage(
            $update->getMessage()->getChat()->getId(),
            '@botonarioum_bot'
        );

        $bot->sendMessage($message);

        return true;
    }


    public function isSupported(Update $update): bool
    {
        return parent::isSupported($update) && $update->getMessage()->getText() === '🤖 BOTONARIOUM';
    }

}