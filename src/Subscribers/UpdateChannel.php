<?php

declare(strict_types=1);

namespace App\Subscribers;

use App\Botonarioum\Bots\Handlers\BotHandlerInterface;
use App\Botonarioum\Bots\Handlers\ModeratorHandler;
use App\Entity\Channel;
use App\Events\ActivityEvent;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UpdateChannel implements EventSubscriberInterface
{
    private const NON_LOGGING_HANDLERS = [ModeratorHandler::class];

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
        if (!$this->isNeedToLogging($event->getHandler())) return;

        try {
            $handler = $event->getHandler();
            $update = $event->getUpdate();

            $chat = $update->getCallbackQuery() ? $update->getCallbackQuery()->getMessage()->getChat() : $update->getMessage()->getChat();
            $from = $update->getCallbackQuery() ? $update->getCallbackQuery()->getFrom() : $update->getMessage()->getFrom();

            $channel = $this->entityManager
                ->getRepository(Channel::class)
                ->findOneBy(['channel_id' => $chat->getId(), 'handler_name' => $handler::HANDLER_NAME]);

            if ($channel) {
                $channel->setFirstName($from->getFirstName());
                $channel->setLastName($from->getLastName());
                $channel->setLanguageCode($from->getLanguageCode() ?? 'en');
                $channel->setUpdatedAt(new \DateTime());
            } else {
                $channel = new Channel();
                $channel->setChannelId($chat->getId());
                $channel->setFirstName($from->getFirstName());
                $channel->setLastName($from->getLastName());
                $channel->setLanguageCode($from->getLanguageCode() ?? 'en');
                $channel->setHandlerName($handler::HANDLER_NAME);
                $channel->setCreatedAt(new \DateTime());
                $channel->setUpdatedAt(new \DateTime());
            }

            $this->entityManager->persist($channel);
            $this->entityManager->flush();
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    private function isNeedToLogging(BotHandlerInterface $botHandler): bool
    {
        foreach (self::NON_LOGGING_HANDLERS as $NON_LOGGING_HANDLER) {
            if (get_class($botHandler) === $NON_LOGGING_HANDLER) return false;
        }

        return true;
    }
}
