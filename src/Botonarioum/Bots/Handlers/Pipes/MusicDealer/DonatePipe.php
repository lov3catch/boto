<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\MusicDealer;

use App\Botonarioum\Bots\Handlers\Pipes\MessagePipe;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class DonatePipe extends MessagePipe
{
    public function processing(Bot $bot, Update $update): bool
    {

        $bot->sendMessage(new SendMessage(
            $update->getMessage()->getChat()->getId(),
            '🇷🇺 Нравится бот? Поддержи его!
VISA/Mastercard: 5169-3600-0134-9707  

🇪🇺 Like this? Donate!
VISA/Mastercard: 5169-3600-0134-9707'
        ));

        return true;
    }


    public function isSupported(Update $update): bool
    {
        return parent::isSupported($update) && $update->getMessage()->getText() === '🍩 DONATE';
    }

}