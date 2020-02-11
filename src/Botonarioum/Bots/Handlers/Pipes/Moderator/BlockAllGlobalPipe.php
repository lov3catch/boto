<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\CommandPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO\MessageDTO;
use App\Entity\ModeratorBlocks;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class BlockAllGlobalPipe extends CommandPipe
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function isSupported(Update $update): bool
    {
        if (!parent::isSupported($update)) return false;

        if ('@omnamas' !== $update->getMessage()->getFrom()->getUsername()) return false;

        $command = explode(' ', $update->getMessage()->getText())[1];

        return '/block-all-global' === $command;
    }

    public function processing(Bot $bot, Update $update): bool
    {
        // todo: проверка админ ли это либо пригласивший бота

        $bot->sendMessage(new SendMessage($update->getMessage()->getChat()->getId(), 'Блокировка глобальная (супер-админ)'));

        $message = new MessageDTO($update->getMessage());
        $bot->sendMessage(new SendMessage(
            $update->getMessage()->getChat()->getId(),
            'Пользователь ' . $message->getReplyToMessage()->getFrom()->getUsername() . 'получает бан.'
        ));

        $this->doBlock($update, '/block-all-global');

        return true;
    }

    private function doBlock(Update $update, string $strategy): void
    {
        $message = new MessageDTO($update->getMessage());

        $groupId = $update->getMessage()->getChat()->getId();
        $userId = $message->getReplyToMessage()->getFrom()->getId();
        $adminId = $update->getMessage()->getFrom()->getId();

        $ban = new ModeratorBlocks();
        $ban->setUserId((string)$userId);
        $ban->setGroupId((string)$groupId);
        $ban->setAdminId($adminId);
        $ban->setCreatedAt(new \DateTime());
        $ban->setStrategy($strategy);

        $this->em->persist($ban);
        $this->em->flush();
    }
}