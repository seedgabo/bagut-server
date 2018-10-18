<?php

namespace App\Http\Controllers;

use App\Funciones;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\CategoriasTickets;
use App\Models\Cliente;
use App\Models\Tickets;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;

class Select2Controller extends Controller
{

  public function clientes(Request $request)
  {
     $search = $request->input('query');
     $results = Cliente::orWhere(function($q)use ($search){
      $q->orWhere("nombres","LIKE", "%". $search ."%")
      ->orWhere("apellidos","LIKE", "%". $search ."%")
      ->orWhere("nit","LIKE", "%". $search ."%")
      ->orWhere("cedula","LIKE", "%". $search ."%");
     })
     ->take(50)
     ->get();
     $results->each(function($item){
        $item->text = $item->full_name;
        $item->id = $item->id;
     });
     return $results;
  }

  public function users(Request $request)
  {
     $search = $request->input('query');
     $results = User::orWhere(function($q)use ($search){
      $q->orWhere("nombre","LIKE", "%". $search ."%")
         ->orWhere("email","LIKE", "%". $search ."%");
     })
     ->take(50)
     ->get();
     $results->each(function($item){
        $item->text = $item->nombre;
        $item->id = $item->id;
     });
     return $results;
  }

}
