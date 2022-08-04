<?php

namespace App\Discord\Commands\Dota2;

use App\Discord\Commands\Command;
use App\Services\DiscordService;
use App\Services\DotaService;
use Discord\Discord;
use Discord\Parts\Channel\Message;

class RefreshRolesCommand extends Command
{
    protected string $name = 'refresh_roles';

    public function execute(Message $message, Discord $discord, array $args): void
    {
        $member = DiscordService::getMemberByMessage($message);
        $party = DotaService::getMemberParty($member);

        if (!$party) {
            $message->reply('You are not in a party');

            return;
        }

        if (!$party->pivot->is_leader) {
            $message->reply('You are not a leader');

            return;
        }

        DotaService::refreshPartyRoles($party);

        $party->load('members');

        $message->reply(DotaService::getPartyInfo($party));
    }

}
