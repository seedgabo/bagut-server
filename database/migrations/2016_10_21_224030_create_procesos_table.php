<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcesosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procesos', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('cliente_id');
            $table->integer('user_id');
            $table->integer('ticket_id');
            $table->string('radicado');
            $table->text('juzgado_instancia_1')->nullable();
            $table->text('juzgado_instancia_2')->nullable();
            $table->text('demandado')->nullable();
            $table->text('demandante')->nullable();
            $table->text('descripcion')->nullble();
            $table->text('notas')->nullable();
            $table->string('estado')->nullable();
            $table->datetime("fecha_proceso")->nullable();
            $table->datetime("fecha_cierre")->nullable();
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
        Schema::drop('procesos');
    }
}
