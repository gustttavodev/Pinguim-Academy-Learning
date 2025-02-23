<?php

namespace App\Services\Github\Jobs;

use App\Models\Collaborator;
use App\Models\PullRequest;
use App\Services\Github\PullRequestReviewersRequestedService;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PullRequestReviewersRequestedSync implements ShouldQueue
{
    use Queueable;
    use Batchable;

    public function __construct(public PullRequest $pullRequest, public string $repositoryFullName)
    {
        
    }

    public function handle(): void
    {
        $response = (new PullRequestReviewersRequestedService())->getAll($this->repositoryFullName, $this->pullRequest->api_number);
        $collaborators = $response['users'];

        foreach ($collaborators as $collaborator) {
            $this->batch()->add([
                new PullRequestReviewerRequestedSync($this->pullRequest, $collaborator)
            ]);
        }
    }
}
