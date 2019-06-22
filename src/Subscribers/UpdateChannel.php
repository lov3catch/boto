<?php

declare(strict_types=1);

namespace App\Subscribers;

use App\Entity\Channel;
use App\Events\ActivityEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\DuplicateKeyException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UpdateChannel implements EventSubscriberInterface
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
        $request = $event->getRequestContent();

        try {
            $channel = new Channel();
            $channel->setChannelId($request['message']['chat']['id']);
            $channel->setFirstName($request['message']['from']['first_name']);
            $channel->setLastName($request['message']['from']['last_name']);
            $channel->setLanguageCode($request['message']['from']['language_code']);
            $channel->setHandlerName('example-handler');
            $channel->setCreatedAt(new \DateTime());
            $channel->setUpdatedAt(new \DateTime());

            $this->entityManager->persist($channel);
            $this->entityManager->flush();
        } catch (DuplicateKeyException $exception) {
            $channel = $this->entityManager->getRepository(Channel::class)->find($request['message']['chat']['id']);
            $channel->setFirstName($request['message']['from']['first_name']);
            $channel->setLastName($request['message']['from']['last_name']);
            $channel->setLanguageCode($request['message']['from']['language_code']);
            $channel->setUpdatedAt(new \DateTime());

            $this->entityManager->persist($channel);
            $this->entityManager->flush();
            echo $exception->getMessage();
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }
}