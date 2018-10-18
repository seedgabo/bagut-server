<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluacionesConductoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluaciones_conductores', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('conductor_id');
            $table->integer('evaluacion_id');
            $table->integer('puntaje')->default(100);
            $table->string('estado')->default('aprobado'); //aprobado, reprobado, prorrogado
            $table->string('accion')->nullable();
            $table->string('nota')->nullable(); 
            $table->datetime('fecha_evaluacion');
            $table->datetime('fecha_proxima');

            $table->datetime('alerta_preventiva');
            $table->datetime('alerta_vencido');
            $table->string('archivo_id');
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
        Schema::drop('evaluaciones_conductores');
    }
}
