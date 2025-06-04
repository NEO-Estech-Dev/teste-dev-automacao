<?php

use App\Models\Job;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job', function (Blueprint $table) { 
            $table->id();
            $table->foreignId('user_id')->references('id')->on('user');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', allowed: Job::ALL_TYPES)
                ->default(Job::TYPE_CLT);
            $table->boolean('paused')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('job_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('user');
            $table->foreignId('job_id')->references('id')->on('job');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_user');
        Schema::dropIfExists('job');
    }
};
