<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriasProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
        public function up()
        {
            Schema::create('categorias_productos', function(Blueprint $table) {
                $table->increments('id');
                $table->string('nombre');
                $table->text('descripción')->nullable();
                $table->integer('parent_id');
                $table->integer('lft')->nullable();
                $table->integer('rgt')->nullable();
                $table->integer('depth')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }


        public function down()
        {
            Schema::drop('categorias_productos');
        }
}
