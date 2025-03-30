<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToOrdenesTable extends Migration
{
    public function up()
    {
        Schema::table('ordenes', function (Blueprint $table) {
            $table->string('direccion')->nullable()->after('estado');
            $table->text('motivo_reembolso')->nullable()->after('direccion');
        });
    }

    public function down()
    {
        Schema::table('ordenes', function (Blueprint $table) {
            $table->dropColumn(['direccion', 'motivo_reembolso']);
        });
    }
}