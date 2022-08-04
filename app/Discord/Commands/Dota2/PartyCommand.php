<?php

namespace App\Discord\Commands\Dota2;

use App\Discord\Commands\Command;
use App\Services\DiscordService;
use App\Services\DotaService;
use Discord\Discord;
use Discord\Parts\Channel\Message;

class PartyCommand extends Command
{
    protected string $name = 'party';

    public function execute(Message $message, Discord $discord, array $args): void
    {
        $member = DiscordService::getMemberByMessage($message);

        if (!DotaService::hasParty($member)) {
            $message->reply('You are not in a party');

            return;
        }

        $party = $member->dotaParties()->latest()->first();

        $message->reply(DotaService::getPartyInfo($party));
    }

}
