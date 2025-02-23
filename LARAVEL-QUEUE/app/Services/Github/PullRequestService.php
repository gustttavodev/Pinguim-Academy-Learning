<?php

namespace App\Services\Github;

use Illuminate\Support\Facades\Http;

class PullRequestService
{
    public function getPullRequests(string $repositoryFullName, int $page): array
    {
        $queryString = http_build_query([
            'page' => $page,
            'state' => 'all'
        ],arg_separator: '&', encoding_type: PHP_QUERY_RFC3986);

        $url = 'repos/'.$repositoryFullName.'/pulls?'.$queryString;
        dump($url . '   hehehe');
        $response  = (new Client)->http()->get($url);

        return $response ->json();
    }

    public function getPullRequest(string $repositoryFullName, int $number): array
    {
        $url = 'repos/'.$repositoryFullName.'/pulls/'.$number;

        $pullRequestsResponse = (new Client)->http()->get($url);

        return $pullRequestsResponse->json();
    }
}
