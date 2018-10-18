<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;

class ProcesoMasivoClienteController extends ApiController
{
    public $model = "\App\Models\ProcesosMasivosCliente";

    public function updateMany(Request $request)
    {
        $ids = $request->input('ids');
        $this->validate($request, $this->updateRules);
        $class = $this->model;
        $results = $class::where('proceso_masivo_id', $request->input('proceso_id'))->whereIn("cliente_id", $ids)->update($request->except('ids', 'proceso_id'));
        return $class::where('proceso_masivo_id', $request->input('proceso_id'))->whereIn("cliente_id", $ids)->get();
    }
}
