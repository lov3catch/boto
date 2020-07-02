<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\CallbackPipe;
use App\Entity\ModeratorBlock;
use App\Entity\ModeratorMember;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\AnswerCallbackQuery;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\InlineKeyboardButton;
use Formapro\TelegramBot\InlineKeyboardMarkup;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class GroupBlockRemovePipe extends CallbackPipe
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
        try {
            $moderatorBlockRepository = $this->em->getRepository(ModeratorBlock::class);

            $moderatorBlockInfoRepository = $this->em->getRepository(ModeratorMember::class);

            $blockId = array_reverse(explode(':', $update->getCallbackQuery()->getData()))[0];

            $blockedMember = $moderatorBlockRepository->findOneBy(['id' => $blockId]);

            if (!$blockedMember instanceof ModeratorBlock) return true;

            $blockMemberInfo = $moderatorBlockInfoRepository->findOneBy(['member_id' => $blockedMember->getUserId()]);

            if (!$blockMemberInfo instanceof ModeratorMember) return true;

            $this->em->remove($blockedMember);
            $this->em->remove($blockMemberInfo);

            $this->em->flush();


            $callBackAnswer = new AnswerCallbackQuery($update->getCallbackQuery()->getId());
            $callBackAnswer->setShowAlert(true);
            $callBackAnswer->setText('❤️ Блокировка снята.');

            $bot->answerCallbackQuery($callBackAnswer);

        } catch (\Throwable $exception) {
            var_dump($exception->getMessage());
        }




        return true;


//
        $blockList = $moderatorBlockRepository->findBy(['admin_id' => $update->getCallbackQuery()->getFrom()->getId()]);
//
        if ([] === $blockList) {
            $bot->sendMessage(new SendMessage(
                $update->getCallbackQuery()->getMessage()->getChat()->getId(),
                'Список пуст.'
            ));

            return true;
        }

        $keyboard = [];

        /**
         * @var $block ModeratorBlock
         */
        foreach ($blockList as $block) {
            // todo: оптимизировать получение детальной информации. Выше мы получаем всех забаненых пользователей. Получать детальную инфу по айдишникам и сливать в один массив

            /** @var ModeratorMember $userDetail */
            $userDetail = $this->em->getRepository(ModeratorMember::class)->findOneBy(['member_id' => $block->getUserId()]);

            $unblockCallbackData = implode(':', ['group', 'blocklist', 'info', $block->getId()]);

            if ($userDetail instanceof ModeratorMember) {
                $firstName = $userDetail->getMemberFirstName();
                $userName = $userDetail->getMemberUsername();
            } else {
                $firstName = $block->getUserId();
                $userName = '';
            }

            $title = "$firstName $userName";

            $keyboard[] = [InlineKeyboardButton::withCallbackData($title, $unblockCallbackData), InlineKeyboardButton::withCallbackData($title, $unblockCallbackData)];
        }

        $message = new SendMessage(
            $update->getCallbackQuery()->getMessage()->getChat()->getId(),
            'Список заблокированных пользователей:'
        );

        $message->setReplyMarkup(new InlineKeyboardMarkup($keyboard));

        $bot->sendMessage($message);

        return true;
    }

    public function isSupported(Update $update): bool
    {
        if (!parent::isSupported($update)) return false;

        if (false === strpos($update->getCallbackQuery()->getData(), implode(':', ['group', 'blocklist', 'remove']))) return false;

        return true;
    }
}