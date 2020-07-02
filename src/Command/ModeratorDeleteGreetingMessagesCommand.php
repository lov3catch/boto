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
        $io->title('Remove greeting messages media');

        while (true) {
            try {
                $groupIds = json_decode(file_get_contents('https://boto-all-in-one.herokuapp.com/group_ids'));
                foreach ($groupIds as $groupId) {
                    $key = RedisKeys::makeLastGreetingsMessageIdKey($groupId);
                    try {
                        $this->clear($key, $io);
                    } catch (\Exception $exception) {
                        var_dump($exception->getMessage());
                    }
                }
            } catch (\Throwable $exception) {
                echo $exception->getMessage() . PHP_EOL;
            }

            sleep(5);
        }

        return 0;
    }

    private function clear(string $greetingsRedisKey, SymfonyStyle $io): void
    {
        // если нет чего удалять - выходим
        if ((int)$this->client->llen($greetingsRedisKey) < 2) return;

        $lastGreeting = $this->client->lpop($greetingsRedisKey);
        $otherGreeting = array_values(array_unique($this->client->lrange($greetingsRedisKey, 0, -1)));
        $this->client->del([$greetingsRedisKey]);

        foreach ($otherGreeting as $greeting) {
            try {
                $io->note('MODERATOR REMOVE GREETING');

                $data = json_decode($greeting, true);

                if (!$data['token']) continue;

                $bot = new Bot($data['token']);
                $chatId = $data['chat_id'];
                $messageId = $data['message_id'];

                $isDeleted = $bot->deleteMessage(new DeleteMessage($chatId, $messageId));

                if ($isDeleted === false) {
                    $this->client->lpush($greetingsRedisKey, [$greeting]);
                    $unremovedGreeting[] = $greeting;
                }

            } catch (\Throwable $exception) {
                var_dump($exception->getMessage());
            }
        }

        $this->client->rpush($greetingsRedisKey, [$lastGreeting]);
    }
}
