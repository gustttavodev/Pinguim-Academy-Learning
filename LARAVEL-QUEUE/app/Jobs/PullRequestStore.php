<?php

namespace App\Jobs;

use App\Models\PullRequest;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PullRequestStore implements ShouldQueue
{
    use Queueable;


    public function __construct(public array $pullRequest)
    {
        
    }

    public function handle(): void
    {
        PullRequest::query()->create([
            'api_id' => $this->pullRequest['id'],
            'api_number' => $this->pullRequest['number'],
            'state' => $this->pullRequest['state'],
            'title' => $this->pullRequest['title'],
            'api_created_at' => Carbon::parse($this->pullRequest['created_at'])->format('Y-m-d H:i:s'),
            'api_updated_at' => Carbon::parse($this->pullRequest['updated_at'])->format('Y-m-d H:i:s'),
            'api_closed_at' => Carbon::parse($this->pullRequest['closed_at'])->format('Y-m-d H:i:s'),
            'api_merged_at' => Carbon::parse($this->pullRequest['merged_at'])->format('Y-m-d H:i:s'),
        ]);
    }
}
