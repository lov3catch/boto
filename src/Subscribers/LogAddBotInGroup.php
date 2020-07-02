<?php

declare(strict_types=1);

namespace App\Subscribers;

use App\Entity\Element;
use App\Entity\ElementType;
use App\Entity\Platform;
use App\Events\AddedUserInGroupEvent;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LogAddBotInGroup implements EventSubscriberInterface
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
//        $update = $event->getUpdate();
//
//        $groupId = $update->getMessage()->getChat()->getId();
//
//        /** @var ElementType $type */
//        $type = $this->entityManager->getRepository(ElementType::class)->findOneBy(['name' => ElementType::GROUP_TYPE]);
//
//        /** @var Platform $platform */
//        $platform = $this->entityManager->getRepository(Platform::class)->findOneBy(['name' => Platform::TELEGRAM_TYPE]);
//
//        $alreadyLogged = (bool)$this->entityManager->getRepository(Element::class)->findOneBy(['group_id' => $groupId]);
//
//        if ($alreadyLogged) return;
//
//        try {
//            $row = new Element();
//            $row->setGroupId($groupId);
//            $row->setStatus(true);
//            $row->setName($event->getUpdate()->getMessage()->getChat()->getTitle());
//            $row->setDescription($event->getUpdate()->getMessage()->getChat()->getType());
//            $row->setTypeId($type);
//            $row->setPlatformId($platform);
//            $row->setUrl('');
//
//            $this->entityManager->persist($row);
//            $this->entityManager->flush();
//        } catch (\Throwable $exception) {
//            $this->logger->error($exception->getMessage());
//        }
    }
}