<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\MusicDealer;

use App\Botonarioum\Bots\Handlers\Pipes\MessagePipe;
use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\Keyboards\TrackFinderSearchResponseKeyboard;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class NextCallbackPipe extends MessagePipe
{
    public function isSupported(Update $update): bool
    {
        return (bool)$update->getCallbackQuery();
    }

    public function processing(Bot $bot, Update $update): bool
    {
        $message = new SendMessage(
            $update->getCallbackQuery()->getMessage()->getChat()->getId(),
            '🔎 Ищу...'
        );

        $bot->sendMessage($message);

        $searchResponse = $this->trackFinderService->search('Hardkiss');
        $markup = (new TrackFinderSearchResponseKeyboard)->build($searchResponse, $update);

        $message = new SendMessage(
            $update->getCallbackQuery()->getMessage()->getChat()->getId(),
            'next'
        );

        $message->setReplyMarkup($markup);

        $bot->sendMessage($message);

        return true;
    }

}
