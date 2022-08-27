<?php

namespace App\Discord\Components;

use App\Models\DiscordMember;
use App\Models\DotaParty;
use Discord\Builders\Components\Option;
use Discord\Builders\Components\SelectMenu;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Interactions\Interaction;

class PartyControlMenu extends SelectMenu
{
    private DotaParty $party;
    private DiscordMember $member;
    private string $messageContent;

    public function __construct(?string $custom_id)
    {
        parent::__construct($custom_id);
    }

    public function init(DotaParty $party, DiscordMember $member): void
    {
        $this->party = $party;
        $this->member = $member;
        Option::new();
        $this->addOption(Option::new('Left party', 0));
        $this->addOption(Option::new('Join party', 1));
    }

    public function setMessageContent(string $content): void
    {
        $this->messageContent = $content;
    }

    public function listener(Interaction $interaction): void
    {
        $interaction->get
        $message = MessageBuilder::new()
            ->setContent($this->messageContent);

        $interaction->respondWithMessage($message);
    }

    public function setListener(?callable $callback, Discord $discord, bool $oneOff = false): SelectMenu
    {
        if (!$callback) {
            $callback = $this->{'listener'};
        }

        return parent::setListener($callback, $discord, $oneOff);
    }
}
