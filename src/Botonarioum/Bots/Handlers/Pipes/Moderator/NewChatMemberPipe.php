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

//        // todo: rewrite this!
//        /** @var ModeratorSetting $setting */
//        $setting = ($this->em->getRepository(ModeratorSetting::class)->createQueryBuilder('setting'))
//                       ->where('setting.is_default = :isd')
//                       ->orWhere('setting.group_id = :grid')
//                       ->orderBy('setting.is_default', 'ASC')
//                       ->setParameters(new ArrayCollection([new Parameter('isd', true), new Parameter('grid', (int)$groupId)]))
//                       ->getQuery()
//                       ->getResult()[0];

//        var_dump($setting);die;

        // удаляем старое приветствие
        $this->removeLastGreeting($bot, $update);
//        try {
//            $lastGreetingIdKey = implode(':', ['moderator', 'last_greeting', $update->getMessage()->getChat()->getId()]);
//            if ($this->client->exists($lastGreetingIdKey)) {
//                $bot->deleteMessage(new DeleteMessage($update->getMessage()->getChat()->getId(), (int)$this->client->get($lastGreetingIdKey)));
//            }
//        } catch (\Exception $exception) {
//            //
//        }


        // формируем новое приветствие
//        $greeting = $setting->getGreeting();
//        $greeting = str_replace('{username}', $newChatMember->getUsername(), $greeting);
//        $greeting = str_replace('{chat_title}', $update->getMessage()->getChat()->getTitle(), $greeting);

        // удаляем стандартное приветствие
        $this->removeStandardGreeting($bot, $update);
//        $deleteMessage = new DeleteMessage($update->getMessage()->getChat()->getId(), $update->getMessage()->getMessageId());
//        $bot->deleteMessage($deleteMessage);


//        $msg = new SendMessage(
//            $update->getMessage()->getChat()->getId(),
//            $greeting
//        );

//        if ($setting->getGreetingButtons()) {
//            $msg->setReplyMarkup((new BuildKeyboard())->build($setting->getGreetingButtons()));
//        }

//        $newGreetingMessage = $bot->sendMessage($msg);

        // создаем новое приветствие
        $this->addNewGreeting($bot, $update, $setting);

        // todo: необходимо либо новая таблица с приветствиями, либо создавать настройки всегда для каждой группы
        // сохраняем lastGreetingId, что бы в следующий раз удалить
//        $this->client->set($lastGreetingIdKey, $newGreetingMessage->getMessageId());


        // todo: логировать JoinToChatLogger
        $this->joinChannelLogger->set($update);

        return true;
    }

    public function isSupported(Update $update): bool
    {
        if ($update->getCallbackQuery()) return false;

//        if (null === $update->getMessage()) return false;

        return ($update->getMessage()->getNewChatMember() instanceof ChatMember);
    }


    private function removeLastGreeting(Bot $bot, Update $update): void
    {
        try {
            $lastGreetingIdKey = RedisKeys::makeLastGreetingMessageIdKey($update->getMessage()->getChat()->getId());

//            $lastGreetingIdKey = implode(':', ['moderator', 'last_greeting', $update->getMessage()->getChat()->getId()]);
            if ($this->client->exists($lastGreetingIdKey)) {
                $bot->deleteMessage(new DeleteMessage($update->getMessage()->getChat()->getId(), (int)$this->client->get($lastGreetingIdKey)));
            }
        } catch (\Exception $exception) {
            //
        }
    }

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

        $lastGreetingIdKey = RedisKeys::makeLastGreetingMessageIdKey($update->getMessage()->getChat()->getId());
        $this->client->set($lastGreetingIdKey, $newGreetingMessage->getMessageId());
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