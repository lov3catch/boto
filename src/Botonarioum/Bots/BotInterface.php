<?php declare(strict_types=1);

namespace App\Botonarioum\Bots;

use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Update;
use Symfony\Component\HttpFoundation\Request;

interface BotInterface
{
    public function handle(Update $update, EntityManagerInterface $entityManager): bool;

    public function getToken(): string;

    public function isCurrentBot(Request $request): bool;
}
