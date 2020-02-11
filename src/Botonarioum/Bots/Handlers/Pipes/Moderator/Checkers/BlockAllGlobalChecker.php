<?php

declare(strict_types=1);


namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers;


use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\BanException;
use App\Entity\ModeratorBlocks;
use App\Entity\ModeratorSetting;
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
        $userId = $update->getMessage()->getFrom()->getId();

        $ban = $this->em->getRepository(ModeratorBlocks::class)->findOneBy(['user_id' => $userId, 'strategy' => '/block-all-global']);

        if ($ban) {
            throw new BanException();
        }
    }
}