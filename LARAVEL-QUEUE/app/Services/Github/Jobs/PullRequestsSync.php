<?php

namespace App\Services\Github\Jobs;

use App\Services\Github\PullRequestService;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PullRequestsSync implements ShouldQueue
{
    use Queueable;
    use Batchable;

    public function __construct(public string $repositoryFulName, public ?int $page = 1)
    {

    }

    public function handle(): void
    {
        $pullRequests = (new PullRequestService())->getPullRequests($this->repositoryFulName, $this->page);

        if(empty($pullRequests)){
            return;
        }

        foreach ($pullRequests as $pullRequest) {
            $this->batch()->add([
                new \App\Services\Github\Jobs\PullRequestSync($this->repositoryFulName, $pullRequest['number'])
            ]);
            
    
        }
        $nextPage = $this->page + 1;

        PullRequestsSync::dispatch($this->repositoryFulName, $nextPage);
    }
}
