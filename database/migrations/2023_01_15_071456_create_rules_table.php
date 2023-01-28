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
        Schema::create('rules', function (Blueprint $table) {
            $table->id();
            $table->string('recurso1')->default('aire');
            $table->string('recurso2')->default('tierra');
            $table->string('recurso3')->default('fuego');
            $table->string('recurso4')->default('agua');
            $table->integer('minerosInit')->default(10);
            $table->integer('minerosUpgrade')->default(5);
            $table->integer('soldierRoboChance')->default(50);
            $table->integer('soldierRoboUpgrade')->default(10);
            $table->integer('soldierMaxAtt')->default(5);
            $table->integer('soldierMaxDeff')->default(5);
            $table->timestamps();
        });
    }

    ### COSAS A EXPLICAR AL JUGADOR ###
    // Un soldado atakante ganador tendrá un [soldierRoboChance] probabilidades base de robar. 
    // Más un 10% por cada punto de atake sobrante despues de la batalla

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rules');
    }
};
