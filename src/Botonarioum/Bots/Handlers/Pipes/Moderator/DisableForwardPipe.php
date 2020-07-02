<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\CallbackPipe;
use App\Entity\ModeratorSetting;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Parameter;
use Formapro\TelegramBot\AnswerCallbackQuery;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\Update;

class DisableForwardPipe extends CallbackPipe
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
            $groupId = explode(':', $update->getCallbackQuery()->getData())[5];

            /** @var ModeratorSetting $settings */
            $settings = ($this->em->getRepository(ModeratorSetting::class)->createQueryBuilder('setting'))
                            ->where('setting.is_default = :isd')
                            ->orWhere('setting.group_id = :grid')
                            ->orderBy('setting.is_default', 'ASC')
                            ->setParameters(new ArrayCollection([new Parameter('isd', true), new Parameter('grid', (int)$groupId)]))
                            ->getQuery()
                            ->getResult()[0];

            /** @var ModeratorSetting $newSettings */
            if ($settings->getIsDefault()) {
                $settings = clone $settings;
            }

            $settings->setAllowForward(false);

            // todo: создать запись, если нету
            $this->em->persist($settings);
            $this->em->flush();

            $answer = new AnswerCallbackQuery($update->getCallbackQuery()->getId());
            $answer->setText('✅ Настройки обновлены');

            $bot->answerCallbackQuery($answer);
        } catch (\Exception $exception) {
            $answer = new AnswerCallbackQuery($update->getCallbackQuery()->getId());
            $answer->setText('🚫 Что-то пошло не так');

            $bot->answerCallbackQuery($answer);
        }

        return true;
    }

    public function isSupported(Update $update): bool
    {
        if (!parent::isSupported($update)) return false;

        if (false === strpos($update->getCallbackQuery()->getData(), implode(':', ['moderator', 'group', 'settings', 'forward', 'disable']))) return false;

        return true;
    }
}