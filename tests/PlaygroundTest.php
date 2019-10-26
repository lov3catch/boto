<?php declare(strict_types=1);

namespace App\Tests;

use App\Botonarioum\Bots\Handlers\MusicDealerHandler;
use Formapro\TelegramBot\Bot;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlaygroundTest extends KernelTestCase
{
    private const BOT_TOKEN = '412602481:AAGzneXUx8LpIdVrC_VPyXDkPx7l8SKWi84';

    private $bot;
    /**
     * @var MusicDealerHandler|object
     */
    private $handler;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->bot = new Bot(self::BOT_TOKEN);
    }

    protected function setUp()
    {
        self::bootKernel();
        $this->handler = self::$container->get('botonarioum.handler.music_dealer');
    }

//    public function testStart()
//    {
//        $update = FakeRequestsFactory::createStartRequest();
//        $this->handler->handle($this->bot, $update);
//
//        $this->assertTrue(true);
//    }

    public function testMessage()
    {
        try {
            $update = FakeRequestsFactory::creatMessageAsSongNameRequest();
            $this->handler->handle($this->bot, $update);
            $this->assertTrue(true);

        } catch (\Exception $exception) {
            $this->assertTrue(false);
        }

    }

//    public function testDownload()
//    {
//        $update = FakeRequestsFactory::creatDownloadRequest();
//
//        $handler = new MusicDealerHandler();
//        $handler->handle($this->bot, $update);
//
//        $this->assertTrue(true);
//    }

//    public function testNextPage()
//    {
//        $update = FakeRequestsFactory::createNextPageRequest();
//        $this->handler->handle($this->bot, $update);
//
//        $this->assertTrue(true);
//    }

//    public function testPrevPage()
//    {
//        $update = FakeRequestsFactory::createPrevPageRequest();
//
//        $handler = new MusicDealerHandler();
//        $handler->handle($this->bot, $update);
//
//        $this->assertTrue(true);
//    }

//    public function testDonate()
//    {
//        $update = FakeRequestsFactory::creatDonateRequest();
//
//        $handler = new MusicDealerHandler();
//        $handler->handle($this->bot, $update);
//
//        $this->assertTrue(true);
//    }

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