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
        return $this->hasMany('App\Respuesta','visita_id');
    }
    public function detalle_ruta(){
        return $this->belongsTo('App\DetalleRuta','visita_id');
    }
    public function supervisor(){
        return $this->belongsTo('App\Supervisor','supervisor_id');
    }
    public $timestamps = false;
}
