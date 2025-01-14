<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('user_cities', function (Blueprint $table) {
            if (!Schema::hasColumn('user_cities', 'send_forecast')) {
                $table->boolean('send_forecast')->default(false);
            }
            if (!Schema::hasColumn('user_cities', 'send_forecast_email_scheduled')) {
                $table->timestamp('send_forecast_email_scheduled')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('user_cities', function (Blueprint $table) {
            if (Schema::hasColumn('user_cities', 'send_forecast')) {
                $table->dropColumn('send_forecast');
            }
            if (Schema::hasColumn('user_cities', 'send_forecast_email_scheduled')) {
                $table->dropColumn('send_forecast_email_scheduled');
            }
        });
    }
};
