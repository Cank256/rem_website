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
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_session_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('url', 500);
            $table->string('path', 255);
            $table->string('method', 10)->default('GET');
            $table->string('referrer', 500)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('device_type', 50)->nullable(); // mobile, tablet, desktop
            $table->string('browser', 100)->nullable();
            $table->string('platform', 100)->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('country', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->integer('duration')->nullable(); // time spent on page in seconds
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
            
            $table->index(['path', 'created_at']);
            $table->index('created_at');
            $table->index('visitor_session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
