<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('regular');
            $table->integer('extra');
            $table->integer('night');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->integer('id_payroll')->unsigned();
            $table->foreign('id_payroll','fk_report_payroll')->references('id')->on('payroll');
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
        Schema::dropIfExists('report');
    }
}