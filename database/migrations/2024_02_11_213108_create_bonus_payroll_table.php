<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonusPayrollTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonus_payroll', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_detail_bonus')->unsigned();
            $table->foreign('id_detail_bonus','fk_detail_bonuspay')->references('id')->on('detail_bonus');
            $table->integer('id_payroll')->unsigned();
            $table->foreign('id_payroll','fk_id_payroll')->references('id')->on('payroll');
            $table->integer('id_worker')->unsigned();
            $table->foreign('id_worker','fk_worker_bonus')->references('id')->on('worker');
        });









    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bonus_payroll');
    }
}
