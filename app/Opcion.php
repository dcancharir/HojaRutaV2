<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Opcion extends Model
{
    //
    protected $table = 'opcion';
    protected $primaryKey ='opcion_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'opcion_id',
        'opcion', 
        'estado',
    ];
}
