<?php

namespace App\Discord\Commands\Dota2;

use App\Discord\Commands\Command;
use App\Models\DotaParty;
use App\Services\DiscordService;
use App\Services\DotaService;
use Discord\Discord;
use Discord\Parts\Channel\Message;

class CreatePartyCommand extends Command
{
    protected string $name = 'create_party';

    public function execute(Message $message, Discord $discord, array $args): void
    {
        $member = DiscordService::getMemberByMessage($message);

        if (DotaService::hasParty($member)) {
            $message->reply('You are already in the party');

            return;
        }

        $party = DotaParty::create();

        DotaService::joinParty($member, $party, true);

        $message->reply('Party number: ' . $party->id);
    }

}
