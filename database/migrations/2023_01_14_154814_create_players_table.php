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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->integer('player');
            $table->integer('puntos_accion')->default(2);
            $table->integer('recurso1')->default(0);
            $table->integer('recurso2')->default(0);
            $table->integer('recurso3')->default(0);
            $table->integer('recurso4')->default(0);
            $table->boolean('c1')->default(false);
            $table->boolean('c2')->default(false);
            $table->boolean('c3')->default(false);
            $table->boolean('c4')->default(false);
            $table->boolean('c5')->default(false);
            $table->boolean('c6')->default(false);
            $table->boolean('c7')->default(false);
            $table->boolean('c8')->default(false);
            $table->boolean('c9')->default(false);
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
        Schema::dropIfExists('players');
    }
};
