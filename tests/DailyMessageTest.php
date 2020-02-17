<?php

declare(strict_types=1);

namespace App\Tests;

use App\Botonarioum\Bots\Handlers\Pipes\Moderator\RedisLogs\DailyMessageLogger;
use App\Botonarioum\Bots\Helpers\RedisKeys;
use App\Storages\RedisStorage;
use Formapro\TelegramBot\Chat;
use Formapro\TelegramBot\Message;
use Formapro\TelegramBot\Update;
use Formapro\TelegramBot\User;
use PHPUnit\Framework\MockObject\MockObject;
use Predis\Client;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DailyMessageTest extends KernelTestCase
{
    private const
        USER_ID = 125,
        CHAT_ID = 51734;
    /**
     * @var Client
     */
    private $client;
    /**
     * @var Update|MockObject
     */
    private $update;
    /**
     * @var Chat|MockObject
     */
    private $chat;
    /**
     * @var Message|MockObject
     */
    private $message;
    /**
     * @var User|MockObject
     */
    private $user;

    public function setUp()
    {
        self::bootKernel();

        $this->client = self::$container->get(RedisStorage::class)->client();

        $this->chat = $this->getMockBuilder(Chat::class)->getMock();
        $this->chat->method('getId')->willReturn(self::CHAT_ID);

        $this->user = $this->getMockBuilder(User::class)->getMock();
        $this->user->method('getId')->willReturn(self::USER_ID);

        $this->message = $this->getMockBuilder(Message::class)->getMock();
        $this->message->method('getChat')->willReturn($this->chat);
        $this->message->method('getFrom')->willReturn($this->user);

        $this->update = $this->getMockBuilder(Update::class)->getMock();
        $this->update->method('getMessage')->willReturn($this->message);
    }

    protected function tearDown()
    {
        $this->client->del([RedisKeys::makeDailyMessageCountKey(self::CHAT_ID, self::USER_ID)]);
    }

    public function testShouldBeZeroIfEmpty()
    {
        $dailyMessageLogger = new DailyMessageLogger($this->client);

        $result = $dailyMessageLogger->get($this->update);

        $this->assertIsInt($result);
        $this->assertEquals(0, $result);
    }

    public function testSet()
    {
        $dailyMessageLogger = new DailyMessageLogger($this->client);

        $result = $dailyMessageLogger->set($this->update);

        $this->assertIsInt($result);
        $this->assertEquals(1, $result);
    }
}