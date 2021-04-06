<?php

namespace App\Http\Controllers;

use App\Supervisor;
use Illuminate\Http\Request;

class SupervisorController extends Controller
{
    public function listarSupervisores(){
        $supervisores=Supervisor::all();
        return $supervisores;
    }
}
