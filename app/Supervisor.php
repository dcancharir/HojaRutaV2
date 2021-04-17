<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class Supervisor extends Authenticatable
{
    use Notifiable;
    protected $table = 'supervisor';
    protected $primaryKey ='supervisor_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'supervisor_id',
        'usuario',
        'nombres',
        'estado'
    ];
    protected $hidden = [
        'password'
    ];
    public $timestamps = false;
    public function tiendas(){
        return $this->hasMany("App\Tienda",'supervisor_id');
    }
}
