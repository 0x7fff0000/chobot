<?php

namespace App\Discord\Commands\Dota2;

use App\Discord\Commands\Command;
use Discord\Discord;
use Discord\Parts\Channel\Message;

class DeleteDotaCommand extends Command
{
    protected string $name = 'delete_dota';

    public function execute(Message $message, Discord $discord, array $args): void
    {
        $message->reply('А в тебе великі яйця');
    }

}
