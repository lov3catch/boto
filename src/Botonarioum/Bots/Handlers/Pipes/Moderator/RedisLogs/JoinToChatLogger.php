<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\RedisLogs;

use App\Botonarioum\Bots\Helpers\RedisKeys;
use App\Storages\RedisStorage;
use Formapro\TelegramBot\Message;
use Formapro\TelegramBot\Update;
use Predis\ClientInterface;

class JoinToChatLogger
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(RedisStorage $redisStorage)
    {
        $this->client = $redisStorage->client();
    }

    public function set(Update $update): int
    {
        $chatId = $update->getMessage()->getChat()->getId();
        $memberId = $update->getMessage()->getNewChatMember()->getId();

        $value = (new \DateTime())->getTimestamp();

        $this->client->set(RedisKeys::makeJoinToChatDateTimeKey($chatId, $memberId), $value);

        return $value;
    }

    public function get(Update $update): int
    {
        // todo: может тут не 0, а null? тк мы храним тут время вступления пользователя в чат
        if ($update->getMessage() instanceof Message) {
            $chatId = $update->getMessage()->getChat()->getId();
            $memberId = $update->getMessage()->getFrom()->getId();
        } elseif ($update->getEditedMessage() instanceof Message) {
            $chatId = $update->getEditedMessage()->getChat()->getId();
            $memberId = $update->getEditedMessage()->getFrom()->getId();
        }


        return (int)($this->client->get(RedisKeys::makeJoinToChatDateTimeKey($chatId, $memberId)) ?? 0);
//        return (int)($this->client->get(self::key($chatId, $memberId)) ?? 0);
    }

//    public static function key(int $chatId, int $memberId): string
//    {
//        return implode(':', ['moderator', 'join', $chatId, $memberId]);
//    }
}