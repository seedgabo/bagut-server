<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidoProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido_producto', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('pedido_id')->nullable();
            $table->integer('producto_id');
            $table->integer('image_id')->nullable();

            $table->string('name')->nullable();
            $table->string('referencia')->nullable();

            $table->text('notas')->nullable();

            $table->integer('cantidad_pedidos');
            $table->integer('cantidad_despachado')->default(0);

            $table->double('precio',10,3);
            $table->double('total',10,3);
            $table->double('iva',10,3);
            $table->double('descuento',10,3);

            $table->date('fecha_entrega')->nullable();
            $table->date('fecha_envio')->nullable();
            $table->date('fecha_pedido')->nullable();
                  
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
        Schema::drop('pedido_producto');
    }
}
