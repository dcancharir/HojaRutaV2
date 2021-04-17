<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleRuta extends Model
{
    //
    protected $table = 'detalle_ruta';
    protected $primaryKey ='detalle_ruta_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'detalle_ruta_id',
        'ruta_id',
        'orden',
        'tienda_id',
        'visita_id',
        'tipo_detalle',
        'observacion'
    ];
    public $timestamps = false;
    public function tienda(){
        return $this->belongsTo('App\Tienda','tienda_id');
    }
    public function ruta(){
        return $this->belongsTo('App\Ruta','ruta_id');
    }
    public function visita(){
        return $this->belongsTo('App\Visita','visita_id');
    }
}
