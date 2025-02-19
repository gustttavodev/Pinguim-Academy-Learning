<?php

namespace App\Services\Github;

use Illuminate\Support\Facades\Http;

class PullRequestReviewersRequestedService
{
    public function getAll(string $repositoryFullName, int $pullRequestNumber): array
    {
        $url = 'https://api.github.com/repos/'.$repositoryFullName.'/pulls/'.$pullRequestNumber .'/requested_reviewers';
        dump($url);
        $pullRequestsResponse = (new Client())->http()->get($url);

        return $pullRequestsResponse->json();
    }

}
