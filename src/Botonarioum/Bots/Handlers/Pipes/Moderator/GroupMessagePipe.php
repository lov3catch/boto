<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\MessagePipe as BaseMessagePipe;
use App\Entity\ModeratorPartnersProgram;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\DeleteMessage;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class GroupMessagePipe extends BaseMessagePipe
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function processing(Bot $bot, Update $update): bool
    {
        // check links
        foreach (['@', 'http:', 'https:', 'http://', 'https://'] as $linkPattern) {
            if (strpos($update->getMessage()->getText(), $linkPattern) !== false) {
                $bot->deleteMessage(new DeleteMessage($update->getMessage()->getChat()->getId(), $update->getMessage()->getMessageId()));
                $bot->sendMessage(new SendMessage(
                    $update->getMessage()->getChat()->getId(),
                    'Ссылки запрещенны'
                ));

                return true;
            }
        }



        if (count($this->em->getRepository(ModeratorPartnersProgram::class)->findBy(['partner_id' => $update->getMessage()->getFrom()->getId()])) > 10) {
//            $bot->sendMessage(new SendMessage(
//                $update->getMessage()->getChat()->getId(),
//                'OK'
//            ));




            return true;
        }

        $bot->deleteMessage(new DeleteMessage($update->getMessage()->getChat()->getId(), $update->getMessage()->getMessageId()));
        $bot->sendMessage(new SendMessage(
            $update->getMessage()->getChat()->getId(),
            'Вы еще никого не пригласили :('
        ));

        return true;
    }

    public function isSupported(Update $update): bool
    {
//        die;
        if ($update->getCallbackQuery()) return false;

        if (null === $update->getMessage()) return false;

        return $update->getMessage()->getChat()->getId() < 0;

        return ($update->getMessage()) ? true : false;
    }
}