<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class ProductosController extends ApiController
{
    public $model = "\App\Models\Producto";



    public function canList(){
        return Auth::user()->canAny(['Agregar Productos','Editar Productos','Eliminar Productos']) || Auth::user()->hasRole('SuperAdmin');
    }

    public function canView(){
        return Auth::user()->canAny(['Agregar Productos' ,'Editar Productos','Eliminar Productos']) || Auth::user()->hasRole('SuperAdmin');
    }

    public function canStore(){
        return Auth::user()->can('Agregar Productos') || Auth::user()->hasRole('SuperAdmin');
    }

    public function canUpdate(){
        return Auth::user()->can('Editar Productos') || Auth::user()->hasRole('SuperAdmin');
    }
    
    public function canDelete(){
        return Auth::user()->can('Eliminar Productos') || Auth::user()->hasRole('SuperAdmin');
    }
}
