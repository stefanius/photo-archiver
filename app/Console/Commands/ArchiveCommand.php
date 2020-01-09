<?php

namespace App\Console\Commands;

use App\Jobs\ArchiveJob;
use Illuminate\Console\Command;

class ArchiveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archive {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive photo\'s in subfolders';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        dispatch(new ArchiveJob($this->argument('path')));
    }
}