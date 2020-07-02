<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers;

use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\ReferralsCountException;
use App\Entity\ModeratorReferral;
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

        $referrals = $this->em->getRepository(ModeratorReferral::class)
            ->findBy([
                'user_id'  => $update->getMessage()->getFrom()->getId(),
                'group_id' => $update->getMessage()->getChat()->getId()]);

        if (count($referrals) < $minReferralsCount) {
            throw new ReferralsCountException('Необходимо добавить ' . $setting->getMinReferralsCount() . ' человек, вы добавили ' . count($referrals) . '. Добавьте еще.');
        }
    }
}