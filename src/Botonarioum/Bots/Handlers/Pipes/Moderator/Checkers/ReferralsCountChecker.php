<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers;

use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\ReferralsCountException;
use App\Entity\ModeratorPartnersProgram;
use App\Entity\ModeratorSetting;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Update;

class ReferralsCountChecker
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function check(Update $update, ModeratorSetting $setting): void
    {
        $minReferralsCount = $setting->getMinReferralsCount();

        if (count($this->em->getRepository(ModeratorPartnersProgram::class)->findBy(['partner_id' => $update->getMessage()->getFrom()->getId()])) < $minReferralsCount) {
            throw new ReferralsCountException();
        }
    }
}