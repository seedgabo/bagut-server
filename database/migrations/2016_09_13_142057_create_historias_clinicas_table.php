<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoriasClinicasTable extends Migration
{
    public function up()
    {
        Schema::create('historias_clinicas', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('paciente_id');
            $table->integer('medico_id');
            $table->integer('cie10_id');

            $table->date('fecha');
            $table->time('ingreso');
            $table->time('egreso');

            //ANAMNESIS
            $table->string('motivo_de_consulta');
            $table->text('enfermedad_actual');
            $table->string('revision_por_sistema');

            //ANTECEDENTES
            $table->string('patologicos')->nullable()->default('niega');
            $table->string('quirurjicos')->nullable()->default('niega');
            $table->string('farmacologicos')->nullable()->default('niega');
            $table->string('traumaticos')->nullable()->default('niega');
            $table->string('inmunologicos')->nullable()->default('niega');
            $table->string('familiares')->nullable()->default('niega');
            $table->string('hospitalarios')->nullable()->default('niega');
            $table->string('toxico_alergicos')->nullable()->default('niega');
            $table->string('ginecobstreticos')->nullable()->default('niega');

            //EXAMEN FISICO
            $table->double('frecuencia_cardiaca',15,5);
            $table->double('frecuencia_respiratoria',15,5);
            $table->double('tension_arterial',15,5);
            $table->double('temperatura',15,5);
            $table->double('peso',15,5);
            $table->double('talla',15,5);
            $table->string('aspecto_general')->nullable()->default('normal');
            $table->string('cabeza_cuello')->nullable()->default('normal');
            $table->string('orl')->nullable()->default('normal');
            $table->string('cardio_pulmonar')->nullable()->default('normal');
            $table->string('abdomen')->nullable()->default('normal');
            $table->string('extremidades')->nullable()->default('normal');
            $table->string('piel')->nullable()->default('normal');
            $table->string('neurologico')->nullable()->default('normal');

            $table->text('notas')->nullable();
            $table->text('analisis')->nullable();


                        


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
        Schema::drop('historias_clinicas');
    }
}
