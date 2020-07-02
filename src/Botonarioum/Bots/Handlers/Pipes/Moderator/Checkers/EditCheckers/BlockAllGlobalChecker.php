<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\EditCheckers;

use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\BanException;
use App\Entity\ModeratorBlock;
use App\Entity\ModeratorSetting;
use App\Repository\ModeratorBlockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Update;

class BlockAllGlobalChecker
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

        $ban = $this->em->getRepository(ModeratorBlock::class)->findOneBy(['user_id' => $userId, 'strategy' => ModeratorBlockRepository::BAN_STRATEGY_TOTAL]);

        if ($ban) {
            throw new BanException();
        }
    }
}