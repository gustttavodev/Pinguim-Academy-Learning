<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collabortor_pull_request', function (Blueprint $table) {
            $table->id();

            $table->foreignId('collaborator_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pull_request_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collabortor_pull_request');
    }
};
