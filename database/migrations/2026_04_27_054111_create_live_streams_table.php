<?php

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
        Schema::create('live_streams', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Live Stream');
            $table->text('youtube_url')->nullable();
            $table->string('youtube_video_id')->nullable();
            $table->string('youtube_channel_id')->nullable();
            $table->boolean('is_live')->default(false);
            $table->boolean('auto_detect')->default(false);
            $table->text('description')->nullable();
            $table->timestamp('stream_started_at')->nullable();
            $table->timestamp('stream_ended_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_streams');
    }
};
