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

    public function handle(): void
    {
        $pullRequests = Http::get('https://api.github.com/repos/laravel/laravel/pulls?state=all&page=1');

        foreach ($pullRequests->json() as $request) {
            
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
    }
}
