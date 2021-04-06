<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    //
    protected $table = 'visita';
    protected $primaryKey ='visita_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'visita_id', 
        'fecha_visita',
        'latitud',
        'longitud',
        'imei',
        'estado_visita',
        'observacion',
        'orden',
        'supervisor_id'
    ];
    public function respuestas(){
        return $this->hasMany('App\Visita','visita_id');
    }
}
