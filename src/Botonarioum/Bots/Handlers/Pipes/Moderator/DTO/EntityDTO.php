<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO;

use Formapro\TelegramBot\Message;
use function Formapro\Values\get_value;

class EntityDTO extends Message
{
    private $values = [];

    private $objects = [];

    public function getOffset(): int
    {
        return get_value($this, 'offset');
    }

    public function getLength(): int
    {
        return get_value($this, 'length');
    }

    public function getType(): string
    {
        return get_value($this, 'type');
    }
}