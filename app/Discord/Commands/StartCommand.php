<?php

namespace App\Discord\Commands;

use Discord\Builders\Components\Option;
use Discord\Builders\Components\SelectMenu;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\Parts\Interactions\Interaction;
use Illuminate\Support\Facades\Storage;

class StartCommand extends Command
{
    protected string $name = 'start';

    public function execute(Message $message, Discord $discord, array $args): void
    {
        $builder = new MessageBuilder();
        $builder->setContent('Шо хочеш?');

        $menuComponent = SelectMenu::new()
            ->addOption(Option::new('Чавить', 0))
            ->setListener(function (Interaction $interaction) use ($message): void {
                $message->reply(MessageBuilder::new()
                    ->setContent('Ну Ілюша...')
                    ->addFile(Storage::path('discord/chavilo.png'))
                );
            }, $discord);

        $builder->addComponent($menuComponent);

        $message->channel->sendMessage($builder);
    }
}
