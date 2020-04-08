<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\EditCheckers;

use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\SleepException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Features\SleepMode\SleepMode;
use App\Entity\ModeratorSetting;
use Carbon\Carbon;
use Formapro\TelegramBot\Update;

class SleepChecker
{
    private const DEFAULT_TIMEZONE = 'Europe/Moscow';

    public function check(Update $update, ModeratorSetting $setting): void
    {
        if ($setting->getSleepFrom() === null || $setting->getSleepUntil() === null) return;

        $timezone = new \DateTimeZone(self::DEFAULT_TIMEZONE);

        $sleepMode = new SleepMode(
            Carbon::createFromFormat('H:i', $setting->getSleepFrom(), $timezone),
            Carbon::createFromFormat('H:i', $setting->getSleepUntil(), $timezone));

        if ($sleepMode->between(Carbon::now($timezone))) throw new SleepException();
    }
}