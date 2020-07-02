<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\EditCheckers;

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
                'user_id'  => $update->getEditedMessage()->getFrom()->getId(),
                'group_id' => $update->getEditedMessage()->getChat()->getId()]);

        if (count($referrals) < $minReferralsCount) {
            throw new ReferralsCountException('Необходимо пригласить ' . $setting->getMinReferralsCount() . ' человек, вы пригласили ' . count($referrals) . '. Пригласите еще.');
        }
    }
}