<?php

namespace App\Services\Github\Jobs;

use App\Models\PullRequest;
use App\Services\Github\PullRequestService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;

class PullRequestsSync implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $repositoryFulName, public ?int $page = 1)
    {

    }

    public function handle(): void
    {
        $pullRequests = (new PullRequestService())->getPullRequests($this->repositoryFulName, $this->page);

        if(empty($pullRequests)){
            return;
        }

        foreach ($pullRequests as $request) {
            \App\Services\Github\Jobs\PullRequestSync::dispatch($this->repositoryFulName, $request['number']);
    
        }
        $nextPage = $this->page + 1;
        PullRequestsSync::dispatch($this->repositoryFulName, $nextPage);
    }
}
