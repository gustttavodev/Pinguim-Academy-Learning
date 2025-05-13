<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collaborator extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_id',
        'login',
    ];
    public function pullRequests()
    {
        return $this->belongsToMany(PullRequest::class)->withTimestamps();
    }
}
