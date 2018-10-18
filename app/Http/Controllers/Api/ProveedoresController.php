<?php

namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProveedoresController extends ApiController
{
    public $model = "\App\Models\Proveedor";
    public function canList(){
        return Auth::user()->canAny(['Agregar Proveedores','Editar Proveedores','Eliminar Proveedores']) || Auth::user()->hasRole('SuperAdmin');
    }

    public function canView(){
        return Auth::user()->canAny(['Agregar Proveedores' ,'Editar Proveedores','Eliminar Proveedores']) || Auth::user()->hasRole('SuperAdmin');
    }

    public function canStore(){
        return Auth::user()->can('Agregar Proveedores') || Auth::user()->hasRole('SuperAdmin');
    }

    public function canUpdate(){
        return Auth::user()->can('Editar Proveedores') || Auth::user()->hasRole('SuperAdmin');
    }
    
    public function canDelete(){
        return Auth::user()->can('Eliminar Proveedores') || Auth::user()->hasRole('SuperAdmin');
    }
}
