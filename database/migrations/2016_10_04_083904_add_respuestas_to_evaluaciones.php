<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRespuestasToEvaluaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evaluaciones_conductores', function ($table) {
            $table->string('respuestas',2000)->default("[]");
        });
        Schema::table('evaluaciones_proveedores', function ($table) {
            $table->string('respuestas',2000)->default("[]");
        });
        Schema::table('evaluaciones_vehiculos', function ($table) {
            $table->string('respuestas',2000)->default("[]");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
