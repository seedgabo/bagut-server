<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCasosMedicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('casos_medicos', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('paciente_id');
            $table->text('origen_del_caso')->nullable();
            $table->text('descripcion')->nullable();
            $table->integer('ticket_id')->nullable();
            $table->datetime('apertura')->nullable();
            $table->datetime('cierre')->nullable();
            $table->integer('medico_id');
            $table->integer('puesto_id');
            $table->string('estado')->default('abierto');
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
        Schema::drop('casos_medicos');
    }
}
