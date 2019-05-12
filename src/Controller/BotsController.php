<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BotsController
{
    /**
     * @Route("/bots/{token}", name="handler")
     */
    public function handler(string $token): Response
    {
        return new Response('It works! ☺');
    }
}
