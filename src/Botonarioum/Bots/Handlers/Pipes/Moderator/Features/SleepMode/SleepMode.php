<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\Features\SleepMode;

use Carbon\Carbon;
use Carbon\CarbonInterface;

class SleepMode
{
    public const FROM_TO_REGEXP = "/^\s*(2[0-3]|[01]?[0-9]):([0-5]?[0-9])\s*-\s*(2[0-3]|[01]?[0-9]):([0-5]?[0-9])\s*$/";

    /**
     * @var CarbonInterface
     */
    private $from;
    /**
     * @var CarbonInterface
     */
    private $to;

    /**
     * @var array
     */
    private $periods = [];

    public function __construct(CarbonInterface $from, CarbonInterface $to)
    {
        $cloneFrom = clone $from;
        $cloneTo = clone $to;

        if ($from->greaterThanOrEqualTo($to)) {
            $this->periods[] = [$from, $cloneFrom->endOfDay()];
            $this->periods[] = [$cloneTo->startOfDay(), $to];
        } else {
            $this->periods[] = [$from, $to];
        }
    }

    public function between(CarbonInterface $dateTime): bool
    {
        foreach ($this->periods as $period) {
            [$from, $to] = $period;

            if (false === $dateTime->between($from, $to)) continue;

            return true;
        }

        return false;
    }

    public static function createFromString(string $fromTo): self
    {
        [$from, $to] = $input = array_map(function (string $inp) {

            return trim($inp);
        }, explode('-', trim($fromTo)));

        return new static(Carbon::createFromFormat('H:i', $from), Carbon::createFromFormat('H:i', $to));
    }
}