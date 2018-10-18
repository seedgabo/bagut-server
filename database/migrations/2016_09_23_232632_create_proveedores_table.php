<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('documento')->unique();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('bien_o_servicio');
            $table->string('direccion'); 
            $table->string('ubicacion'); 
            $table->string('tipo'); 
            $table->string('telefono')->nullable(); 
            $table->string('email')->nullable();
            $table->date('fecha_ingreso');
            $table->boolean('activo')->default(1);
            $table->text('nota')->nullable();
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
        Schema::drop('proveedores');
    }
}
