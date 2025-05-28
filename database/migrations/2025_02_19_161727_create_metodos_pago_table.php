<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\MetodoPago;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metodos_pago', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->timestamps();
        });

        // Insertar datos iniciales
        MetodoPago::create(['nombre' => 'efectivo']);
        MetodoPago::create(['nombre' => 'nequi']);
    }

    public function down(): void
    {
        Schema::dropIfExists('metodos_pago');
    }
};