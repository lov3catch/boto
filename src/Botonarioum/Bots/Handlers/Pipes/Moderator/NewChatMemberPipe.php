<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\AbstractPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\BotChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO\MessageDTO;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO\NewChatMemberDTO;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\BotException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\RedisLogs\JoinToChatLogger;
use App\Entity\ModeratorSetting;
use App\Events\AddedUserInGroupEvent;
use App\Storages\RedisStorage;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;
use Predis\ClientInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class NewChatMemberPipe extends AbstractPipe
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var ClientInterface
     */
    private $client;
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

    public function __construct(EventDispatcherInterface $dispatcher, EntityManagerInterface $entityManager, BotChecker $botChecker, JoinToChatLogger $joinToChatLogger)
    {
        $this->dispatcher = $dispatcher;
        $this->joinChannelLogger = $joinToChatLogger;
        $this->botChecker = $botChecker;
        $this->em = $entityManager;
    }

    public function processing(Bot $bot, Update $update): bool
    {
        $this->dispatcher->dispatch(AddedUserInGroupEvent::EVENT_NAME, new AddedUserInGroupEvent($update));

        $bot->sendMessage(new SendMessage(
            $update->getMessage()->getChat()->getId(),
            'New member!'
        ));

        /** @var ModeratorSetting $setting */
        $setting = $this->em->getRepository(ModeratorSetting::class)->findOneBy([]);

        try {
            $this->botChecker->check($update, $setting);
        } catch (BotException $exception) {
            $bot->sendMessage(new SendMessage(
                $update->getMessage()->getChat()->getId(),
                'Нельзя приглашать ботов'
            ));
        }

        // todo: логировать JoinToChatLogger
        $this->joinChannelLogger->set($update);

        return true;
    }

    public function isSupported(Update $update): bool
    {
        if ($update->getCallbackQuery()) return false;

        if (null === $update->getMessage()) return false;

        return ((new MessageDTO($update->getMessage()))->getNewChatMember() instanceof NewChatMemberDTO);

        if ($update->getMessage()->getChat()->getId() > 0) return false;

        $messageDto = new MessageDTO($update->getMessage());
        var_dump($messageDto->getNewChatMember());
        die;
        $messageDto->getNewChatMember();

        return ($update->getMessage()) ? true : false;
    }
}