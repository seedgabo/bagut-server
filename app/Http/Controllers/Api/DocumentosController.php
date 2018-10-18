<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class DocumentosController extends ApiController
{
    public $model = "\App\Models\Documentos";
}
