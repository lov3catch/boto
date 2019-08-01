<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes;

use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\KeyboardButton;
use Formapro\TelegramBot\ReplyKeyboardMarkup;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class StartPipe extends AbstractPipe
{
    protected const
        BOTONARIOUM_KEY = '🤖 BOTONARIOUM',
        DONATE_KEY = '🍩 DONATE',
        MESSAGE = 'I`m started';

    public function processing(Bot $bot, Update $update): bool
    {
        $message = new SendMessage(
            $update->getMessage()->getChat()->getId(),
            $this::MESSAGE
        );

        $keyboard = new ReplyKeyboardMarkup(
            [
                [new KeyboardButton(self::DONATE_KEY), new KeyboardButton(self::BOTONARIOUM_KEY)]
            ]
        );

        $message->setReplyMarkup($keyboard);
        $bot->sendMessage($message);

        return true;
    }

    public function isSupported(Update $update): bool
    {
        if ($update->getCallbackQuery()) return false;

        if ($update->getMessage()) {
            return '/start' === $update->getMessage()->getText() ? true : false;
        }

        return false;
    }
}