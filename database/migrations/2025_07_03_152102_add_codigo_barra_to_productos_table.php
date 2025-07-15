<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->string('codigo_barra')->nullable()->unique()->after('nombre');
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('codigo_barra');
        });
    }
};