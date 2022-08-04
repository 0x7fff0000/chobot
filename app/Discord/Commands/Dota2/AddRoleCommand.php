<?php

namespace App\Discord\Commands\Dota2;

use App\Discord\Commands\Command;
use App\Services\DiscordService;
use App\Services\DotaService;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Exception;

class AddRoleCommand extends Command
{
    protected string $name = 'add_role';

    public function execute(Message $message, Discord $discord, array $args): void
    {
        if (!isset($args[0])) {
            $message->reply("Enter role. [1-5]");

            return;
        }

        $member = DiscordService::getMemberByMessage($message);

        try {
            DotaService::addPartyMemberRole($member, $args[0]);

            $reply = 'Role added';
        } catch (Exception $e) {
            $reply = $e->getMessage();
        }

        $message->reply($reply);
    }

}
