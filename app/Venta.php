<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    //
    protected $table = 'venta';
    protected $primaryKey ='venta_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'venta_id', 
        'fecha',
        'tienda_id',
        'monto',
        'moneda',
    ];
}
