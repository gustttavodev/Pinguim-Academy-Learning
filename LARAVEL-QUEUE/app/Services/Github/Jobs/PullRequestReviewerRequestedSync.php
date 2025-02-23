<?php

namespace App\Services\Github\Jobs;

use App\Models\Collaborator;
use App\Models\PullRequest;
use App\Services\Github\PullRequestReviewersRequestedService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PullRequestReviewerRequestedSync implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public PullRequest $pulllRequest, public array $collaborator)
    {
        
    }

    public function handle(): void
    {

        $collaborator = Collaborator::query()->updateOrCreate(
            ['login' => $this->collaborator['login']],
            ['api_id' => $this->collaborator['id']]
        );


        $collaborator->pullRequests()->attach($this->pulllRequest);
        
    }
}
