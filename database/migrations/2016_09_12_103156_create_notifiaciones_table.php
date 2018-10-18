<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotifiacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notificaciones', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('titulo');
            $table->string('mensaje');
            $table->boolean('leido')->default(0);
            $table->boolean('admin')->default(0);
            $table->integer('user_id');
            $table->integer('ticket_id')->nullable();
            $table->integer('paciente_id')->nullable();
            $table->string('url');
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
        Schema::drop('notificaciones');
    }

}
