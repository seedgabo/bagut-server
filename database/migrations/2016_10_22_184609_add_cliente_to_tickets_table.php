<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClienteToTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('users', function($table) {
            $table->integer("cliente_id")->nullable()->unsigned();
        });        

        Schema::table('tickets', function($table) {
            $table->integer("cliente_id")->nullable()->unsigned();
        });

        Schema::table('comentarios_tickets', function($table) {
            $table->boolean("publico")->default('0');
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
