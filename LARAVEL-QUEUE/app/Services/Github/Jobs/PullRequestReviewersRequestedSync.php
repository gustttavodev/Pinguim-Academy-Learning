<?php

namespace App\Services\Github\Jobs;

use App\Models\Collaborator;
use App\Models\PullRequest;
use App\Services\Github\PullRequestReviewersRequestedService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PullRequestReviewersRequestedSync implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $repositoryFullName, public int $pullRequestNumber)
    {
        //
    }

    public function handle(): void
    {
        $response = (new PullRequestReviewersRequestedService())->getAll($this->repositoryFullName, $this->pullRequestNumber);



        $collaborators = $response['users'];

        foreach ($collaborators as $collaborator) {
            Collaborator::query()->updateOrCreate(
                ['login' => $collaborator['login']],
                ['api_id' => $collaborator['id']]
            );

            $pr = PullRequest::query()->where('api_number', $this->pullRequestNumber)->first();

            $collaborator->pullRequests()->attach($pr);
        }
    }
}
