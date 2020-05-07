<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO\BotDTO;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO\KickChatMemberDTO;
use App\Events\SpamDetectedEvent;
use App\Storages\RedisStorage;
use Formapro\TelegramBot\Message;
use Predis\Client;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class IncSpamCountSubscriber implements EventSubscriberInterface
{
    private const MAX_SPAM_TRIES = 7;

    /**
     * @var Client
     */
    private $client;

    public function __construct(RedisStorage $storage)
    {
        $this->client = $storage->client();
    }

    /**
     * @param $event SpamDetectedEvent
     */
    public function incSpamCount($event): void
    {
        // X попыток спама за Y минуту - бан на 12 часов

        $message = ($event->getUpdate()->getMessage() instanceof Message)
            ? $event->getUpdate()->getMessage()
            : $event->getUpdate()->getEditedMessage();

        $key = $this->buildKey($message);

        $spamCount = $this->client->get($key);

        if (is_null($spamCount)) {
            $this->client->set($key, 1);
            $this->client->expire($key, 3 * 60);
        } else {
            $this->client->incr($key);
        }
    }

    /**
     * @param $event SpamDetectedEvent
     */
    public function kickChatMember($event): void
    {
        $message = ($event->getUpdate()->getMessage() instanceof Message)
            ? $event->getUpdate()->getMessage()
            : $event->getUpdate()->getEditedMessage();

        $key = $this->buildKey($message);

        $count = $this->client->get($key) ?? 0;

        if ($count < self::MAX_SPAM_TRIES) return;

        $bot = $event->getBot();

        $botDTO = new BotDTO($bot->getToken());

        $botDTO->kickChatMember(new KickChatMemberDTO(
            '@' . $message->getChat()->getUsername(),
            $message->getFrom()->getId(),
            time() + (60 * 60 * 12)
        ));
    }

    private function buildKey(Message $message): string
    {
        return implode(':', [
            'spam_log',
            $message->getFrom()->getId(),
            $message->getChat()->getId()
        ]);
    }

    public static function getSubscribedEvents()
    {
        return [
            SpamDetectedEvent::EVENT_NAME => [
                ['incSpamCount', 10],
                ['kickChatMember', -10]
            ]
        ];
    }
}
