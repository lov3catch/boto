<?php declare(strict_types=1);

namespace App\Util;

use App\Botonarioum\Bots\Handlers\MusicDealerHandler;
use App\Tests\FakeRequestsFactory;
use Formapro\TelegramBot\Bot;
use PHPUnit\Framework\TestCase;

class PlaygroundTest extends TestCase
{
    private const BOT_TOKEN = '412602481:AAGzneXUx8LpIdVrC_VPyXDkPx7l8SKWi84';

    private $bot;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->bot = new Bot(self::BOT_TOKEN);
    }

//    public function testStart()
//    {
//        $update = FakeRequestsFactory::createStartRequest();
//
//        $handler = new MusicDealerHandler();
//        $handler->handle($this->bot, $update);
//
//        $this->assertTrue(true);
//    }

    public function testMessage()
    {
        $update = FakeRequestsFactory::creatMessageAsSongNameRequest();

        $handler = new MusicDealerHandler();
        $handler->handle($this->bot, $update);

        $this->assertTrue(true);
    }

//    public function testDonate()
//    {
//        $update = FakeRequestsFactory::creatDonateRequest();
//
//        $handler = new MusicDealerHandler();
//        $handler->handle($this->bot, $update);
//
//        $this->assertTrue(true);
//    }
//
//    public function testBotonarioum()
//    {
//        $update = FakeRequestsFactory::creatBotonarioumRequest();
//
//        $handler = new MusicDealerHandler();
//        $handler->handle($this->bot, $update);
//
//        $this->assertTrue(true);
//    }
}