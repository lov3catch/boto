<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\Helpers;

use App\Botonarioum\TrackFinder\Page;
use App\Storages\RedisStorage;
use Formapro\TelegramBot\Update;
use Rhumsaa\Uuid\Uuid;

class CallbackQueryHelper
{
    private const REDIS_TTL = 60 * 60 * 24 * 30 * 3;        // 3 month
    /**
     * @var RedisStorage
     */
    private $storage;

    public function __construct(RedisStorage $storage)
    {
        $this->storage = $storage;
    }

    public function getText(Update $update): string
    {
        return $update->getMessage()->getText();
    }

    public function getTextFromCallback(Update $update): string
    {
        $searchThis = array_reverse(explode('.', $update->getCallbackQuery()->getData()));
        return reset($searchThis);
    }

    public function getOffset(Update $update): int
    {
        return (int)(explode('.', $update->getCallbackQuery()->getData())[Page::OFFSET_CALLBACK_POSITION]);
    }

    public function getLimit(Update $update): int
    {
        return (int)(explode('.', $update->getCallbackQuery()->getData())[Page::LIMIT_CALLBACK_POSITION]);
    }

    public function getDirection(Update $update): string
    {
        return explode('.', $update->getCallbackQuery()->getData())[Page::DIRECTION_CALLBACK_POSITION];
    }

    public function buildPrevCallbackData(int $limit, int $offset, string $text): string
    {
        $key = (Uuid::uuid1())->toString();
        $value = implode('.', ['pager', 'prev', 'limit', $limit, 'offset', $offset, 'track_name', $text]);

        $this->storage->client()->set($key, $value, null, self::REDIS_TTL);

        return $value;
    }

    public function buildNextCallbackData(int $limit, int $offset, string $text): string
    {
        $key = (Uuid::uuid1())->toString();
        $value = implode('.', ['pager', 'next', 'limit', $limit, 'offset', $offset, 'track_name', $text]);

        $this->storage->client()->set($key, $value, null, self::REDIS_TTL);

        return $value;
    }
}