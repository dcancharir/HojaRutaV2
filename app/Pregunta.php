<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    //
    protected $table = 'pregunta';
    protected $primaryKey ='pregunta_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pregunta_id',
        'titulo',
        'tipo',
        'estado',
    ];
    public $timestamps = false;
}
