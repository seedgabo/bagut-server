<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcesosMasivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procesos_masivos', function(Blueprint $table) {
            $table->increments('id');
            $table->string('titulo')->nullable();
            $table->string("referencia")->nullable();
            $table->integer('user_id')->nullable();
            $table->date('inicio')->nullable();
            $table->date('cierre')->nullable();
            $table->string("estado")->default("abierto");
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::drop('procesos_masivos');
    }
}
