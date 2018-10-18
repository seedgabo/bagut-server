<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminat\Support\Facades\Input;
class UsersController extends ApiController
{
    public $model = "\App\User";



    public function canList(Request $request){
		return true;
        return Auth::user()->canAny(['Agregar Usuarios','Editar Usuarios','Eliminar Usuarios']) || Auth::user()->hasRole('SuperAdmin');
    }

    public function canView(Request $request, $id){	
	if($id == Auth::user()->id) return true;
        return Auth::user()->canAny(['Agregar Usuarios' ,'Editar Usuarios','Eliminar Usuarios']) || Auth::user()->hasRole('SuperAdmin');
    }

    public function canStore(Request $request){
        return Auth::user()->can('Agregar Usuarios') || Auth::user()->hasRole('SuperAdmin');
    }

    public function canUpdate(Request $request, $id){
        return Auth::user()->can('Editar Usuarios') || Auth::user()->hasRole('SuperAdmin');
    }
    
    public function canDelete(Request $request, $id){
        return Auth::user()->can('Eliminar Usuarios') || Auth::user()->hasRole('SuperAdmin');
    }
}
