<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\AbstractPipe;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\KeyboardButton;
use Formapro\TelegramBot\ReplyKeyboardMarkup;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class StartPipe extends AbstractPipe
{
    public const
        GROUPS_KEY = '👥 Мои группы';

    public function processing(Bot $bot, Update $update): bool
    {
        $message = new SendMessage(
            $update->getMessage()->getChat()->getId(),
            'It`s working :)'
        );

        $keyboard = new ReplyKeyboardMarkup(
            [
                [new KeyboardButton(self::GROUPS_KEY)]
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