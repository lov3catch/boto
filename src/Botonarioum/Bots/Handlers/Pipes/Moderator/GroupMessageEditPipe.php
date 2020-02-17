<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO\MessageDTO;
//use App\Entity\ModeratorBanList;
//use App\Entity\ModeratorGroupOwners;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\Message;
use Formapro\TelegramBot\Update;

class GroupMessageEditPipe extends MessagePipe
{
    private const BLOCK_STRATEGIES_FOR_USERS = [
        '/block',
        '/block-all',
    ];

    private const BLOCK_STRATEGIES_FOR_ADMIN = [
        '/block-all-global',
    ];

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function processing(Bot $bot, Update $update): bool
    {
        return true;
    }

    public function isSupported(Update $update): bool
    {
        return $update->getEditedMessage() instanceof Message;
//        return false;
        // todo: проверка группа ли это
        if (!parent::isSupported($update)) return false;

//        if ($update->getMessage()->getChat()->getId() > 0) return false;


        $message = new MessageDTO($update->getMessage());

        if (!(bool)$message->getReplyToMessage()) return false;

        if (!in_array($update->getMessage()->getText(), array_merge(self::BLOCK_STRATEGIES_FOR_USERS, self::BLOCK_STRATEGIES_FOR_ADMIN))) {
            return false;
        }

    }

}