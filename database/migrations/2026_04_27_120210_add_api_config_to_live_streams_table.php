<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('live_streams', function (Blueprint $table) {
            $table->string('youtube_api_key')->nullable()->after('youtube_channel_id');
            $table->unsignedTinyInteger('check_interval_minutes')->default(5)->after('youtube_api_key');
        });
    }

    public function down(): void
    {
        Schema::table('live_streams', function (Blueprint $table) {
            $table->dropColumn(['youtube_api_key', 'check_interval_minutes']);
        });
    }
};
