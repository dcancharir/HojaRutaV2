<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    //
    protected $table = 'respuesta';
    protected $primaryKey ='respuesta_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'respuesta_id',
        'pregunta_id',
        'opcion_id',
        'observacion',
        'visita_id',
        'tipo',
        'estado'
    ];
    public function pregunta(){
        return $this->belongsTo('App\Pregunta','pregunta_id');
    }
    public function opcion(){
        return $this->belongsTo('App\Opcion','opcion_id');
    }
    public $timestamps = false;
}
