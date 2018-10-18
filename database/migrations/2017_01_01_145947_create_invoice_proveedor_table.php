<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceProveedorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices_proveedor', function(Blueprint $table){
            $table->increments('id');
            $table->integer('proveedor_id');
            $table->integer("user_id")->nullable();
            $table->string("resolucion")->nullable();
            $table->string("regimen")->nullable();
            $table->string("cabecera")->nullable();
            $table->date("fecha");
            $table->date("vencimiento")->nullable();
            $table->text("proveedor_data")->nullable();
            $table->text("direccion")->nullable();
            $table->string("orden_compra")->nullable();
            $table->string("estado")->default("emitida");  // emitida,  pagada, vencida, anulada, rechazada
            $table->double("valor",10,3)->default(0);
            $table->text("nota")->nullable();
            $table->text("pie")->nullable();
            $table->text("items")->nullable();
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
        Schema::drop('invoices_proveedor');

    }
}
