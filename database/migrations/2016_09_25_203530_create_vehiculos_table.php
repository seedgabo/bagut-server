<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehiculos', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('placa')->unique()->nullable();
            $table->string('marca');
            $table->string('modelo');
            $table->string('linea');
            $table->string('tipo');
            $table->string('color');
            $table->string('motor');
            $table->string('chasis');
            $table->string('cilindraje');
            $table->string('direccion');
            $table->string('uso');
            $table->string('licencia_transito');
            $table->string('dueño'); 
            $table->date('fecha_ingreso');
            $table->boolean('activo')->default(1);
            $table->text('nota')->nullable();
            $table->string('archivo_id')->nullable();
            $table->string('foto')->nullable();
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
        Schema::drop('vehiculos');
    }
}
