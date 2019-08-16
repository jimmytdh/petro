<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonitoringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monitoring', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('participant_id')->unsigned();
            $table->foreign('participant_id')->references('id')->on('participants');
            $table->integer('training_id')->unsigned();
            $table->foreign('training_id')->references('id')->on('trainings');
            $table->integer('self');
            $table->integer('supervisor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monitoring');
    }
}
