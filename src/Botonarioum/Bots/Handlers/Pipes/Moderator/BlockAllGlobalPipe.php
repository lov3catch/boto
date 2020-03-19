<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\CommandPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO\MessageDTO;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO\ReplyToMessageDTO;
use App\Botonarioum\Bots\Helpers\GetMe;
use App\Entity\ModeratorBlock;
use App\Entity\ModeratorMember;
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

        $message = new MessageDTO($update->getMessage());

        if (!$message->getReplyToMessage() instanceof ReplyToMessageDTO) return false;

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

        $options = ['member_id' => $message->getReplyToMessage()->getFrom()->getId()];
//        $defaults = [
//            'member_id'         => $update->getMessage()->getFrom()->getId(),
//            'member_first_name' => $update->getMessage()->getFrom()->getFirstName() ?? '',
//            'member_username'   => $update->getMessage()->getFrom()->getUsername() ?? '',
//            'member_is_bot'     => $update->getMessage()->getFrom()->isBot(),
//        ];
        $defaults = [
            'member_id'         => $message->getReplyToMessage()->getFrom()->getId() ?? '',
            'member_first_name' => $message->getReplyToMessage()->getFrom()->getFirstName() ?? '',
            'member_username'   => $message->getReplyToMessage()->getFrom()->getUsername() ?? '',
            'member_is_bot'     => false
        ];
        $this->em->getRepository(ModeratorMember::class)->getOrCreate($options, $defaults);

        $bot->sendMessage(new SendMessage(
            $update->getMessage()->getChat()->getId(),
            'Пользователь ' . $message->getReplyToMessage()->getFrom()->getFirstName() . ' получает бан.'
        ));
    }
}