<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class Alerta extends Model
{
	use CrudTrait;
	use \Venturecraft\Revisionable\RevisionableTrait;
	protected $revisionCreationsEnabled = true;

	protected $table = 'alertas';
	protected $primaryKey = 'id';
	protected $fillable = ['titulo','mensaje','correo','programado','emitido','usuarios', 'user_id', 'culminacion'];
    protected $dates = ['programado', 'culminacion'];

	protected $casts = [
	      'usuarios' => 'array',
	];

    public function emitir()
    {
    	$usuarios = $this->usuarios();
    	$mails = $usuarios->pluck("email")->toArray();
    	$titulo = $this->titulo;
		$this->emitido = true;
    	$this->save();
		$html = $this->correo;
    	Mail::send([],[], function ($message) use($mails, $titulo,$html){
			$message->to($mails);
			$message->subject($titulo);
			$message->setBody($html, 'text/html');
		});

		Notification::send($usuarios, new \App\Notifications\Alerta($this->titulo, $this->mensaje));
		\App\Models\Dispositivo::SendPush($titulo,$this->mensaje,$usuarios->pluck("id")->toArray());
    }
	public function getUsuariosText()
    {
        $usuarios = \App\User::wherein('id', $this->usuarios)->pluck("nombre");
		return $usuarios->implode(", ", $usuarios);
    }

    public function getDurationAttribute(){
    	if ($this->culminacion) {
    		return $this->culminacion - $this->programado;
    	}
    	return 0;
    }
	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	public function user()
	{
		return $this->belongsTo("\App\User");
	}

	public function usuarios()
    {
        return \App\User::wherein('id', $this->usuarios)->get();
    }

	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/
    public function scopePoremitir($query){
		return $query->where('programado', '<', Carbon::now())->where("emitido","=",false);
	}

    public function scopeEmitidos($query){
		return $query->where('emitido', '=', true);
	}

    public function scopeProximos($query, $cant){
		return $query->where('programado', '>', Carbon::now())->where("emitido","=", false)->orderBy("programado","asc")->limit($cant);
	}
}
