<?php

namespace App\Services\Github;

use Illuminate\Support\Facades\Http;

class PullRequestReviewersRequestedService
{
    public function getAll(string $repositoryFullName, int $pullRequestNumber): array
    {
        $url = 'https://api.github.com/repos/'.$repositoryFullName.'/pulls/'.$pullRequestNumber .'/requested_reviewers';
        dump($url);
        $pullRequestsResponse = Http::withToken(config('services.github_access_token'))
            ->get($url);

        return $pullRequestsResponse->json();
    }

    public function getPullRequest(string $repositoryFullName, int $number): array
    {
        $url = 'https://api.github.com/repos/'.$repositoryFullName.'/pulls/'.$number;
        dump($url);
        $pullRequestResponse = Http::withToken(config('services.github_access_token'))
            ->get($url);

        return $pullRequestResponse->json();
    }
}
