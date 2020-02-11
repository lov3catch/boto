<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO;

use Formapro\TelegramBot\Message;
use function Formapro\Values\get_object;
use function Formapro\Values\get_objects;

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

    public function getEntities(): ?\Generator
    {
        return get_objects($this->message, 'entities', EntityDTO::class);
    }

//    /**
//     * @deprecated
//     * @return ChatMemberDTO|null
//     */
//    public function getNewChatMember(): ?ChatMemberDTO
//    {
//        return get_object($this->message, 'new_chat_member', ChatMemberDTO::class);
//    }

//    /**
//     * @deprecated
//     * @return ChatMemberDTO|null
//     */
//    public function getLeftChatMember(): ?ChatMemberDTO
//    {
//        return get_object($this->message, 'left_chat_member', ChatMemberDTO::class);
//    }

    public function getReplyToMessage(): ?ReplyToMessageDTO
    {
        return get_object($this->message, 'reply_to_message', ReplyToMessageDTO::class);
    }
}