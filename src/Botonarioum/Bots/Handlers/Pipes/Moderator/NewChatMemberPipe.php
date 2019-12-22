<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\AbstractPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO\MessageDTO;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO\NewChatMemberDTO;
use App\Entity\ModeratorPartnersProgram;
use App\Events\ActivityEvent;
use App\Events\AddedUserInGroupEvent;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class NewChatMemberPipe extends AbstractPipe
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function processing(Bot $bot, Update $update): bool
    {
        $this->dispatcher->dispatch(AddedUserInGroupEvent::EVENT_NAME, new AddedUserInGroupEvent($update));

        $bot->sendMessage(new SendMessage(
            $update->getMessage()->getChat()->getId(),
            'New member!'
        ));

        return true;
    }

    public function isSupported(Update $update): bool
    {
        if ($update->getCallbackQuery()) return false;

        if (null === $update->getMessage()) return false;

        return ((new MessageDTO($update->getMessage()))->getNewChatMember() instanceof NewChatMemberDTO);

        if ($update->getMessage()->getChat()->getId() > 0) return false;

        $messageDto = new MessageDTO($update->getMessage());
        var_dump($messageDto->getNewChatMember());die;
        $messageDto->getNewChatMember();

        return ($update->getMessage()) ? true : false;
    }
}