<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PullRequestSync extends Command
{

    protected $signature = 'app:pull-request-sync {repositoryFullName}';

    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \App\Jobs\PullRequestsSync::dispatch(
            $this->argument('repositoryFullName')
        );
    }
}
