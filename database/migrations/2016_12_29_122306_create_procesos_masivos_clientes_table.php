<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateProcesosMasivosClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $fields = \App\Models\ProcesosMasivosCliente::fields;
        // Schema::drop('procesos_masivos_clientes');
        Schema::create('procesos_masivos_clientes', function(Blueprint $table) use($fields){
            $table->increments('id');
            $table->integer('ticket_id')->nullable();
            $table->timestamp('fecha_agregado')->default(DB::raw('CURRENT_TIMESTAMP'));
            foreach ($fields as $key => $field) {
                $name= str_replace(" ","_",strtolower($key));
                if($field['type'] == "date")
                    $table->date($name)->nullable();
                else if(!isset($field['no_migrate']))
                    $table->string($name)->nullable();
            }
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
        Schema::drop('procesos_masivos_clientes');

    }
}
