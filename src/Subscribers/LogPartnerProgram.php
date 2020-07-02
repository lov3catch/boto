<?php

declare(strict_types=1);

namespace App\Subscribers;

use App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO\MessageDTO;
use App\Entity\ModeratorReferral;
use App\Events\AddedUserInGroupEvent;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\ChatMember;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

//use App\Entity\ModeratorPartnersProgram;

class LogPartnerProgram implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * ['eventName' => 'methodName']
     *  * ['eventName' => ['methodName', $priority]]
     *  * ['eventName' => [['methodName1', $priority], ['methodName2']]]
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [AddedUserInGroupEvent::EVENT_NAME => 'onAction'];
    }

    public function onAction(AddedUserInGroupEvent $event): void
    {
        if (!$event->getUpdate()->getMessage()->getNewChatMember() instanceof ChatMember) return;

        $messageDTO = new MessageDTO($event->getUpdate()->getMessage());

        $newChatMembers = $messageDTO->getNewChatMembers();


        $referralRepository = $this->entityManager->getRepository(ModeratorReferral::class);

        $groupId = $event->getUpdate()->getMessage()->getChat()->getId();
        $userId = $event->getUpdate()->getMessage()->getFrom()->getId();


        /** @var ChatMember $newChatMember */
        foreach ($newChatMembers as $newChatMember) {
            if ($newChatMember->isBot()) continue;

//            $referralId = $event->getUpdate()->getMessage()->getNewChatMember()->getId();
            $referralId = $newChatMember->getId();

            $options = ['referral_id' => $referralId, 'group_id' => $groupId];
            $defaults = ['referral_id' => $referralId, 'group_id' => $groupId, 'user_id' => $userId];

            $referralRepository->getOrCreate($options, $defaults);
        }

//        die;

//        if ($event->getUpdate()->getMessage()->getNewChatMember()->isBot()) return;


//        $update = $event->getUpdate();
//
//        $groupId = $update->getMessage()->getChat()->getId();
//        $partnerId = $update->getMessage()->getFrom()->getId();
//        $referralId = $update->getMessage()->getNewChatMember()->getId();
//
//        $alreadyLogged = (bool)$this->entityManager->getRepository(ModeratorPartnersProgram::class)->findOneBy(['group_id' => $groupId, 'partner_id' => $partnerId, 'referral_id' => $referralId]);
//        $isBot = $event->getUpdate()->getMessage()->getNewChatMember()->isBot();

//        if ($alreadyLogged || $isBot) return;

//            try {
//
//                $row = new ModeratorPartnersProgram();
//                $row->setGroupId($groupId);
//                $row->setPartnerId($partnerId);
//                $row->setReferralId($referralId);
//                $row->setCreatedAt(new \DateTime());
//
//                $this->entityManager->persist($row);
//                $this->entityManager->flush();
//            } catch (\Throwable $exception) {
//                $this->logger->error($exception->getMessage());
//            }
    }
}