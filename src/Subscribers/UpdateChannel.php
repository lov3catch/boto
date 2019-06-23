<?php

declare(strict_types=1);

namespace App\Subscribers;

use App\Entity\Channel;
use App\Events\ActivityEvent;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UpdateChannel implements EventSubscriberInterface
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
            $channel = $this->entityManager
                ->getRepository(Channel::class)
                ->findOneBy(['channel_id' => $request['message']['chat']['id'], 'handler_name' => $handler::HANDLER_NAME]);

            if ($channel) {
                $channel->setFirstName($request['message']['from']['first_name']);
                $channel->setLastName($request['message']['from']['last_name']);
                $channel->setLanguageCode($request['message']['from']['language_code']);
                $channel->setUpdatedAt(new \DateTime());
            } else {
                $channel = new Channel();
                $channel->setChannelId($request['message']['chat']['id']);
                $channel->setFirstName($request['message']['from']['first_name']);
                $channel->setLastName($request['message']['from']['last_name']);
                $channel->setLanguageCode($request['message']['from']['language_code']);
                $channel->setHandlerName($handler::HANDLER_NAME);
                $channel->setCreatedAt(new \DateTime());
                $channel->setUpdatedAt(new \DateTime());
            }

            $this->entityManager->persist($channel);
            $this->entityManager->flush();
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }
}