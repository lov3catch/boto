<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO;

use GuzzleHttp\ClientInterface;
use function Formapro\Values\get_values;

class BotDTO
{
    private $token;

    private $httpClient;

    public function __construct(string $token, ClientInterface $httpClient = null)
    {
        $this->token = $token;
        $this->httpClient = $httpClient ?? new \GuzzleHttp\Client();
    }

    public function kickChatMember(KickChatMemberDTO $kickChatMember)
    {
        $response = $this->httpClient->post($this->getMethodUrl('kickChatMember'), [
            'json' => get_values($kickChatMember),
        ]);

        $json = json_decode((string)$response->getBody(), true);

        return (isset($json['ok']) && $json['ok']) && (isset($json['result']) && $json['result']);

    }

    private function getMethodUrl(string $method): string
    {
        return sprintf('https://api.telegram.org/bot%s/%s', $this->token, $method);
    }
}