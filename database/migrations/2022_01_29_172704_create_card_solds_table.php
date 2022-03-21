<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardSoldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_solds', function (Blueprint $table) {
            $table->id('id');
            //$table->string('name');
            $table->integer('amount');
            $table->float('price');
            $table->unsignedBigInteger('card_asociate');
            $table->foreign('card_asociate')->references('id')->on('card_colllection');
            $table->unsignedBigInteger('user_asociate');
            $table->foreign('user_asociate')->references('id')->on('users');
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
        Schema::dropIfExists('card_solds');
    }
}
