<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Helpers;

use DateTime;

class RedisKeys
{
    /**
     * @param int $chatId
     * @return string
     * @deprecated
     */
    public static function makeLastGreetingMessageIdKey(int $chatId): string
    {
        return implode(':', ['moderator', 'last_greeting', $chatId]);
    }

    /**
     * @param int $chatId
     * @return string
     * @deprecated
     */
    public static function makeLastGreetingsMessageIdKey(int $chatId): string
    {
        return implode(':', ['moderator', 'last_greeting', 'queue', $chatId]);
    }

    /**
     * @param int $chatId
     * @return string
     * @deprecated
     */
    public static function makeLastGreetingMediasMessageIdKey(int $chatId): string
    {
        return implode(':', ['moderator', 'last_greeting_media', 'queue', $chatId]);
    }

    public static function makeLastGreetingsMessageQueueIdKey(): string
    {
        return implode(':', ['moderator', 'last_greeting', 'queue']);
    }

    public static function makeAwaitSettingChangeKey(int $chatId): string
    {
        return implode(':', ['moderator', 'group', 'settings', 'await', $chatId]);
    }

    public static function makeTempMessageKey(): string
    {
        return 'moderator:temp:messages';
    }

    public static function makeDailyMessageCountKey(int $chatId, int $userId): string
    {
        $todayTimestamp = (DateTime::createFromFormat('Y-m-d', date('Y-m-d'))->setTime(0, 0))->getTimestamp();

        return implode(':', ['moderator', 'message', $chatId, $userId, $todayTimestamp]);
    }

    public static function makeJoinToChatDateTimeKey(int $chatId, int $userId): string
    {
        return implode(':', ['moderator', 'join', $chatId, $userId]);
    }
}