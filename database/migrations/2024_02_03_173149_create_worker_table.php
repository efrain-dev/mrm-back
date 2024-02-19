<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('worker', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('last_name');
            $table->decimal('salary');
            $table->date('date_in');
            $table->date('date_out')->nullable();
            $table->date('birthdate');
            $table->string('social_number')->nullable();
            $table->string('rate_night');
            $table->string('email')->unique();
            $table->string('address')->nullable();
            $table->string('contact')->nullable();
            $table->string('cel')->nullable();
            $table->boolean('active')->default(true);

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
