<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluaciones', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->text('opciones')->nullable();
            $table->integer('tipo'); // 1 : proveedores, 2 vehiculos, 3 conductores, 4 personal
            $table->boolean('activo')->default(1);
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
        Schema::drop('evaluaciones');
    }
}
