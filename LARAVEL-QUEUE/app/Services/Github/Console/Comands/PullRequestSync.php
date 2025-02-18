<?php

namespace App\Services\Github\Console\Comands;

use Illuminate\Console\Command;

class PullRequestSync extends Command
{

    protected $signature = 'github:pull-request-sync {repositoryFullName}';

    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \App\Services\Github\Jobs\PullRequestsSync::dispatch(
            $this->argument('repositoryFullName')
        );
    }
}
