<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConfirmadoPorVendedorToOrdenesCompraTable extends Migration
{
    public function up()
    {
        Schema::table('ordenes_compra', function (Blueprint $table) {
            $table->boolean('confirmado_por_vendedor')->default(false)->after('detalles');
        });
    }

    public function down()
    {
        Schema::table('ordenes_compra', function (Blueprint $table) {
            $table->dropColumn('confirmado_por_vendedor');
        });
    }
}