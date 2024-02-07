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
            $table->id();
            $table->string('name');
            $table->string('last_name');
            $table->decimal('salary');
            $table->date('date_in');
            $table->date('date_out')->nullable();
            $table->date('birthdate');
            $table->string('social_number');
            $table->string('rate_night');
            $table->string('email')->unique();
            $table->string('address');
            $table->string('contact');
            $table->boolean('active')->default(true);

            $table->timestamps();
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
