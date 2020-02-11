<?php

declare(strict_types=1);


namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\BlockStrategies;


use Formapro\TelegramBot\Chat;
use Formapro\TelegramBot\User;

class Block
{
    /**
     * @var int
     */
    protected $groupId;
    /**
     * @var int
     */
    protected $userId;
    /**
     * @var int
     */
    protected $adminId;

    public function __construct(Chat $chat, User $user, User $admin)
    {
        $this->groupId = $groupId;
        $this->userId = $userId;
        $this->adminId = $adminId;
    }

    public function do(): void
    {
        //
    }
}