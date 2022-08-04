<?php

namespace App\Console\Commands;

use App\Discord\Bot;
use Illuminate\Console\Command;

class RunDiscordBotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'discord:bot:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start discord bot';

    protected Bot $bot;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->bot = new Bot();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->bot->initCommands()->run();

        return 0;
    }
}
