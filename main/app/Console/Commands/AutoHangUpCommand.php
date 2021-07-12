<?php

namespace App\Console\Commands;

use Nesk\Puphpeteer\Puppeteer;
use Illuminate\Console\Command;

class AutoHangUpCommand extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hang Up';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hang:up';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $puppeteer = new Puppeteer;
        $browser   = $puppeteer->launch();

        $page = $browser->newPage();
        $page->goto('https://example.com');
        $page->screenshot(['path' => 'example.png']);

        $browser->close();
    }
}
