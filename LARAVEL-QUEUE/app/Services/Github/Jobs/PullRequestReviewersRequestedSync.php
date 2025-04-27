<?php

namespace App\Services\Github\Jobs;

use App\Models\Collaborator;
use App\Models\PullRequest;
use App\Services\Github\PullRequestReviewersRequestedService;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\SkipIfBatchCancelled;

class PullRequestReviewersRequestedSync implements ShouldQueue
{
    use Queueable;
    use Batchable;

    public function __construct(public PullRequest $pullRequest, public string $repositoryFullName)
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
        $response = (new PullRequestReviewersRequestedService())->getAll($this->repositoryFullName, $this->pullRequest->api_number);
        $collaborators = $response['users'];
        
        $jobs = [];

        foreach ($collaborators as $collaborator) {
            $jobs[] = new PullRequestReviewerRequestedSync($this->pullRequest, $collaborator);

        }

        $this->batch()->add($jobs);
    }
}
