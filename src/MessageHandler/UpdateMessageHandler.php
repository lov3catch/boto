<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Botonarioum\Bots\Handlers\ModeratorHandler;
use App\Message\UpdateMessage;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\Update;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class UpdateMessageHandler implements MessageHandlerInterface
{
    /**
     * @var ModeratorHandler
     */
    private $handler;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(ModeratorHandler $handler, LoggerInterface $logger, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->logger = $logger;
        $this->handler = $handler;
    }

    public function __invoke(UpdateMessage $message)
    {
//        $cacheDriver = new \Doctrine\Common\Cache\ArrayCache();
//        $deleted = $cacheDriver->deleteAll();

        $this->em->clear();
        $this->handler->handle(
            new Bot($message->getToken()),
            Update::create($message->getUpdate())
        );
    }
}
