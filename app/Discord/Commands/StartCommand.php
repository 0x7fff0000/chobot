<?php

namespace App\Discord\Commands;

use Discord\Discord;
use Discord\Parts\Channel\Message;

class StartCommand extends Command
{
    protected string $name = 'start';

    public function execute(Message $message, Discord $discord, array $args): void
    {
        $message->reply('Шо хочеш?');
    }
}
