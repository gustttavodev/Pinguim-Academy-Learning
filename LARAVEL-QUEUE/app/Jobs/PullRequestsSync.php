<?php

namespace App\Jobs;

use App\Models\PullRequest;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class PullRequestsSync implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $repositoryFulName, public ?int $page = 1)
    {

    }

    public function handle(): void
    {
        $pullRequestsResponse = Http::withToken(config('services.github_access_token'))
            ->get('https://api.github.com/repos/'.$this->repositoryFulName.'/pulls?state=all&page='.$this->page);

        $pullRequests = $pullRequestsResponse->json();

        if(empty($pullRequests)){
            return;
        }

        foreach ($pullRequests as $request) {
            
            PullRequest::query()->create([
                'api_id' => $request['id'],
                'api_number' => $request['number'],
                'state' => $request['state'],
                'title' => $request['title'],
                'api_created_at' => Carbon::parse($request['created_at'])->format('Y-m-d H:i:s'),
                'api_updated_at' => Carbon::parse($request['updated_at'])->format('Y-m-d H:i:s'),
                'api_closed_at' => Carbon::parse($request['closed_at'])->format('Y-m-d H:i:s'),
                'api_merged_at' => Carbon::parse($request['merged_at'])->format('Y-m-d H:i:s'),
            ]);
    
        }
        $nextPage = $this->page + 1;
        PullRequestsSync::dispatch($this->repositoryFulName, $nextPage);
    }
}
