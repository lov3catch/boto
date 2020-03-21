<?php

namespace App\Command;

use App\Botonarioum\Bots\Helpers\RedisKeys;
use App\Storages\RedisStorage;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\DeleteMessage;
use Predis\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ModeratorDeleteGreetingMessagesCommand extends Command
{
    protected static $defaultName = 'ModeratorDeleteGreetingMessages';
    /**
     * @var Client
     */
    private $client;

    public function __construct(RedisStorage $redisStorage, string $name = null)
    {
        parent::__construct($name);
        $this->client = $redisStorage->client();
    }


    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Remove greeting messages');

        $key = RedisKeys::makeLastGreetingsMessageQueueIdKey();

        while (true) {
            try {
                $this->clear($key);
            } catch (\Exception $exception) {
                var_dump($exception->getMessage());
            }
        }

        return 0;
    }

    private function clear(string $greetingsRedisKey): void
    {
        // если нет чего удалять - выходим
        if ((int)$this->client->llen($greetingsRedisKey) < 2) return;

        $lastGreeting = $this->client->lpop($greetingsRedisKey);
        $otherGreeting = array_unique($this->client->lrange($greetingsRedisKey, 0, -1));

        foreach ($otherGreeting as $greeting) {
            try {
                echo 'MODERATOR REMOVE GREETING';

                $data = json_decode($greeting, true);

                $bot = new Bot($data['token']);
                $chatId = $data['chat_id'];
                $messageId = $data['message_id'];

                $bot->deleteMessage(new DeleteMessage($chatId, $messageId));
            } catch (\Throwable $exception) {
                var_dump($exception->getMessage());
            }
        }

        $this->client->del([$greetingsRedisKey]);

//        if ($lastGreeting) {
        $this->client->rpush($greetingsRedisKey, [$lastGreeting]);
//        }
    }
}