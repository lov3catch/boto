<?php

declare(strict_types=1);

namespace App\Subscribers;

use App\Entity\ChannelActivity;
use App\Events\ActivityEvent;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TrackChannelActivity implements EventSubscriberInterface
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
        return [ActivityEvent::EVENT_NAME => 'onAction'];
    }

    public function onAction(ActivityEvent $event): void
    {
        $handler = $event->getHandler();
        $request = $event->getRequestContent();

        try {
            $channelActivite = (new ChannelActivity())
                ->setChannelId($request['message']['chat']['id'])
                ->setHandlerName($handler::HANDLER_NAME)
                ->setCreatedAt(new \DateTime());

            $this->entityManager->persist($channelActivite);
            $this->entityManager->flush();
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }
}