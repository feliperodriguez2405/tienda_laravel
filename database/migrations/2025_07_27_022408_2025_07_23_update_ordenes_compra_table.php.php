<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordenes_compra', function (Blueprint $table) {
            $table->dateTime('fecha')->nullable()->change();
            $table->decimal('monto', 10, 2)->nullable()->change();
            $table->string('estado')->nullable()->change();
            $table->json('detalles')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('ordenes_compra', function (Blueprint $table) {
            $table->dateTime('fecha')->nullable(false)->change();
            $table->decimal('monto', 10, 2)->nullable(false)->change();
            $table->string('estado')->default('pendiente')->nullable(false)->change();
            $table->json('detalles')->nullable()->change();
        });
    }
};
