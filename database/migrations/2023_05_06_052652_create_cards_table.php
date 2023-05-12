<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('transaction_id');
            $table->string("card_type")->default("visa");
            $table->string("currency")->default("USD");
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string("card_number");
            $table->integer("cvc");
            $table->string("expire_month");
            $table->string("expire_year");
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->unsignedBigInteger('card_request_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
           // $table->foreign('transaction_id')->references('id')->on('transactons');
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
        Schema::dropIfExists('cards');
    }
};
