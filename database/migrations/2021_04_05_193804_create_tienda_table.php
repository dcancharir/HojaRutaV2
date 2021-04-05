<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTiendaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tienda', function (Blueprint $table) {
            $table->increments('tienda_id');
            $table->string('cc');
            $table->string('correo_jop');
            $table->string('correo_sop');
            $table->string('nombres');
            $table->integer('supervisor_id');
            $table->string('latitud');
            $table->string('longitud');
            $table->integer('frecuencia_semanal');
            $table->integer('estado');
            $table->string('direccion');
            $table->integer('nro_visitas_semana');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tienda');
    }
}
