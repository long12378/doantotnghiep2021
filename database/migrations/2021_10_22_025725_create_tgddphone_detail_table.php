<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTgddphoneDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tgddphone_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('phone_id');
            $table->integer('id_hang');
            $table->string('screen')->nullable();
            $table->string('OS')->nullable();
            $table->string('chip')->nullable();
            $table->string('RAM')->nullable();
            $table->string('ROM')->nullable();
            $table->string('sim')->nullable();
            $table->string('back_camera')->nullable();
            $table->string('front_camera')->nullable();
            $table->string('pin')->nullable();
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
        Schema::dropIfExists('tgddphone_detail');
    }
}
