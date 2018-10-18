@extends('clientes.formularios.layout')

@section('content')
	<tickets :tickets=' {!! Auth::user()->cliente->tickets()->with('user','guardian','categoria')->get() !!} '></tickets>
@stop