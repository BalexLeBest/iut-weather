<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('user_cities', function (Blueprint $table) {
            $table->boolean('send_forecast')->default(false);
            $table->boolean('favorite')->default(false);
            $table->timestamp('send_forecast_email_scheduled')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('user_cities', function (Blueprint $table) {
            $table->dropColumn(['send_forecast', 'send_forecast_email_scheduled']);
        });
    }
};
