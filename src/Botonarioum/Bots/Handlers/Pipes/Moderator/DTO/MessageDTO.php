<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO;

use Formapro\TelegramBot\Message;
use Formapro\TelegramBot\User;
use function Formapro\Values\get_object;

class MessageDTO extends Message
{
    /**
     * @var Message
     */
    private $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function getNewChatMember(): ?NewChatMemberDTO
    {
        return get_object($this->message, 'new_chat_member', NewChatMemberDTO::class);
    }
}