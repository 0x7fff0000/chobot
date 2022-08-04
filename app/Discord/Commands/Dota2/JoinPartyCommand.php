<?php

namespace App\Discord\Commands\Dota2;

use App\Discord\Commands\Command;
use App\Services\DiscordService;
use App\Services\DotaService;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Exception;

class JoinPartyCommand extends Command
{
    protected string $name = 'join_party';

    public function execute(Message $message, Discord $discord, array $args): void
    {
        if (!isset($args[0])) {
            $message->reply('Enter party number');

            return;
        }

        $member = DiscordService::getMemberByMessage($message);
        $party = DotaService::getParty($args[0]);

        if (!$party) {
            $message->reply('Party not found');

            return;
        }

        try {
            DotaService::joinParty($member, $party);

            $party->load('members');

            $reply = DotaService::getPartyInfo($party);
        } catch (Exception $e) {
            $reply = $e->getMessage();
        }

        $message->reply($reply);
    }

}
