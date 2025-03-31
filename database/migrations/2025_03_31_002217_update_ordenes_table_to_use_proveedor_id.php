<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordenes', function (Blueprint $table) {
            // Eliminar la clave foránea y columna user_id
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            // Agregar proveedor_id
            $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('cascade')->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('ordenes', function (Blueprint $table) {
            // Revertir: eliminar proveedor_id y restaurar user_id
            $table->dropForeign(['proveedor_id']);
            $table->dropColumn('proveedor_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->after('id');
        });
    }
};