<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
class Usuarios extends Model {
    use CrudTrait;
    use softDeletes;
    use Notifiable;
    /**
     * A user may have multiple roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(
            config('laravel-permission.models.role'),
            config('laravel-permission.table_names.user_has_roles'),
            'user_id',
            'role_id'
        );
    }

    /**
     * A user may have multiple direct permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(
            config('laravel-permission.models.permission'),
            config('laravel-permission.table_names.user_has_permissions'),
            'user_id',
            'permission_id'
        );
    }


    use \Venturecraft\Revisionable\RevisionableTrait;
    protected $revisionCreationsEnabled = true;
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = ["nombre","email","categorias_id","admin","departamento","cargo",'medico','cliente_id'];

    protected $casts = [
        'categorias_id' => 'array',
        'admin' => 'boolean',
        'medico' => 'boolean',
    ];


    public function getCategorias()
    {
        if(isset($this->categorias_id))
          return CategoriasTickets::wherein('id', $this->categorias_id)->get();
        else
          return CategoriasTickets::wherein('id', [])->get();
    }

    public function imagen()
    {
        $files =glob(public_path().'/img/users/'. $this->id . "*");
        if($files)
            return $url = asset('img/users/'. $this->id. "." . pathinfo($files[0], PATHINFO_EXTENSION));
        else
            return $url = asset('/img/user.jpg');
    }

    public function tickets()
    {
        return $this->hasMAny("\App\Models\Tickets","user_id","id");
    }

    public function categoriasdocumentos(){
        return $this->belongsToMany("\App\Models\CategoriaDocumentos", "categoria_documento_user", "user_id","categoria_documento_id");
    }

    public function ticketsResponsables()
    {
        return $this->hasMAny("\App\Models\Tickets","guardian_id","id");
    }

    public function comentarios()
    {
        return $this->hasMAny("\App\Models\ComentariosTickets","user_id","id");
    }

    public function notificaciones()
    {
        return $this->hasMany('\App\Models\Notificacion','user_id','id');
    }

    public function  tickets_guardian()
    {
        return $this->hasMany("\App\Models\Tickets","guardian_id","id");
    }

    public function auditorias()
    {
        return $this->hasMany("\App\Models\Auditorias","user_id","id");
    }

    public function cliente()
    {
      return $this->belongsTo("\App\Models\Cliente","cliente_id","id");
    }


    public function clientes()
    {
      return $this->hasMany("\App\Models\Cliente","user_id","id");
    }

    public function procesos()
    {
      return $this->hasMany("\App\Models\Proceso","user_id","id");
    }
    
    public function consultas()
    {
      return $this->hasMany("\App\Models\Consulta","user_id","id");
    }

    public function getNameAttribute()
    {
      return $this->nombre;
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

    public function CategoriasTickets(){
        return $this->belongsToMany('App\Models\CategoriasTickets','categoria_user','user_id','categoria_id');
    }


    public function getButtonAuditar()
    {
        return '<a class="btn btn-outline btn-warning btn-xs" href="'. url('admin/auditar/usuario/'. $this->id) .'"> <i class="fa fa-files-o"></i> Auditar</a>';
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
        if(isset($this->categorias_id))
        {
            $cat = CategoriasTickets::wherein('id', $this->categorias_id)->pluck("nombre");
            return str_limit(strip_tags($cat->implode(", ", $cat)), 80, "[...]");
        }
        return "";
    }

    public function getMedicoText()
    {
        return $this->medico == 1 ? "Si" : "No";
    }

    public function  imagen_html()
    {
        return '<img style="height:45px; width:45px;" src="'. $this->imagen() . '"/>';
    }
}
