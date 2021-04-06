<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tienda extends Model
{
    //
    protected $table = 'tienda';
    protected $primaryKey ='tienda_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tienda_id', 
        'cc',
        'correo_jop',
        'correo_sop',
        'nombres',
        'supervisor_id',
        'longitud',
        'latitud',
        'frecuencia_semanal',
        'estado',
        'direccion',
        'nro_visitas_semana'
    ];
}
