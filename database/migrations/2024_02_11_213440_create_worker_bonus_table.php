<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkerBonusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worker_bonus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_worker')->unsigned();
            $table->foreign('id_worker','fk_worker_bonus')->references('id')->on('worker');
            $table->integer('id_bonus')->unsigned();
            $table->foreign('id_bonus','fk_bonus_worker')->references('id')->on('bonus');
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
        Schema::dropIfExists('worker_bonus');
    }
}
