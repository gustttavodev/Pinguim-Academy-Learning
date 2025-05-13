<?php

namespace App\Services\Github\Jobs;

use App\Services\Github\PullRequestService;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\SkipIfBatchCancelled;

class PullRequestsSync implements ShouldQueue
{
    use Queueable;
    use Batchable;

    public function __construct(public string $repositoryFulName, public ?int $page = 1)
    {

    }

    public function middleware()
    {
        return [
            new SkipIfBatchCancelled(),
        ];
    }


    public function handle(): void
    {
        $pullRequests = (new PullRequestService())->getPullRequests($this->repositoryFulName, $this->page);

        if(empty($pullRequests)){
            return;
        }

        $jobs = [];

        foreach ($pullRequests as $pullRequest) {
            
            $jobs[] = new PullRequestSync($this->repositoryFulName, $pullRequest['number']);

        }
        $this->batch()->add($jobs);

        $nextPage = $this->page + 1;
        $this->batch()->add(jobs: [
            new PullRequestsSync($this->repositoryFulName, $nextPage)
        ]);
        
    }
}
