<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    
    App\Jobs\PullRequestsSync::dispatch();

});
