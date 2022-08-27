<?php

namespace App\Discord\Commands\Dota2;

use App\Discord\Commands\Command;
use App\Services\DiscordService;
use App\Services\DotaService;
use Discord\Discord;
use Discord\Parts\Channel\Message;

class KickMemberCommand extends Command
{
    protected string $name = 'kick_member';

    public function execute(Message $message, Discord $discord, array $args): void
    {
        $memberNumber = $args[0] ?? null;

        if (!$memberNumber || $memberNumber < 1 && $memberNumber > 5) {
            $message->reply("Enter member number. [1-5]");

            return;
        }

        $member = DiscordService::getMemberByMessage($message);

        if (!DotaService::hasParty($member)) {
            $message->reply('You are not in a party');

            return;
        }

        $party = DotaService::getMemberParty($member);

        if (!$party->pivot->is_leader) {
            $message->reply('You are not a leader');

            return;
        }

        $kickedMember = $party->members->get($memberNumber - 1);

        if (!$kickedMember) {
            $message->reply('Member does not exist');

            return;
        }

        DotaService::leaveParty($kickedMember, $party);

        $message->reply("{$kickedMember->mention} kicked");
    }

}
