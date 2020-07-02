<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers;

use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\HoldTimeException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\RedisLogs\JoinToChatLogger;
use App\Entity\ModeratorSetting;
use Formapro\TelegramBot\Update;

class HoldTimeChecker
{
    /**
     * @var JoinToChatLogger
     */
    private $joinToChatLogger;

    public function __construct(JoinToChatLogger $joinToChatLogger)
    {
        $this->joinToChatLogger = $joinToChatLogger;
    }

    public function check(Update $update, ModeratorSetting $setting): void
    {
        $joinTime = $this->joinToChatLogger->get($update);

        if (0 === $joinTime) return;

        $now = time();
        $expire = $joinTime + $setting->getHoldtime();

        if ($now < $expire) {
            throw new HoldTimeException();
        }
    }
}