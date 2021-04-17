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
            $table->string('cc')->nullable();
            $table->string('correo_jop')->nullable();
            $table->string('correo_sop')->nullable();
            $table->string('nombres')->nullable();
            $table->integer('supervisor_id');
            $table->string('latitud')->nullable();
            $table->string('longitud')->nullable();
            $table->integer('frecuencia_semanal');
            $table->integer('estado');
            $table->string('direccion')->nullable();
            $table->integer('nro_visitas_semana');
            $table->integer('razon_social_id')->nullable();
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
