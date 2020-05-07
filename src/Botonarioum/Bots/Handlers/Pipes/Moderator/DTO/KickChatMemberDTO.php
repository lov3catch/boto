<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO;

use function Formapro\Values\set_value;

class KickChatMemberDTO
{
    private $values = [];

    public function __construct(string $chatId, int $userId, int $untilDate)
    {
        set_value($this, 'chat_id', $chatId);
        set_value($this, 'user_id', $userId);
        set_value($this, 'until_date', $untilDate);
    }
}