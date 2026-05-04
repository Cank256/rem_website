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
        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_session_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('page_view_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('event_name', 100); // e.g., 'video_play', 'form_submit', 'download'
            $table->string('event_category', 100)->nullable(); // e.g., 'engagement', 'conversion'
            $table->text('event_data')->nullable(); // JSON data
            $table->string('url', 500)->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
            
            $table->index(['event_name', 'created_at']);
            $table->index('created_at');
            $table->index('visitor_session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_events');
    }
};
