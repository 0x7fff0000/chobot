<?php

namespace App\Discord\Commands\Dota2;

use App\Discord\Commands\Command;
use App\Services\DiscordService;
use App\Services\DotaService;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Exception;

class RemoveRoleCommand extends Command
{
    protected string $name = 'remove_role';

    public function execute(Message $message, Discord $discord, array $args): void
    {
        if (!isset($args[0])) {
            $message->reply("Enter role number. [1-5]");

            return;
        }

        $member = DiscordService::getMemberByMessage($message);

        try {
            DotaService::removePartyMemberRole($member, $args[0]);

            $reply = 'Role removed';
        } catch (Exception $e) {
            $reply = $e->getMessage();
        }

        $message->reply($reply);
    }

}
