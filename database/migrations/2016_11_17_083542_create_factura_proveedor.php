<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturaProveedor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas_proveedores', function(Blueprint $table) {
            $table->increments('id');
            $table->string("referencia")->nullable();
            $table->integer('user_id')->nullable();
            $table->integer("proveedor_id")->nullable();
            $table->date("fecha");
            $table->float("valor",10,3)->nullable();
            $table->float("iva",10,3)->default(16)->nullable();
            $table->string("estado")->default("recibida");  // emitida,  pagada, vencida, anulada, rechazada
            $table->text("nota")->nullable();
            $table->float("rete_ica",10,3)->default(0);
            $table->float("rete_iva",10,3)->default(0);
            $table->float("rete_fuentes",10,3)->default(0);
            $table->float("otros",10,3)->default(0);
            $table->integer('archivo_id')->nullable();
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
        Schema::drop('facturas_proveedores');
    }
}
