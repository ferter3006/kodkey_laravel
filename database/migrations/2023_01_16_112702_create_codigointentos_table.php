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
        Schema::create('codigo_intentos', function (Blueprint $table) {
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
            $table->integer('result_count');
            $table->boolean('result_c1');
            $table->boolean('result_c2');
            $table->boolean('result_c3');
            $table->boolean('result_c4');
            $table->boolean('result_c5');
            $table->boolean('result_c6');
            $table->boolean('result_c7');
            $table->boolean('result_c8');
            $table->boolean('result_c9');
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
        Schema::dropIfExists('codigointentos');
    }
};
