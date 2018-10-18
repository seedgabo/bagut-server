<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function(Blueprint $table) {
            $table->increments('id');
            $table->string('referencia')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('notas')->nullable();

            $table->integer('stock')->default(0);
            $table->integer('categoria_id')->nullable();
            $table->integer('parent_id')->nullable();
            
            $table->double('precio',10,2);
            $table->double('precio2',10,2)->default(0);
            $table->double('precio3',10,2)->default(0);
            $table->double('precio4',10,2)->default(0);

            $table->boolean('destacado')->default(0);
            $table->boolean('active')->default(1);
            $table->string('data',500)->nullable();
            $table->double('impuesto',10,2)->default(19);
            $table->integer('image_id')->nullable();
            $table->double('disccount',10,2)->default(0);

            $table->boolean('es_vendible_sin_stock')->default(0);
            $table->boolean('mostrar_stock')->default(1);

            $table->integer("user_id")->nullable(); 
                  
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
        Schema::drop('productos');
    }
}
