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
        Schema::create('mineros', function (Blueprint $table) {
            $table->id();            
            $table->foreignId('player_id')->constrained();
            $table->integer('lvl_minero1')->default(1);
            $table->integer('lvl_minero2')->default(1);
            $table->integer('lvl_minero3')->default(1);
            $table->integer('lvl_minero4')->default(1);
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
        Schema::dropIfExists('mineros');
    }
};
