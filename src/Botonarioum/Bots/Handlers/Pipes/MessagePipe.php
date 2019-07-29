<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes;

use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\Keyboards\TrackFinderSearchResponseKeyboard;
use App\Botonarioum\TrackFinder\Page;
use App\Botonarioum\TrackFinder\TrackFinderService;
use Exception;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\EditMessageText;
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
        try {
            $message = new SendMessage(
                $update->getMessage()->getChat()->getId(),
                '🔎 Ищу...'
            );

            $sendSearchMessage = $bot->sendMessage($message);

            if (empty($update->getMessage()->getText())) {
                $message = new SendMessage(
                    $update->getMessage()->getChat()->getId(),
                    'Не найдено :('
                );

                $bot->sendMessage($message);

                // todo: обновляем клавиатуру

                return true;
            }

            $searchResponse = $this->trackFinderService->search($update->getMessage()->getText(), Page::DEFAULT_LIMIT, Page::DEFAULT_OFFSET);

            if ($searchResponse->isEmpty()) {
                $message = new SendMessage(
                    $update->getMessage()->getChat()->getId(),
                    'Не найдено :('
                );

                $bot->sendMessage($message);

                // todo: обновляем клавиатуру

                return true;
            }

            $markup = (new TrackFinderSearchResponseKeyboard)->build($searchResponse, $update);

            $newMessage = EditMessageText::withChatId(
                '🎶 Результат поиска: ' . substr($update->getMessage()->getText(), 0, 20),
                $update->getMessage()->getChat()->getId(),
                $sendSearchMessage->getMessageId()

            );

            $newMessage->setReplyMarkup($markup);

            $bot->editMessageText($newMessage);
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }


        return true;
    }

    public function isSupported(Update $update): bool
    {
        if ($update->getCallbackQuery()) return false;

        return true;
    }
}