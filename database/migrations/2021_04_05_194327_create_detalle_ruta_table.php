<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleRutaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_ruta', function (Blueprint $table) {
            $table->increments('detalle_ruta_id');
            $table->integer('ruta_id');
            $table->integer('orden');
            $table->integer('tienda_id');
            $table->integer('visita_id')->nullable();
            $table->integer('tipo_detalle');
            $table->string('observacion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_ruta');
    }
}
