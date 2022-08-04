<?php

namespace App\Discord\Commands\Dota2;

use App\Discord\Commands\Command;
use App\Models\DotaParty;
use App\Services\DiscordService;
use App\Services\DotaService;
use Discord\Discord;
use Discord\Parts\Channel\Message;

class LeavePartyCommand extends Command
{
    protected string $name = 'leave_party';

    public function execute(Message $message, Discord $discord, array $args): void
    {
        $member = DiscordService::getMemberByMessage($message);

        if (!DotaService::hasParty($member)) {
            $message->reply('You are not in a party');

            return;
        }

        $member->dotaParties->each(fn (DotaParty $party) => DotaService::leaveParty($member, $party));

        $message->reply('You left the party');
    }

}
