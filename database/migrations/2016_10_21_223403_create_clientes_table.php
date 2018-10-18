<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function(Blueprint $table) {
            $table->increments('id');
            $table->integer("user_id")->unsigned()->nullable();
            $table->string('cedula')->unique()->nullable();
            $table->string('nit')->unique()->nullable();
            $table->string('nombres');
            $table->string('apellidos')->nullable();
            $table->string('email')->nullable();
            $table->date("nacimiento")->nullable();
            $table->char("sexo")->default('M');
            $table->string("estadoCivil")->default('soltero');
            $table->string('telefono')->nullable();
            $table->string('foto')->nullable();
            $table->text('nota')->nullable(); 
            $table->text('direccion')->nullable();
            $table->string('ingreso')->nullable(); 
            $table->string('egreso')->nullable(); 
            $table->text('condiciones')->nullable();
            $table->integer('eps_id')->nullable(); 
            $table->integer('arl_id')->nullable(); 
            $table->integer('fondosDePensiones_id')->nullable();
            $table->decimal('ibc',20,3)->default(0);     
                  
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
        Schema::drop('clientes');
    }
}
