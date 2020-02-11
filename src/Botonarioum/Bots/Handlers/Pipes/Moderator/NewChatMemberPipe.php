<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\AbstractPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\BotChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\RedisLogs\JoinToChatLogger;
use App\Botonarioum\Bots\Helpers\BuildKeyboard;
use App\Entity\ModeratorSetting;
use App\Events\AddedUserInGroupEvent;
use App\Storages\RedisStorage;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Parameter;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\ChatMember;
use Formapro\TelegramBot\DeleteMessage;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;
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
     * @var \Predis\Client
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
        $newMemberName = $update->getMessage()->getNewChatMember();
//        $newMemberName = (new MessageDTO($update->getMessage()))->getNewChatMember();

        if ($newMemberName->isBot()) return true;


        $this->dispatcher->dispatch(AddedUserInGroupEvent::EVENT_NAME, new AddedUserInGroupEvent($update));

        $groupId = $update->getMessage()->getChat()->getId();

        /** @var ModeratorSetting $setting */
        $setting = ($this->em->getRepository(ModeratorSetting::class)->createQueryBuilder('setting'))
                       ->where('setting.is_default = :isd')
                       ->orWhere('setting.group_id = :grid')
                       ->orderBy('setting.is_default', 'ASC')
                       ->setParameters(new ArrayCollection([new Parameter('isd', true), new Parameter('grid', (int)$groupId)]))
                       ->getQuery()
                       ->getResult()[0];

        // удаляем старое приветствие
        try {

            $lastGreetingIdKey = implode(':', ['moderator', 'last_greeting', $update->getMessage()->getChat()->getId()]);
            if ($this->client->exists($lastGreetingIdKey)) {
                $bot->deleteMessage(new DeleteMessage($update->getMessage()->getChat()->getId(), (int)$this->client->get($lastGreetingIdKey)));
            }
        } catch (\Exception $exception) {
            //
        }


        // формируем новое приветствие
        $greeting = $setting->getGreeting();
        $greeting = str_replace('{username}', $newMemberName->getUsername(), $greeting);
        $greeting = str_replace('{chat_title}', $update->getMessage()->getChat()->getTitle(), $greeting);

        // удаляем стандартное сообщение
        $deleteMessage = new DeleteMessage($update->getMessage()->getChat()->getId(), $update->getMessage()->getMessageId());
        $bot->deleteMessage($deleteMessage);


        $msg = new SendMessage(
            $update->getMessage()->getChat()->getId(),
            $greeting
        );

        if ($setting->getGreetingButtons()) {
            $msg->setReplyMarkup((new BuildKeyboard())->build($setting->getGreetingButtons()));
        }

        $newGreetingMessage = $bot->sendMessage($msg);

        // todo: необходимо либо новая таблица с приветствиями, либо создавать настройки всегда для каждой группы
        // сохраняем lastGreetingId, что бы в следующий раз удалить
        $this->client->set($lastGreetingIdKey, $newGreetingMessage->getMessageId());


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
}