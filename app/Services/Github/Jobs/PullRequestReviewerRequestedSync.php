<?php

namespace App\Services\Github\Jobs;

use App\Models\Collaborator;
use App\Models\PullRequest;
use App\Services\Github\PullRequestReviewersRequestedService;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\SkipIfBatchCancelled;

class PullRequestReviewerRequestedSync implements ShouldQueue
{
    use Queueable;
    use Batchable;

    /**
     * Create a new job instance.
     */
    public function __construct(public PullRequest $pullRequest, public array $collaborator)
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

        $collaborator = Collaborator::query()->updateOrCreate(
            ['login' => $this->collaborator['login']],
            ['api_id' => $this->collaborator['id']]
        );


        $collaborator->pullRequests()->attach($this->pullRequest);
        
    }
}
