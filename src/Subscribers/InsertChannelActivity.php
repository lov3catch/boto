<?php

declare(strict_types=1);

namespace App\Subscribers;

use App\Entity\Channel;
use App\Events\ActivityEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class InsertChannelActivity implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
        $token = $event->getBot()->getToken();
        $request = $event->getRequestContent();

        try {
            $channel = new Channel();
            $channel->setToken($token);
            $channel->setChannelId($request['message']['chat']['id']);
            $channel->setFirstName($request['message']['from']['first_name']);
            $channel->setLastName($request['message']['from']['last_name']);
            $channel->setCreatedAt(new \DateTime());
            $channel->setUpdatedAt(new \DateTime());

            $this->entityManager->persist($channel);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }
}