<?php declare(strict_types=1);

namespace App\Botonarioum\Bots;

use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;
use Symfony\Component\HttpFoundation\Request;

class AbstractBot implements BotInterface
{
    protected const
        BOTONARIOUM_KEY = '🤖 BOTONARIOUM',
        DONATE_KEY = '🍩 DONATE';

    public function handle(Update $update): bool
    {
        throw new \Exception('Method must be implemented');
    }

    public function isCurrentBot(Request $request): bool
    {
        return false !== strpos($request->getRequestUri(), $this->getToken());
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
            'Автор: @igor.kpl'
        );
    }

    public function getToken(): string
    {
        // TODO: Implement getToken() method.
    }
}
