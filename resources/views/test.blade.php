@extends('layouts.app')

@section('content')
	<div class="form-group{{ $errors->has('clientes') ? ' has-error' : '' }}">
	    {!! Form::label('clientes', 'Clientes') !!}
	    {!! Form::select('clientes',["hola" => "hola"], null , ['id' => 'clientes', 'class' => 'form-control select2ajax', 'required' => 'required', 'data-url'=> url('select2/clientes')]) !!}
	    <small class="text-danger">{{ $errors->first('clientes') }}</small>
	</div>
@stop