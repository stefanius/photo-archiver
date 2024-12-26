<?php

namespace App\Console\Commands;

use App\Jobs\ArchiveJob;
use App\Strategies\Strategy;
use Illuminate\Console\Command;
use App\Exceptions\UnknownStrategyException;

class ArchiveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archive {path}
                           {--strategy= : The key of the strategy you want to use. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive photo\'s in subfolders';

    /**
     * @return void
     */
    public function handle()
    {
        $strategy = $this->loadStrategy();

        if (!$strategy instanceof Strategy) {
            throw new UnknownStrategyException;
        }

        dispatch(new ArchiveJob($this->argument('path'), $strategy));
    }

    /**
     * Load strategy based on the given key.
     */
    protected function loadStrategy(): ?\App\Strategies\Strategy
    {
        $key = $this->option('strategy') ?: config('archiver.default-strategy');

        return config("archiver.strategies.{$key}");
    }
}
