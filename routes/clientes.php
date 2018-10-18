<?php
use App\Funciones;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;


Route::group(['middleware' => ['isCliente', 'clientes'], 'prefix' => 'clientes'], function () {

	Route::get('/', 'ClientesController@menuCliente');
	Route::get('/invoices', 'ClientesController@verFacturas');
	Route::get('/ver-invoice/{id}', 'ClientesController@verFactura');
	Route::get('/ver-invoice/pdf/{id}', 'ClientesController@verFacturaPdf');
	Route::get('/invoices', 'ClientesController@verFacturas');
	Route::get('/ticket/{id}', 'ClientesController@verTicket');
	Route::post('/ticket/{id}/comentario', 'ClientesController@addComentario');
	Route::delete('/comentario/{id}', 'ClientesController@deleteComentario');

	Route::get('notificaciones', 'ClientesController@verNotificaciones');
	Route::get('notificacion/{id}', 'ClientesController@verNotificacion');
	Route::get('notificaciones/read-all', 'ClientesController@leerTodasNotificaciones');

	Route::get('profile', 'ClientesController@profile');
	Route::put('profile', 'ClientesController@profileUpdate');	
});
