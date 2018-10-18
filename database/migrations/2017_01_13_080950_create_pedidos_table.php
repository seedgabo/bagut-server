<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function(Blueprint $table) {
            $table->increments('id');
            $table->string('numero_pedido');
            $table->text('notas')->nullable();
            $table->string('estado')->default('pedido'); // pedido, enviado, entregado, pagado, reembolso, error en el pago, retrasado, parcialmente enviado, desconocido
            $table->string('direccion_envio',500);
            $table->string('direccion_facturado',500);
            $table->double('subtotal',10,3);
            $table->double('total',10,3);
            $table->double('descuento',10,3);

            $table->date('fecha_entrega')->nullable();
            $table->date('fecha_envio')->nullable();
            $table->date('fecha_pedido')->nullable();

            $table->integer("cliente_id")->nullable(); 
            $table->integer("user_id")->nullable(); 
            $table->integer("invoice_id")->nullable(); 
                  
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
        Schema::drop('pedidos');
    }

}
