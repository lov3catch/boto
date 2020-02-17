<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\CommandPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO\MessageDTO;
use App\Botonarioum\Bots\Helpers\GetMe;
use App\Entity\ModeratorBlock;
use App\Entity\ModeratorStart;
use App\Repository\ModeratorBlockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class BlockAllGlobalPipe extends CommandPipe
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function isSupported(Update $update): bool
    {
        if (!parent::isSupported($update)) return false;

//        $botId = (new GetMe())->me($bot);
//        $userId = '';
//
//        $isSuperuser = $this->em->getRepository(ModeratorStart::class)->findOneBy(['bot_id' => $botId, 'user_id' => $userId, 'is_superuser' => true]);
//
////        if ('@omnamas' !== $update->getMessage()->getFrom()->getUsername()) return false;

        $command = explode(' ', $update->getMessage()->getText())[1];

        return ModeratorBlockRepository::BAN_STRATEGY_TOTAL === $command;
    }

    public function processing(Bot $bot, Update $update): bool
    {
        $botId = (new GetMe())->me($bot)->getId();
        $userId = $update->getMessage()->getFrom()->getId();

        $isSuperuser = $this->em->getRepository(ModeratorStart::class)
            ->findOneBy([
                'bot_id'       => $botId,
                'user_id'      => $userId,
                'is_superuser' => true]);

        if (!$isSuperuser) return true;

        $this->doBlock($update, $bot);

        return true;
    }

    private function doBlock(Update $update, Bot $bot): void
    {
        $message = new MessageDTO($update->getMessage());

        $this->em->getRepository(ModeratorBlock::class)
            ->doBlockTotal(
                $message->getReplyToMessage()->getFrom()->getId(),
                $update->getMessage()->getFrom()->getId(),
                $update->getMessage()->getChat()->getId());

        $bot->sendMessage(new SendMessage(
            $update->getMessage()->getChat()->getId(),
            'Пользователь ' . $message->getReplyToMessage()->getFrom()->getUsername() . 'получает бан.'
        ));
    }
}