<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\Helpers;

use App\Botonarioum\TrackFinder\Page;
use App\Storages\RedisStorage;
use Formapro\TelegramBot\Update;

class CallbackQueryHelper
{
    private const REDIS_TTL = 60 * 60 * 24 * 3;        // 3 week
    private const OLD_SCHEMA_PARTS_COUNT = 7;

    /**
     * @var RedisStorage
     */
    private $storage;

    public function __construct(RedisStorage $storage)
    {
        $this->storage = $storage;
    }

//    private function isOldSchema(Update $update)
//    {
//        var_dump($update->getCallbackQuery()->getData());
//        die;
//
//        var_dump(explode('.', $update->getCallbackQuery()->getData())[7] ?? null);
//        die;
//        return (bool)(explode('.', $update->getCallbackQuery()->getData())[7] ?? null);
//    }

    public function getTextFromCallback(Update $update): string
    {
        if ($callbackData = $update->getCallbackQuery()) {
            $result = (count(explode('.', $callbackData)) === self::OLD_SCHEMA_PARTS_COUNT)
                ? explode('.', $callbackData)[Page::TEXT_CALLBACK_POSITION]
                : explode('.', $this->storage->client()->get($callbackData))[Page::TEXT_CALLBACK_POSITION];
        }

        return mb_convert_encoding($result ?? $update->getMessage()->getText(), 'UTF-8', 'UTF-8');
    }

    public function getOffset(Update $update): int
    {
        // todo: ключа может уже не быть -> реализовать соответственное сообщенте пользователю
        $callbackData = $update->getCallbackQuery();
        $offset = (count(explode('.', $callbackData)) === self::OLD_SCHEMA_PARTS_COUNT)
            ? explode('.', $callbackData)[Page::OFFSET_CALLBACK_POSITION]
            : explode('.', $this->storage->client()->get($callbackData))[Page::OFFSET_CALLBACK_POSITION];

        return (int)$offset;
    }

    public function getLimit(Update $update): int
    {
        // todo: ключа может уже не быть -> реализовать соответственное сообщенте пользователю
        $callbackData = $update->getCallbackQuery();
        $limit = (count(explode('.', $callbackData)) === self::OLD_SCHEMA_PARTS_COUNT)
            ? explode('.', $callbackData)[Page::LIMIT_CALLBACK_POSITION]
            : explode('.', $this->storage->client()->get($callbackData))[Page::LIMIT_CALLBACK_POSITION];

        return (int)$limit;
    }

    public function getDirection(Update $update): string
    {
        // todo: ключа может уже не быть -> реализовать соответственное сообщенте пользователю
        $callbackData = $update->getCallbackQuery();
        $direction = (count(explode('.', $callbackData)) === self::OLD_SCHEMA_PARTS_COUNT)
            ? explode('.', $callbackData)[Page::DIRECTION_CALLBACK_POSITION]
            : explode('.', $this->storage->client()->get($callbackData))[Page::DIRECTION_CALLBACK_POSITION];

        return (string)$direction;
    }

    public function buildPrevCallbackData(int $limit, int $offset, string $text, string $uuid): string
    {
        $value = implode('.', ['pager', 'prev', 'limit', $limit, 'offset', $offset, 'track_name', $text]);

        $this->storage->client()->set($uuid, $value);
        $this->storage->client()->expire($uuid, self::REDIS_TTL);

        return $uuid;
        return $value;
    }

    public function buildNextCallbackData(int $limit, int $offset, string $text, string $uuid): string
    {
        $value = implode('.', ['pager', 'next', 'limit', $limit, 'offset', $offset, 'track_name', $text]);

        $this->storage->client()->set($uuid, $value);
        $this->storage->client()->expire($uuid, self::REDIS_TTL);

        return $uuid;
        return $value;
    }
}