<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    //
    protected $table = 'ruta';
    protected $primaryKey ='ruta_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ruta_id',
        'supervisor_id',
        'fecha',
        'estado_completo',
        'tiendas_pendientes',
        'tiendas_visitadas',
        'tipo_ruta'
    ];
    public $timestamps = false;
    public function detalles_ruta(){
        return $this->hasMany('App\DetalleRuta','ruta_id');
    }
}
