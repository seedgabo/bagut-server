<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableArchivosConductoresTable extends Migration
{

    public function up()
    {
        Schema::create('archivo_conductor', function(Blueprint $table) {
            $table->increments('id');
            $table->string('conductor_id');
            $table->text('archivo_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('archivo_conductor');
    }

}
