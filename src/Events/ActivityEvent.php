<?php

declare(strict_types=1);

namespace App\Events;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class ActivityEvent extends Event
{
    public const EVENT_NAME = 'bots.activity';
    /**
     * @var Request
     */
    private $request;

    /**
     * @var string
     */
    private $token;

    public function __construct(Request $request, string $token)
    {
        $this->request = $request;
        $this->token = $token;
    }

    public function getRequestContent(): array
    {
        return json_decode($data = $this->request->getContent(), true);
    }

    public function getToken(): string
    {
        return $this->token;
    }
}