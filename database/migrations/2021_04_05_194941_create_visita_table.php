<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visita', function (Blueprint $table) {
            $table->increments('visita_id');
            $table->datetime('fecha_visita');
            $table->string('latitud');
            $table->string('longitud');
            $table->string('imei');
            $table->integer('estado_visita');
            $table->string('observacion');
            $table->integer('orden');
            $table->integer('supervisor_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visita');
    }
}
