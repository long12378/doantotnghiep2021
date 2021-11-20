<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTgddlaptopDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tgddlaptop_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('laptop_id');
            $table->integer('id_hang');
            $table->string('cpu')->nullable();
            $table->string('ram')->nullable();
            $table->string('disk')->nullable();
            $table->string('screen')->nullable();
            $table->string('screencard')->nullable();
            $table->string('gate')->nullable();
            $table->string('special')->nullable();
            $table->string('os')->nullable();
            $table->string('design')->nullable();
            $table->string('size')->nullable();
            $table->string('year')->nullable();
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
        Schema::dropIfExists('tgddlaptop_detail');
    }
}
