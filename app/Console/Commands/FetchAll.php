<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Feed\Fetch;
use App\Models\Source;

class FetchAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:all {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch feed items from all sources';

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
    	if ($this->option('force'))
    	{
        	$this->info("Fetching from all sources...");
        	$sources = Source::all();
        } else {
        	$this->info("Fetching from all active sources...");
        	$sources = Source::active()->get();
        }

        $start = microtime(true);

        $items = 0;

        foreach ($sources as $source) {
            $sourceStart = microtime(true);
			$items += Fetch::source($source->id);// fetch items
            $sourceEnd = microtime(true);
            $sourceTimeTaken = round($sourceEnd - $sourceStart, 2);
            $this->info("Collected {$items} items from source {$source->id} ({$source->url}) in {$sourceTimeTaken} seconds.'");
		}

        $end = microtime(true);

        $timeTaken = round($end - $start, 2);
        $sourceCount = $sources->count();
 
        $this->line("Collected {$items} items from {$sourceCount} sources in {$timeTaken} seconds.'");
    }
}
