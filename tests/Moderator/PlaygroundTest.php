<?php

declare(strict_types=1);

namespace App\Tests\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Features\SleepMode\SleepMode;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlaygroundTest extends KernelTestCase
{
    private const TIME_REGEX = "/^\s*(2[0-3]|[01]?[0-9]):([0-5]?[0-9])\s*-\s*(2[0-3]|[01]?[0-9]):([0-5]?[0-9])\s*$/";

    public function testFoo()
    {
        $now = Carbon::now();

        $first = Carbon::create(null, null, null, 6);
        $second = Carbon::create(null, null, null, 20);

        $this->assertTrue($now->between($first, $second));
    }

    public function testBar()
    {
        $now = Carbon::now();

        $first = Carbon::create(null, null, null, 16);
        $second = Carbon::create(null, null, null, 14);

        $sleepMode = new SleepMode($first, $second);

        $this->assertTrue($sleepMode->between($now));
    }

    public function invalidInputProvider()
    {
        return [
            ['33:00 - 14:00'],
            ['20:23 - 15:333'],
            ['invalid-value'],
            ['1'],
            [' '],
            [''],

        ];
    }

    public function validInputProvider()
    {
        return [
            ['13:00-14:00'],
            ['13:00 - 14:00'],
            ['20:23 - 15:33'],
            ['  23:50 -     12:33'],
            ['23:50 -     12:33  '],
            ['  23:50 -     12:33  '],
            ['23:00 - 12:22']
        ];
    }

    public function validBetweenInputProvider()
    {
        return [
            ['13:00', '14:00', '13:21'],
            ['20:23', '15:33', '14:00'],
            ['23:50', '13:33', '01:59'],
        ];
    }

    /**
     * @dataProvider validInputProvider
     * @param string $input
     */
    public function testValidInput(string $input)
    {
        $this->assertTrue((bool)preg_match(self::TIME_REGEX, $input));
    }

    /**
     * @dataProvider invalidInputProvider
     * @param string $input
     */
    public function testInvalidInput(string $input)
    {
        $this->assertFalse((bool)preg_match(self::TIME_REGEX, $input));
    }

    /**
     * @dataProvider validInputProvider
     * @param string $input
     */
    public function testCreateCarbonObject(string $input)
    {
        [$from, $to] = $input = array_map(function (string $inp) {

            return trim($inp);
        }, explode('-', trim($input)));

        $this->assertInstanceOf(Carbon::class, Carbon::createFromFormat('H:i', $from));
        $this->assertInstanceOf(Carbon::class, Carbon::createFromFormat('H:i', $to));
    }

    /**
     * @dataProvider validBetweenInputProvider
     * @param string $from
     * @param string $to
     * @param string $now
     */
    public function testBetween(string $from, string $to, string $now)
    {
        $timeZone =  new \DateTimeZone('Europe/Moscow');

        $sleepMode = new SleepMode(Carbon::createFromFormat('H:i', $from, $timeZone), Carbon::createFromFormat('H:i', $to, $timeZone));

        $this->assertTrue($sleepMode->between(Carbon::createFromFormat('H:i', $now, $timeZone)));

//
//        $timezone = new \DateTimeZone('Europe/Moscow');
//        var_dump(Carbon::now($timezone));die;

    }
}