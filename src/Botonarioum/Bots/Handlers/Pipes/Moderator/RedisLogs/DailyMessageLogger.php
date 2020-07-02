<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\RedisLogs;

use App\Botonarioum\Bots\Helpers\RedisKeys;
use App\Storages\RedisStorage;
use DateTime;
use Formapro\TelegramBot\Update;
use Predis\ClientInterface;

class DailyMessageLogger
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(RedisStorage $redisStorage)
    {
        $this->client = $redisStorage->client();
    }

    /**
     * Инкрементим счетчик с к-вом сообщений от пользователя в опредлененной группе
     * (если запись есть - инкрементим, если нет - устанавливаем стартовое значение)
     *
     * @param Update $update
     * @return int
     */
    public function set(Update $update): int
    {
        $chatId = $update->getMessage()->getChat()->getId();
        $memberId = $update->getMessage()->getFrom()->getId();;

        $key = RedisKeys::makeDailyMessageCountKey($chatId, $memberId);

        if ((int)$this->client->exists($key)) {
            $this->client->incr($key);

            return (int)$this->client->get($key);
        }

        $this->client->set($key, 0);
        $this->client->expire($key, 60 * 60 * 24);

        return $this->set($update);
    }

    public function get(Update $update): int
    {
        $chatId = $update->getMessage()->getChat()->getId();
        $memberId = $update->getMessage()->getFrom()->getId();

        return (int)($this->client->get(RedisKeys::makeDailyMessageCountKey($chatId, $memberId)) ?? 0);
//        return (int)($this->client->get(self::key($chatId, $memberId)) ?? 0);
    }

//    public static function key(int $chatId, int $memberId): string
//    {
//        $todayTimestamp = (DateTime::createFromFormat('Y-m-d', date('Y-m-d'))->setTime(0, 0))->getTimestamp();
//
//        return implode(':', ['moderator', 'message', $chatId, $memberId, $todayTimestamp]);
//    }
}