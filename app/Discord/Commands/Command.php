<?php

namespace App\Discord\Commands;

use Discord\Discord;
use Discord\Parts\Channel\Message;

abstract class Command
{
    protected string $name;

    public function getName(bool $withPrefix = false): string
    {
        return ($withPrefix ? config('discord.command.prefix') : '') . $this->name;
    }

    abstract public function execute(Message $message, Discord $discord, array $args): void;
}
