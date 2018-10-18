<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backpack\CRUD\CrudTrait;
use Illuminate\Support\Facades\DB;
class documentos extends Model
{
    use SoftDeletes;
    use CrudTrait;
    use \Venturecraft\Revisionable\RevisionableTrait;

    public $table = 'documentos';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'titulo',
        'descripcion',
        'version',
        'editable',
        'categoria_id',
        "activo",
        "archivo"
    ];
    
    protected $appends = ['mime'];


    protected $casts = [
        'titulo' => 'string',
        'descripcion' => 'string',
        'descripcion' => 'string',
        'categorias_id' => 'integer',
        'version' => 'string',
        'editable' => 'boolean'
    ];

    public static $rules = [
        'titulo' => 'required|min:3|max:50|text',
        'descripcion' => 'min:3|max:50|text'
    ];

	public function categoria()
	{
		return $this->belongsTo('\App\Models\CategoriaDocumentos','categoria_id','id');
	}

    public function getActivoText(){
        return $this->activo == 1 ? 'Activo' : '<span style="color:red">Inactivo</span>';
    }

    public function getEditableText(){
        return $this->editable == 1 ? 'Editable' : '<span style="color:red">Protegido</span>';
    }

    public function getLinkArchivo()
    {
        return "<a href='" . url('getDocumento/'. $this->id). "'> Ver Documento </a>";
    }


    public static function getDescargasByDocumentos($limit)
    {
       return \App\Models\Auditorias::select(DB::raw('count(*) as count'),'documentos.titulo')
       ->where('tipo',"=","descarga")
        ->groupBy('documento_id')
        ->rightJoin('documentos','documentos.id',"=",'auditorias.documento_id')
        ->orderBy('count','desc')
        ->limit($limit)
        ->get();

    }

    public function getMimeAttribute()
    {
         return substr(strrchr($this->archivo,'.'),1);
    }
}
