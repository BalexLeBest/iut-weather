<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCitiesTable extends Migration
{
    public function up()
    {
        Schema::create('user_cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('city');
            $table->boolean('favorite')->default(false);
            $table->boolean('send_forecast')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_cities');
    }
}
