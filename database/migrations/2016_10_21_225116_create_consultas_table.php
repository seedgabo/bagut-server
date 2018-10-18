<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsultasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultas', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('cliente_id');
            $table->integer('user_id');
            $table->integer('ticket_id');
            $table->datetime('fecha_consulta')->nullable();
            $table->text('consulta')->nullable();
            $table->text('detalles')->nullable();
            $table->text('respuesta')->nullable();
            $table->text('descripcion')->nullble();
            $table->text('notas')->nullable();
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
        Schema::drop('consultas');
    }
}
