<?php

namespace App\Services\Github\Console\Comands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
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
        $repositoryName = $this->argument('repositoryFullName');
        Bus::batch([
            new \App\Services\Github\Jobs\PullRequestsSync($this->argument('repositoryFullName')),
            new \App\Services\Github\Jobs\FailedJob()
        ])

        ->then(function () {
            info('All done');
        })
        ->name('Github repository sync [' . $repositoryName . ']')
        ->allowFailures()
        ->dispatch();

    }
}
