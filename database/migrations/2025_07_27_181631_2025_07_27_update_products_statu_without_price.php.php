<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing products with precio <= 0 or null to estado = 'inactivo'
        DB::table('productos')
            ->where('precio', '<=', 0)
            ->orWhereNull('precio')
            ->update(['estado' => 'inactivo']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse action needed, as we don't want to arbitrarily reactivate products
    }
};