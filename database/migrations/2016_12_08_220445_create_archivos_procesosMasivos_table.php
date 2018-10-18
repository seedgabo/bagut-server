<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchivosProcesosMasivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archivos_procesosMasivos', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('cliente_id');
            $table->integer('procesoMasivo_id');
            $table->integer('archivo_id');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('archivos', function ($table) {
            $table->integer('paginas')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('archivos_procesosMasivos');
        Schema::table('archivos', function ($table) {
            $table->dropColumn(['paginas']);
        });
    }
}
