<?php

namespace App\Command;

use App\Storages\RedisStorage;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\DeleteMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ModeratorDeleteTempMessagesCommand extends Command
{
    protected static $defaultName = 'ModeratorDeleteTempMessages';
    /**
     * @var \Predis\Client
     */
    private $client;

    public function __construct(string $name = null, RedisStorage $redisStorage)
    {
        $this->client = $redisStorage->client();
        parent::__construct($name);
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
        $io->title('Remove temporary messages');

        $key = 'moderator:temp:messages';
        while (true) {
            sleep(1);
            try {
                $tempMessage = $this->client->rpop($key);

                if (!(bool)$tempMessage) continue;

                $tempMessage = json_decode($tempMessage, true);

                if (($tempMessage['created'] + 15) < time()) {
                    $bot = new Bot($tempMessage['token']);

                    $bot->deleteMessage(new DeleteMessage($tempMessage['chat_id'], $tempMessage['message_id']));
                    $io->success('REMOVE MESSAGE SUCCESS');
                    continue;
                }

                $io->note('REMOVE AFTER ' . (($tempMessage['created'] + 15) - time()));

                $this->client->lpush($key, [json_encode($tempMessage)]);


            } catch (\Exception $exception) {
                echo $exception->getMessage();
            }
        }

        return 0;
    }
}
