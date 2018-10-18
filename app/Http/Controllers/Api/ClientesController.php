<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class ClientesController extends ApiController
{
    public $model = "\App\Models\Cliente";



    public function canList(Request $request){
        return Auth::user()->canAny(['Agregar Clientes','Editar Clientes','Eliminar Clientes']) || Auth::user()->hasRole('SuperAdmin');
    }

    public function canView(Request $request, $id){
        return Auth::user()->canAny(['Agregar Clientes' ,'Editar Clientes','Eliminar Clientes']) || Auth::user()->hasRole('SuperAdmin');
    }

    public function canStore(Request $request){
        return Auth::user()->can('Agregar Clientes') || Auth::user()->hasRole('SuperAdmin');
    }

    public function canUpdate(Request $request, $id){
        return Auth::user()->can('Editar Clientes') || Auth::user()->hasRole('SuperAdmin');
    }
    
    public function canDelete(Request $request, $id){
        return Auth::user()->can('Eliminar Clientes') || Auth::user()->hasRole('SuperAdmin');
    }
}
