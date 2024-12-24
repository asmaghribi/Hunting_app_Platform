<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlertsTable extends Migration
{
    public function up()
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->string('message');
            $table->json('position');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('alerts');
    }
}
