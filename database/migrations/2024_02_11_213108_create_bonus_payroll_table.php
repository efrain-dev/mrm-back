<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGlobalPayrollTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('global_payroll', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_detail_global')->unsigned();
            $table->foreign('id_detail_global','fk_detail_global')->references('id')->on('detail_global');
            $table->integer('id_payroll')->unsigned();
            $table->foreign('id_payroll','fk_id_payroll')->references('id')->on('payroll');
        });









    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('global_payroll');
    }
}
