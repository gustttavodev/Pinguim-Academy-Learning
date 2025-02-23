<?php

namespace App\Services\Github\Jobs;

use App\Models\PullRequest;
use App\Services\Github\PullRequestService;
use Carbon\Carbon;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class PullRequestSync implements ShouldQueue
{
    use Queueable;
    use Batchable;

    public function __construct(public string $repositoryFulName, public int $number)
    {
        
    }

    public function handle(): void
    {
        $pullRequest = (new PullRequestService())->getPullRequest($this->repositoryFulName, $this->number);

        $pr = PullRequest::query()->create([
            'api_id' => $pullRequest['id'],
            'api_number' => $pullRequest['number'],
            'state' => $pullRequest['state'],
            'title' => $pullRequest['title'],
            'commits_total' => $pullRequest['commits'],
            'api_created_at' => Carbon::parse($pullRequest['created_at'])->format('Y-m-d H:i:s'),
            'api_updated_at' => Carbon::parse($pullRequest['updated_at'])->format('Y-m-d H:i:s'),
            'api_closed_at' => Carbon::parse($pullRequest['closed_at'])->format('Y-m-d H:i:s'),
            'api_merged_at' => Carbon::parse($pullRequest['merged_at'])->format('Y-m-d H:i:s'),
        ]);
        
        $this->batch()->add([
            new PullRequestReviewersRequestedSync($pr,$this->repositoryFulName)
        ]);
    }
}
