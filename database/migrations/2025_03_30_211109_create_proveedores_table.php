<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable()->unique();
            $table->text('direccion')->nullable();
            $table->json('productos_suministrados')->nullable();
            $table->text('condiciones_pago')->nullable();
            $table->dateTime('fecha_vencimiento_contrato')->nullable();
            $table->boolean('recibir_notificaciones')->default(false);
            $table->string('estado')->default('activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};