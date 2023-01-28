<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Nette\Utils\Random;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {



        Schema::create('codigos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained();
            $table->string('c1');
            $table->string('c2');
            $table->string('c3');
            $table->string('c4');
            $table->string('c5');
            $table->string('c6');
            $table->string('c7');
            $table->string('c8');
            $table->string('c9');
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
        Schema::dropIfExists('codigos');
    }
};
