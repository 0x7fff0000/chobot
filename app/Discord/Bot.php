<?php

namespace App\Discord;

use App\Discord\Commands\Dota2\AddRoleCommand;
use App\Discord\Commands\Dota2\CreatePartyCommand;
use App\Discord\Commands\Dota2\DeleteDotaCommand;
use App\Discord\Commands\Dota2\JoinPartyCommand;
use App\Discord\Commands\Dota2\LeavePartyCommand;
use App\Discord\Commands\Dota2\PartyCommand;
use App\Discord\Commands\Dota2\RefreshRolesCommand;
use App\Discord\Commands\Dota2\RemoveRoleCommand;
use App\Discord\Commands\Dota2\RollPartyCommand;
use App\Discord\Commands\Dota2\SetRolesCommand;
use App\Discord\Commands\StartCommand;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use React\EventLoop\Factory;
use Illuminate\Support\Str;

class Bot extends Discord
{
    const COMMANDS = [
        StartCommand::class,
        CreatePartyCommand::class,
        JoinPartyCommand::class,
        LeavePartyCommand::class,
        PartyCommand::class,
        RollPartyCommand::class,
        SetRolesCommand::class,
        AddRoleCommand::class,
        RemoveRoleCommand::class,
        RefreshRolesCommand::class,
        DeleteDotaCommand::class
    ];

    public function __construct()
    {
        parent::__construct([
            'token' => config('discord.app.token'),
            'loop' => Factory::create()
        ]);
    }

    public function initCommands(): self
    {
        $this->on('message', function (Message $message, Discord $discord) {
            if (!Str::startsWith($message->content, config('discord.command.prefix'))) {
                return;
            }

            foreach (static::COMMANDS as $commandClass) {
                $command = new $commandClass;

                $args = explode(' ', $message->content);

                if ($command->getName(true) != array_shift($args)) {
                    continue;
                }

                $command->execute($message, $discord, $args);
            }
        });

        return $this;
    }
}
