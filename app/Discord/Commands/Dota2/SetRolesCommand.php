<?php

namespace App\Discord\Commands\Dota2;

use App\Discord\Commands\Command;
use App\Services\DiscordService;
use App\Services\DotaService;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Exception;

class SetRolesCommand extends Command
{
    protected string $name = 'set_roles';

    public function execute(Message $message, Discord $discord, array $args): void
    {
        if (!isset($args[0])) {
            $message->reply("Enter roles. Example: {$this->getName(true)} 1 2 3 4 5");

            return;
        }

        $member = DiscordService::getMemberByMessage($message);

        try {
            $roles = DotaService::setPartyMemberRoles($member, $args);

            $reply = "Your roles: " . implode(', ', $roles);
        } catch (Exception $e) {
            $reply = $e->getMessage();
        }

        $message->reply($reply);
    }

}
