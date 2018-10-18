<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCodigoToEntidadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entidades', function($table) {
            $table->string("codigo");
        });
        Schema::table('clientes', function($table) {
            $table->integer("entidad_id");
        });
        Schema::table('pedidos', function($table) {
            $table->integer("entidad_id");
        });
        Schema::table('proveedores', function($table) {
            $table->integer("entidad_id");
        });
        Schema::table('users', function($table) {
            $table->integer("entidad_id");
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
