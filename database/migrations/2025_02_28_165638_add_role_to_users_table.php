<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('usuarios', function (Blueprint $table) {
        $table->string('rol')->default('cliente')->after('password');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('usuarios', function (Blueprint $table) {
        $table->dropColumn('rol');
    });
}
};
