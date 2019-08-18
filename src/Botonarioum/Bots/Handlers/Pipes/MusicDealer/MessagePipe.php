<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\MusicDealer;

use App\Botonarioum\Bots\Handlers\Pipes\MessagePipe as BaseMessagePipe;
use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\Keyboards\TrackFinderSearchResponseKeyboard;
use App\Botonarioum\TrackFinder\Page;
use App\Botonarioum\TrackFinder\TrackFinderService;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\EditMessageText;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;
use Psr\Log\LoggerInterface;

class MessagePipe extends BaseMessagePipe
{
    /**
     * @var TrackFinderService
     */
    protected $trackFinderService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->trackFinderService = new TrackFinderService();
    }

    public function processing(Bot $bot, Update $update): bool
    {
        try {
            $this->logger->error('-----1-----');
            $message = new SendMessage(
                $update->getMessage()->getChat()->getId(),
                '🔎 Ищу...'
            );

            $sendSearchMessage = $bot->sendMessage($message);
            $this->logger->error('-----2-----');

            if (empty($update->getMessage()->getText())) {
                $message = new SendMessage(
                    $update->getMessage()->getChat()->getId(),
                    'Не найдено :('
                );

                $bot->sendMessage($message);

                // todo: обновляем клавиатуру

                return true;
            }
            $this->logger->error('-----3-----');

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
            $this->logger->error('-----4-----');

            $markup = (new TrackFinderSearchResponseKeyboard)->build($searchResponse, $update);

            $newMessage = EditMessageText::withChatId(
                '🎶 Результат поиска: ' . substr($update->getMessage()->getText(), 0, 20),
                $update->getMessage()->getChat()->getId(),
                $sendSearchMessage->getMessageId()

            );

            $this->logger->error('-----5-----');

            $newMessage->setReplyMarkup($markup);

            $this->logger->error('-----6-----');

            $bot->editMessageText($newMessage);

            $this->logger->error('-----7-----');
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage());
        }


        return true;
    }
}