<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\MusicDealer;

use App\Botonarioum\Bots\Handlers\Pipes\MessagePipe;
use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\Keyboards\TrackFinderSearchResponseKeyboard;
use App\Botonarioum\TrackFinder\Page;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\EditMessageText;
use Formapro\TelegramBot\Update;

class NextCallbackPipe extends MessagePipe
{
    public function isSupported(Update $update): bool
    {
        if ($update->getCallbackQuery()) {
            $direction = explode('.', $update->getCallbackQuery()->getData())[Page::DIRECTION_CALLBACK_POSITION];
            return 'next' === $direction;
        }

        return false;
    }

    public function processing(Bot $bot, Update $update): bool
    {
        $message = EditMessageText::withChatId(
            $update->getCallbackQuery()->getMessage()->getText(),
            $update->getCallbackQuery()->getMessage()->getChat()->getId(),
            $update->getCallbackQuery()->getMessage()->getMessageId()
        );

        $offset = (int)(explode('.', $update->getCallbackQuery()->getData())[Page::OFFSET_CALLBACK_POSITION] + Page::PAGE_SIZE);
        $limit = (int)(explode('.', $update->getCallbackQuery()->getData())[Page::LIMIT_CALLBACK_POSITION]);

        $searchThis = array_reverse(explode('.', $update->getCallbackQuery()->getData()));
        $searchThis = reset($searchThis);

        $searchResponse = $this->trackFinderService->search($searchThis, $limit, $offset);
        $markup = (new TrackFinderSearchResponseKeyboard)->build($searchResponse, $update);

        $message->setReplyMarkup($markup);

        $bot->editMessageText($message);

        return true;
    }
}
