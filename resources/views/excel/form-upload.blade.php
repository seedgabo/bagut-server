@extends('backpack::layout') 
@section('header')
<section class="content-header">
    <h1>
    	Importar @choice('literales.ticket', 10)
	</h1>
    <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">{{ trans('backpack::base.dashboard') }}</li>
    </ol>
</section>
@endsection 

@section('content')
<div class="row col-md-9 col-md-offset-1">
<div class="box">
	<div class="box-body">
		{!! Form::open(['method' => 'POST', 'class' => 'form-horizontal', 'files' => true]) !!}

		    <div class="form-group{{ $errors->has('file') ? ' has-error' : '' }}">
		        {!! Form::label('file', 'Archivo Excel', ['class' => 'col-sm-3 control-label']) !!}
		        <div class="col-sm-9">
		    	    {!! Form::file('file', ['required' => 'required']) !!}  
		    	    <small class="text-danger">{{ $errors->first('file') }}</small>
		        </div>
		    </div>

		    <div class="btn-group pull-right">
		        {!! Form::submit("Subir", ['class' => 'btn btn-success']) !!}
		    </div>

		{!! Form::close() !!}
		
	</div>
</div>
</div>
@stop