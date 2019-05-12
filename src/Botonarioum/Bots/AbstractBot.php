<?php declare(strict_types=1);

namespace App\Botonarioum\Bots;
//include_once "BotInterface.php";

use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class AbstractBot implements BotInterface
{
//    protected const ENV_TOKEN_KEY = null;

    protected const
        BOTONARIOUM_KEY = '🤖 BOTONARIOUM',
        DONATE_KEY = '🍩 DONATE';

    public function handle(Update $update): bool
    {
        throw new \Exception('Method must be implemented');
    }

    public function isCurrentBot(): bool
    {
        return false !== strpos($_SERVER[REQUEST_URI], $this->getToken());
    }

//    protected function getToken(): string
//    {
//        return $_ENV[self::ENV_TOKEN_KEY];
//    }

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
