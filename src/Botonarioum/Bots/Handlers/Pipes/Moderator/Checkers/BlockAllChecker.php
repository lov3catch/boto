<?php

declare(strict_types=1);


namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers;


use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\BanException;
use App\Entity\ModeratorBlocks;
use App\Entity\ModeratorGroupOwners;
use App\Entity\ModeratorSetting;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Update;

class BlockAllChecker
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function check(Update $update, ModeratorSetting $setting): void
    {
        $userId = $update->getMessage()->getFrom()->getId();
        $groupId = $update->getMessage()->getChat()->getId();

        // ищем хозяина группы
        $groupOwner = ($this->em->getRepository(ModeratorGroupOwners::class))->findOneBy(['group_id' => $groupId]);

        if (!$groupOwner instanceof ModeratorGroupOwners) return;

        // ищем забанен ли пользователь для всех групп текущего админа
        $ban = $this->em->getRepository(ModeratorBlocks::class)->findOneBy(['admin_id' => $groupOwner->getPartnerId(), 'user_id' => $userId, 'strategy' => '/block-all']);

        if ($ban) {
            throw new BanException();
        }
    }
}