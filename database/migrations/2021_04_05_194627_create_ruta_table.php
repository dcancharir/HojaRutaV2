<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRutaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ruta', function (Blueprint $table) {
            $table->increments('ruta_id');
            $table->integer('supervisor_id');
            $table->datetime('fecha');
            $table->integer('estado_completo');
            $table->integer('tiendas_pendientes');
            $table->integer('tiendas_visitadas');
            $table->integer('tipo_ruta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ruta');
    }
}
