<?php

declare(strict_types=1);

namespace App\Message;

final class UpdateMessage
{
    /**
     * @var array
     */
    private $update;
    /**
     * @var string
     */
    private $token;

    public function __construct(string $token, array $update)
    {
        $this->update = $update;
        $this->token = $token;
    }

    /**
     * @return array
     */
    public function getUpdate(): array
    {
        return $this->update;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
}
