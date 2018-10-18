<?php

namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketsController extends ApiController
{
    public $model = "\App\Models\Tickets";
    public function canList(){
        return Auth::user()->canAny(['Agregar Casos','Editar Casos','Eliminar Casos']) || Auth::user()->hasRole('SuperAdmin');
    }

    public function canView(){
        return Auth::user()->canAny(['Agregar Casos' ,'Editar Casos','Eliminar Casos']) || Auth::user()->hasRole('SuperAdmin');
    }

    public function canStore(){
        return Auth::user()->can('Agregar Casos') || Auth::user()->hasRole('SuperAdmin');
    }

    public function canUpdate(){
        return Auth::user()->can('Editar Casos') || Auth::user()->hasRole('SuperAdmin');
    }
    
    public function canDelete(){
        return Auth::user()->can('Eliminar Casos') || Auth::user()->hasRole('SuperAdmin');
    }
}
