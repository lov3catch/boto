<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\AbstractPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\BotChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\RedisLogs\JoinToChatLogger;
use App\Botonarioum\Bots\Helpers\BuildKeyboard;
use App\Botonarioum\Bots\Helpers\RedisKeys;
use App\Entity\ModeratorSetting;
use App\Events\AddedUserInGroupEvent;
use App\Storages\RedisStorage;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\ChatMember;
use Formapro\TelegramBot\DeleteMessage;
use Formapro\TelegramBot\Message;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;
use Predis\Client;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class NewChatMemberPipe extends AbstractPipe
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var JoinToChatLogger
     */
    private $joinChannelLogger;
    /**
     * @var BotChecker
     */
    private $botChecker;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var Client
     */
    private $client;

    public function __construct(EventDispatcherInterface $dispatcher, EntityManagerInterface $entityManager, BotChecker $botChecker, JoinToChatLogger $joinToChatLogger, RedisStorage $redisStorage)
    {
        $this->dispatcher = $dispatcher;
        $this->joinChannelLogger = $joinToChatLogger;
        $this->botChecker = $botChecker;
        $this->em = $entityManager;
        $this->client = $redisStorage->client();
    }

    public function processing(Bot $bot, Update $update): bool
    {
        $this->dispatcher->dispatch(AddedUserInGroupEvent::EVENT_NAME, new AddedUserInGroupEvent($update, $bot));

        if ($update->getMessage()->getNewChatMember()->isBot()) return true;

        $groupId = $update->getMessage()->getChat()->getId();

        $setting = $this->em->getRepository(ModeratorSetting::class)->getForSelectedGroup($groupId);


        $this->removeStandardGreeting($bot, $update);

//        $this->removeLastGreeting($bot, $update);

        $this->addNewGreeting($bot, $update, $setting);

        // todo: логировать JoinToChatLogger
        // todo: тут баг в том, что мы трекаем подключение пользователя в группу, но только одного, а их может быть несколько
        $this->joinChannelLogger->set($update);

        return true;
    }

    public function isSupported(Update $update): bool
    {
        if ($update->getCallbackQuery()) return false;
        if (!$update->getMessage() instanceof Message) return false;

        return ($update->getMessage()->getNewChatMember() instanceof ChatMember);
    }


//    private function removeLastGreeting(Bot $bot, Update $update): void
//    {
//        $elements = array_unique($this->client->lrange(RedisKeys::makeLastGreetingsMessageQueueIdKey($update->getMessage()->getChat()->getId()), 0, -1));
//
//        $this->client->del([RedisKeys::makeLastGreetingsMessageQueueIdKey($update->getMessage()->getChat()->getId())]);
//
//        foreach ($elements as $element) {
//            try {
//                echo 'MODERATOR GREETING TO REMOVE: ' . $element . PHP_EOL;
//
//                $bot->deleteMessage(new DeleteMessage($update->getMessage()->getChat()->getId(), (int)$element));
//            } catch (\Exception $exception) {
//                $this->client->lpush(RedisKeys::makeLastGreetingsMessageQueueIdKey($update->getMessage()->getChat()->getId()), $element);
//            }
//        }
//
//
////        try {
////            $lastGreetingIdKey = RedisKeys::makeLastGreetingsMessageQueueIdKey();
////            var_dump('GREETING ID TO DELETE: ' . $lastGreetingIdKey);
////
//////            $lastGreetingIdKey = implode(':', ['moderator', 'last_greeting', $update->getMessage()->getChat()->getId()]);
////            if ($this->client->exists($lastGreetingIdKey)) {
////                $bot->deleteMessage(new DeleteMessage($update->getMessage()->getChat()->getId(), (int)$this->client->get($lastGreetingIdKey)));
////            } else {
////                var_dump('GREETING ID TO DELETE: ' . $lastGreetingIdKey . ' NOT FOUND!');
////            }
////        } catch (\Exception $exception) {
////            var_dump('GREETING EXCEPTION: ' . $exception->getMessage());
////        }
//    }

    private function addNewGreeting(Bot $bot, Update $update, ModeratorSetting $setting): void
    {
        $greeting = $setting->getGreetingMessage();
        $greeting = str_replace('{username}', $update->getMessage()->getNewChatMember()->getFirstName(), $greeting);
        $greeting = str_replace('{chat_title}', $update->getMessage()->getChat()->getTitle(), $greeting);

        $msg = new SendMessage(
            $update->getMessage()->getChat()->getId(),
            $greeting
        );

        if ($setting->getGreetingButtons()) {
            $msg->setReplyMarkup((new BuildKeyboard())->build($setting->getGreetingButtons()));
        }

        $newGreetingMessage = $bot->sendMessage($msg);

        $lastGreetingIdKey = RedisKeys::makeLastGreetingsMessageQueueIdKey();
        $data = json_encode([
            'chat_id'    => $update->getMessage()->getChat()->getId(),
            'message_id' => $newGreetingMessage->getMessageId(),
            'token'      => $bot->getToken(),
        ]);

//        var_dump($data);
        $this->client->lpush($lastGreetingIdKey, [$data]);
    }

    private function removeStandardGreeting(Bot $bot, Update $update): void
    {
        try {
            $deleteMessage = new DeleteMessage(
                $update->getMessage()->getChat()->getId(),
                $update->getMessage()->getMessageId());

            $bot->deleteMessage($deleteMessage);
        } catch (\Exception $exception) {
            //
        }
    }
}