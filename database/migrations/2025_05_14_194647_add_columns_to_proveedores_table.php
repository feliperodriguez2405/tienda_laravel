<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('proveedores', function (Blueprint $table) {
            // Add fecha_vencimiento_contrato if it doesn't exist
            if (!Schema::hasColumn('proveedores', 'fecha_vencimiento_contrato')) {
                $table->dateTime('fecha_vencimiento_contrato')->nullable()->after('condiciones_pago');
            }
            // Add recibir_notificaciones if it doesn't exist
            if (!Schema::hasColumn('proveedores', 'recibir_notificaciones')) {
                $table->boolean('recibir_notificaciones')->default(false)->after('fecha_vencimiento_contrato');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proveedores', function (Blueprint $table) {
            $table->dropColumn(['fecha_vencimiento_contrato', 'recibir_notificaciones']);
        });
    }
}