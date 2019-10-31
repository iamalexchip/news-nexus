<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Feed\Fetch;
use App\Models\Source;

class FetchSource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:source {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch feed items from a source';

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

        $source = Source::find($this->argument('id'));

        if (!$source)
        {
            return $this->error('source not found');
        }

        $this->info("Fetching...");

        $start = microtime(true);

        $items = Fetch::source($source->id);// fetch items

        $end = microtime(true);

        $timeTaken = round($end - $start, 2);
 
        $this->line("Collected {$items} items from source {$source->id} ({$source->url}) in {$timeTaken} seconds.'");
    }
}
