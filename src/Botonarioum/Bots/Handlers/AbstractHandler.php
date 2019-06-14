<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers;

use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

abstract class AbstractHandler implements BotHandlerInterface
{
    protected const
        BOTONARIOUM_KEY = '🤖 BOTONARIOUM',
        DONATE_KEY = '🍩 DONATE';

    public function handle(Bot $bot, Update $update): bool
    {
        throw new \Exception('Method must be implemented');
    }

    protected function botonarioumAction(Update $update): SendMessage
    {
        return new SendMessage(
            $update->getMessage()->getChat()->getId(),
            'Другие боты и контакты разработчика ты можешь найти тут: @botonarioum_bot'
        );
    }

    protected function donateAction(Update $update): SendMessage
    {
        return new SendMessage(
            $update->getMessage()->getChat()->getId(),
            '🇷🇺 Нравится бот? Поддержи его!
VISA/Mastercard: 5169-3600-0134-9707  

🇪🇺 Like this? Donate!
VISA/Mastercard: 5169-3600-0134-9707'
        );
    }

    protected function contactAction(Update $update): SendMessage
    {
        return new SendMessage(
            $update->getMessage()->getChat()->getId(),
            'Автор: @igorkpl'
        );
    }
}
