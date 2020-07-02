<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\EditCheckers;

use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\BanException;
use App\Entity\ModeratorBlock;
use App\Entity\ModeratorOwner;
use App\Entity\ModeratorSetting;
use App\Repository\ModeratorBlockRepository;
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
        $userId = $update->getEditedMessage()->getFrom()->getId();
        $groupId = $update->getEditedMessage()->getChat()->getId();

        // ищем хозяина группы
        $groupOwner = ($this->em->getRepository(ModeratorOwner::class))->findOneBy(['group_id' => $groupId]);

        if (!$groupOwner instanceof ModeratorOwner) return;

        // ищем забанен ли пользователь для всех групп текущего админа
        $ban = $this->em->getRepository(ModeratorBlock::class)->findOneBy(['admin_id' => $groupOwner->getUserId(), 'user_id' => $userId, 'strategy' => ModeratorBlockRepository::BAN_STRATEGY_GLOBAL]);

        if ($ban) {
            throw new BanException();
        }
    }
}