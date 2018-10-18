<?php

namespace App\Models;

use App\Models\Notificacion;
use App\User;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
class Notificacion extends Model
{
	use CrudTrait;
	use softDeletes;
	use \Venturecraft\Revisionable\RevisionableTrait;
	protected $revisionCreationsEnabled = true;

	protected $table = 'notificaciones';
	protected $primaryKey = 'id';

    protected $dates = ['deleted_at'];
	
	public static $rules = [
		'title' => 'required',
		'mensaje' => 'required',
		'user_id' =>'number:required'
	];

	protected $fillable = ['titulo','mensaje','user_id','leido','url', 'admin', 'ticket_id','paciente_id'];

	public function scopeNoleidos($query){
		return $query->where('leido', '=', 0);
	}

	public function scopeLeidos($query){
		return $query->where('leido', '=', 1);
	}

	public function scopeUsuario($query, $user_id){
		return $query->where('user_id', '=', $user_id);
	}

	public function user(){
		return $this->hasOne('\App\User','id','user_id');
	}

	public static function noLeidas($user_id){
		return Notificacion::usuario($user_id)->noleidos()->orderby("created_at","desc")->get();
	}

	public static function getByUser($take = 50){
		return Notificacion::where('user_id',"=",Auth::user()->id)->orderby("id","desc")->get();
	}



	public  static function InsertarNotificacionesMasivas($titulo,$mensaje,$excluidos = []){
		$notificaciones= [];
		$usuarios = User::whereNotIn('id',$excluidos)->get();
		foreach ($usuarios as $usuario) {
			$notificaciones[] = ['titulo' =>$titulo, 'mensaje' => $mensaje, 'user_id' => $usuario->id, 'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')];
		}

		Notificacion::insert($notificaciones);
	}


	public  static function InsertarNotificacionesAUsuarios($titulo,$mensaje,$usuarios, $data=null){
		$notificaciones= [];
		$usuarios = User::whereIn('id',$usuarios)->get();
		foreach ($usuarios as $usuario) 
		{
			$aux =  ['titulo' =>$titulo, 'mensaje' => $mensaje, 'user_id' => $usuario->id, 'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')];
			if($data !=null)
			{
				$aux = array_merge($aux, $data);
			}
			$notificaciones[] = $aux;
		}

		Notificacion::insert($notificaciones);
	}

}
