<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailBonusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_bonus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_bonus')->unsigned();
            $table->foreign('id_bonus','fk_detail_bonus')->references('id')->on('bonus');
            $table->string('calc')->comment('Tipo 1 es monto fijo 2 es porcentual')->default('1');
            $table->decimal('amount');
            $table->date('date');
            $table->boolean('general')->default(true);
            $table->boolean('active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_bonus');
    }
}
