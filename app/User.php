<?php

namespace App;

use App\Empresas;
use App\Models\CategoriasTickets;
use Backpack\CRUD\CrudTrait;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Spatie\Permission\Traits\HasRoles;
class User extends Authenticatable
{
  use CrudTrait;
  use HasRoles;
  use softDeletes;
  use Notifiable;
  use CanResetPassword;

  protected $fillable = [
  "nombre","email","categorias_id","admin","departamento","cargo",'medico','cliente_id','is_vendedor','direccion','telefono','entidad_id','notas'
  ,'password'];

  protected $hidden = [
  'password', 'remember_token',
  ];

  protected $casts = [
  'categorias_id' => 'array',
  ];

  protected $dates = ['deleted_at'];

  protected $appends = ['imagen'];

  public function Categorias()
  {
    if(isset($this->categorias_id))
      return CategoriasTickets::wherein('id', $this->categorias_id)->get();
    else
      return CategoriasTickets::wherein('id', [])->get();
  }

  public function CategoriasTickets(){
    return $this->belongsToMany('App\Models\CategoriasTickets','categoria_user','user_id','categoria_id');
  }


  public function tickets()
  {
    return $this->hasMAny("\App\Models\Tickets","user_id","id");
  }

  public function ticketsResponsables()
  {
    return $this->hasMAny("\App\Models\Tickets","guardian_id","id");
  }

  public function comentarios()
  {
    return $this->hasMany("\App\Models\ComentariosTickets","user_id","id");
  }


  public function  tickets_guardian()
  {
    return $this->hasMany("\App\Models\Tickets","guardian_id","id");
  }
  
  public function notificaciones()
  {
    return $this->hasMany('\App\Models\Notificacion','user_id','id');
  }

  public function misAlertas(){
    return \App\Models\Alerta::
    where("user_id",$this->id)
    ->orWhere(function($q){
      $q->orWhere('usuarios',"LIKE",'['. $this->id .']')
      ->orWhere('usuarios',"LIKE",'"'. $this->id .'"')
      ->orWhere('usuarios',"LIKE",'['. $this->id .',');
    });
  }


  public function auditorias()
  {
    return $this->hasMany("\App\Models\Auditorias","user_id","id");
  }

  public function atajos()
  {
    return $this->hasMany('\App\Models\Atajo','user_id','id');
  }

  public function clientes()
  {
    return $this->hasMany("\App\Models\Cliente","user_id","id");
  }

  public function cliente()
  {
    return $this->belongsTo("\App\Models\Cliente","cliente_id","id");
  }

  public function procesos()
  {
    return $this->hasMany("\App\Models\Proceso","user_id","id");
  }

  public function consultas()
  {
    return $this->hasMany("\App\Models\Consulta","user_id","id");
  }

  public function pedidos(){
    return $this->hasMany('\App\Models\Pedido','user_id','id');
  }

  public function getNameAttribute()
  {
    return $this->nombre;
  }

  public function EventsCalendar($withUrl = true)
  {
    if ($this->hasRole('SuperAdmin')) {$tickets = \App\Models\Tickets::
      where('vencimiento',"<>","null")
      ->select("id","titulo as title","vencimiento as start","estado as className", "contenido")
      ->orderBy("vencimiento","desc")
      ->take(800)->get();
      $alertas = \App\Models\Alerta::select("id","titulo as title","programado as start", "culminacion as end" , "titulo as description")->take(1500)->get();
    }
    else{
      $tickets = \App\Models\Tickets::todos()
      ->where('vencimiento',"<>","null")
      ->select("id","titulo as title","vencimiento as start","estado as className", "contenido")
      ->orderBy("vencimiento","desc")
      ->take(800)->get();
      $alertas = $this->misAlertas()->select("id","titulo as title","programado as start", "culminacion as end" , "titulo as description")->take(1500)->get();
    }

    $tickets->each(function($t) use ($withUrl) {
      if($withUrl){
        $t->url = url('ticket/ver/'. $t->id);
      }
      $t->textColor = "black";
      $t->type = "ticket";
      $t->description = str_limit(strip_tags($t->contenido), 80, "...");
    });

    $events = $tickets;

    $alertas->each(function($a) use ($events, $withUrl){
      if($withUrl && (Auth::user()->hasRole("Administrar Alertas" ) ||  Auth::user()->hasRole('SuperAdmin') ) ){
        $a->url = url('admin/alertas/'. $a->id . '/edit');
      }
      $a->backgroundColor = "yellow";
      $a->textColor= "black";
      $a->type = "alert";
      $events->push($a);
    });
    return $events;
  }

  public function canAny($permisos)
  {
    foreach ($permisos as $permiso) {
      if($this->can($permiso))
        return true;
    }
    return false;
  }

  public function canAll($permisos)
  {
    foreach ($permisos as $permiso) {
      if($this->cannot($permiso))
        return false;
    }
    return true;
  }
  
  public function getButtonAuditar()
  {
    return '<a class="btn btn-default btn-xs" href="'. url('admin/auditar/usuario/'. $this->id) .'"> <i class="fa fa-files-o"></i> Auditar</a>';
  }


  public function getAdmin()
  {
    if($this->admin == 1)
    {
      return "Administrador";
    }
    else
      return "Usuario";
  }

  public function getCategoriasText()
  {
    $cat = CategoriasTickets::wherein('id', $this->categorias_id)->pluck("nombre");
    return $cat->implode(", ", $cat);
  }

  public function getMedicoText()
  {
    return $this->medico == 1 ? "Si" : "No";
  }

  public function imagen()
  {
    $files =glob(public_path().'/img/users/'. $this->id . "*");
    if($files)
      return $url = asset('img/users/'. $this->id. "." . pathinfo($files[0], PATHINFO_EXTENSION));
    else
      return $url = asset('/img/user.jpg');
  }

  public function  imagen_html()
  {
    return '<img style="height:45px; width:45px;" src="'. $this->imagen() . '"/>';
  }

  public function getImagenAttribute()
  {
    return $this->imagen();
  }

  public function getImagenHtmlAttribute()
  {
    return $this->imagen_html();
  }


  public function scopeQNoclientes($query)
  {
   return $query->where(function($q){
     return $q->doesntHave("cliente");
   });
 }

 public function scopeQClientes($query)
 {
   return $query->where(function($q){
     return $q->has('cliente');
   });
 }


 public function anyPermission($model){
    return $this->canAny(['Agregar ' . $model, 'Editar ' . $model , 'Eliminar ' . $model]) || $this->hasRole('SuperAdmin');
 }

  public function categoriasdocumentos(){
    return $this->belongsToMany("\App\Models\CategoriaDocumentos", "categoria_documento_user", "user_id","categoria_documento_id");
  }

}
