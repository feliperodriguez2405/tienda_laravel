<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdjustProductosTable extends Migration
{
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->unsignedBigInteger('categoria_id')->nullable()->change();
            $table->decimal('precio', 8, 2)->nullable()->change();
            $table->decimal('precio_compra', 8, 2)->nullable()->change();
            // Si tienes otras columnas como descripción, también hazlas nullable si no las usas aquí
            // $table->text('descripcion')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->unsignedBigInteger('categoria_id')->nullable(false)->change();
            $table->decimal('precio', 8, 2)->nullable(false)->change();
            $table->decimal('precio_compra', 8, 2)->nullable(false)->change();
            // Revertir otras columnas si las modificaste
            // $table->text('descripcion')->nullable(false)->change();
        });
    }
}