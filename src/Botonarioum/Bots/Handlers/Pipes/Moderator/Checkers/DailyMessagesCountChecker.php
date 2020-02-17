<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers;

use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\DailyMessageCountException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\RedisLogs\DailyMessageLogger;
use App\Entity\ModeratorSetting;
use Formapro\TelegramBot\Update;

class DailyMessagesCountChecker
{
    /**
     * @var DailyMessageLogger
     */
    private $dailyMessageLogger;

    public function __construct(DailyMessageLogger $dailyMessageLogger)
    {
        $this->dailyMessageLogger = $dailyMessageLogger;
    }

    public function check(Update $update, ModeratorSetting $setting): void
    {
        if ($this->dailyMessageLogger->get($update) > $setting->getMaxDailyMessageCount()) {
            throw new DailyMessageCountException();
        }
    }
}