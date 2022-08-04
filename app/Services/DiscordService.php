<?php

namespace App\Services;

use App\Models\DiscordMember;
use Discord\Parts\Channel\Message;

class DiscordService
{
    public static function getMemberByMessage(Message $message): DiscordMember
    {
        return DiscordMember::firstOrCreate([
            'member_id' => $message->member->id
        ], [
            'username' => $message->member->username,
            'discriminator' => $message->member->discriminator
        ]);
    }
}
