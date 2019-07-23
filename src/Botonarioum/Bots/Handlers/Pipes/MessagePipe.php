<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes;

use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\Keyboards\TrackFinderSearchResponseKeyboard;
use App\Botonarioum\TrackFinder\Page;
use App\Botonarioum\TrackFinder\TrackFinderService;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\DeleteMessage;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class MessagePipe extends AbstractPipe
{
    /**
     * @var TrackFinderService
     */
    protected $trackFinderService;

    // todo: Перенести в MD
    public function __construct()
    {
        $this->trackFinderService = new TrackFinderService();
    }

    public function processing(Bot $bot, Update $update): bool
    {
        $message = new SendMessage(
            $update->getMessage()->getChat()->getId(),
            '🔎 Ищу...'
        );

        $sendSearchMessage = $bot->sendMessage($message);

        $searchResponse = $this->trackFinderService->search($update->getMessage()->getText(), Page::DEFAULT_LIMIT, Page::DEFAULT_OFFSET);

        if ($searchResponse->isEmpty()) {
            $message = new SendMessage(
                $update->getMessage()->getChat()->getId(),
                'Not found :('
            );

            $bot->sendMessage($message);

            return true;
        }

        $bot->deleteMessage(new DeleteMessage($update->getMessage()->getChat()->getId(), $sendSearchMessage->getMessageId()));

        $markup = (new TrackFinderSearchResponseKeyboard)->build($searchResponse, $update);

        $message = new SendMessage(
            $update->getMessage()->getChat()->getId(),
            $update->getMessage()->getText()
        );

        $message->setReplyMarkup($markup);

        $bot->sendMessage($message);

        return true;
    }

    public function isSupported(Update $update): bool
    {
        if ($update->getCallbackQuery()) return false;

        return true;
    }
}