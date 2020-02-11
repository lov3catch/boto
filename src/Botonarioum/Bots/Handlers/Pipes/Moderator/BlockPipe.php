<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\CommandPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO\MessageDTO;
use App\Entity\ModeratorBlocks;
use App\Entity\ModeratorGroupOwners;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class BlockPipe extends CommandPipe
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

        // check is from admin
        $groupId = $update->getMessage()->getChat()->getId();
        $adminId = ($this->em->getRepository(ModeratorGroupOwners::class)->findOneBy(['group_id' => $groupId]))->getPartnerId();

        if ((int)$adminId !== (int)$update->getMessage()->getFrom()->getId()) return false;

        $command = explode(' ', $update->getMessage()->getText())[1];

        return '/block' === $command;
    }

    public function processing(Bot $bot, Update $update): bool
    {
        // todo: проверка админ ли это либо пригласивший бота

        $bot->sendMessage(new SendMessage($update->getMessage()->getChat()->getId(), 'Блокировка в группе'));

        $message = new MessageDTO($update->getMessage());
        $bot->sendMessage(new SendMessage(
            $update->getMessage()->getChat()->getId(),
            'Пользователь ' . $message->getReplyToMessage()->getFrom()->getUsername() . 'получает бан.'
        ));

        $this->doBlock($update, '/block');

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