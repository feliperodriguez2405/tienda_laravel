<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProveedoresTableMakeFieldsNullable extends Migration
{
    public function up()
    {
        Schema::table('proveedores', function (Blueprint $table) {
            $table->string('nombre')->nullable()->change();
            $table->string('telefono')->nullable()->change();
            $table->string('email')->nullable()->change(); // Sin unique(), ya existe
            $table->text('direccion')->nullable()->change();
            $table->json('productos_suministrados')->nullable()->change();
            $table->text('condiciones_pago')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('proveedores', function (Blueprint $table) {
            $table->string('nombre')->nullable(false)->change();
            $table->string('telefono')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->text('direccion')->nullable(false)->change();
            $table->json('productos_suministrados')->nullable(false)->change();
            $table->text('condiciones_pago')->nullable(false)->change();
        });
    }
}