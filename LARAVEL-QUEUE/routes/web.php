<?php

use App\Models\PullRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    
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

});
