<?php

namespace App\Services\Github\Console\Comands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

class PullRequestSync extends Command
{

    protected $signature = 'github:pull-requests-sync {repositoryFullName}';

    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Bus::batch([
            new \App\Services\Github\Jobs\PullRequestsSync($this->argument('repositoryFullName')),
        ])

        ->then(function () {
            info('All done');
        })
        ->dispatch();

    }
}
