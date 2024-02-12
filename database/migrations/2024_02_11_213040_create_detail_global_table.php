<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailGlobalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_global', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_global')->unsigned();
            $table->foreign('id_global','fk_detail_global')->references('id')->on('global');
            $table->string('calc')->comment('Tipo 1 es monto fino 2 es porcentual')->default('1');
            $table->decimal('amount');
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('detail_global');
    }
}
