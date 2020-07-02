<?php

declare(strict_types=1);

namespace App\Subscribers;

use App\Entity\ModeratorGroup;
use App\Events\AddedUserInGroupEvent;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

//use App\Entity\ModeratorGroupOwners;

class LogAddGroupInfo implements EventSubscriberInterface
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
        $moderatorGroupRepository = $this->entityManager->getRepository(ModeratorGroup::class);

        $options = ['group_id' => $event->getUpdate()->getMessage()->getChat()->getId()];
        $defaults = [
            'group_id'       => $event->getUpdate()->getMessage()->getChat()->getId(),
            'group_title'    => $event->getUpdate()->getMessage()->getChat()->getTitle(),
            'group_username' => $event->getUpdate()->getMessage()->getChat()->getUsername() ?? 'username-not-found',
            'group_type'     => $event->getUpdate()->getMessage()->getChat()->getType(),
        ];

        $moderatorGroupRepository->getOrCreate($options, $defaults);
    }
}