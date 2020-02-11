<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO;

use Formapro\TelegramBot\Message;
use Formapro\TelegramBot\User;
use function Formapro\Values\get_object;
use function Formapro\Values\get_value;

/**
 * Class ChatMemberDTO
 * @package App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO
 * @deprecated
 */
class ChatMemberDTO extends Message
{
    private $values = [];

    private $objects = [];

    public function getId(): int
    {
        return get_value($this, 'id');
    }

    public function isBot(): bool
    {
        return get_value($this, 'is_bot', false);
    }

    public function getFirstName(): ?string
    {
        return get_value($this, 'first_name');
    }
    public function getUsername(): ?string
    {
        return get_value($this, 'username');
    }
}