<?php

use Illuminate\Http\Request;
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers:Origin, Content-Type, Accept, Authorization, X-Requested-With,X-XSRF-TOKEN, Auth-Token, X-CSRF-TOKEN');

	//Api
	Route::group(['prefix' => 'api', 'middleware' => ['auth.basic.once']], function(){
		Route::get('login', 'ApiController@doLogin');
		Route::get('getCategorias', 'ApiController@getCategorias');
		Route::get('getEventos', 'ApiController@getEventos');
		Route::get('getUsuarios', 'ApiController@getUsuarios');
		Route::get('getAllCategorias', 'ApiController@getAllCategorias');
		Route::get('documentos/getCategorias', 'ApiController@getCategoriasDocumentos');
		Route::get('{categoria}/getTickets', 'ApiController@getTickets')->where('categoria', '[0-9]+');;
		Route::get('getTicket/{ticket_id}', 'ApiController@getTicket');
		Route::get('{categoria}/getDocumentos', 'ApiController@getDocumentos')->where('categoria', '[0-9]+');;
		Route::get('getDocumento/{id}','HomeController@getDocumento');
		Route::get('getUsuariosCategoria/{categoria_id}', 'ApiController@getUsuariosCategoria');
		Route::get('search', 'ApiController@busqueda');

		Route::get('getMisTickets', 'ApiController@getMisTickets');
		Route::get('getAllTickets', 'ApiController@getAllTickets');
		Route::get('getTicketsVencidos', 'ApiController@getTicketsVencidos');
		Route::get('getTicketsAbiertos', 'ApiController@getTicketsAbiertos');

		Route::post('addTicket', 'TicketsController@storeAjax');
		Route::post('addAlerta', 'ApiController@addAlerta');
		Route::put('editTicket/{id}', 'ApiController@editTicket');
		Route::post('addComentario/{ticket_id}','ApiController@addComentarioTicket');
		Route::delete('deleteComenarioTicket/{comentario_id}','ApiController@deleteComentarioTicket');

		Route::get('getEncryptedFile/ticket/{id}/{clave}','HomeController@getFileTicketEncrypted');
		Route::get('getEncryptedFile/comentario/{id}/{clave}','HomeController@getFileComentarioTicketEncrypted');



		Route::get('getNotificaciones/', 'ApiController@getNotificaciones');
		// Route::get('notificaciones/leer-todas', 'ApiController@NotificacionesLeerTodas');
		Route::get('notificacion/{id}/leida', 'ApiController@Leernotificacion');
		Route::get('notificacion/{id}/noleida', 'ApiController@NoLeernotificacion');

		Route::get('getPacientes','ApiController@getPacientes');
		Route::get('getCaso/{id}','ApiController@getCaso');
		Route::get('getIncapacidad/{id}','ApiController@getIncapacidad');
		Route::get('iniciar-seguimiento/{id}', 'ApiController@iniciarSeguimiento');

		Route::get('auth', ['middleware' => 'auth.basic.once', 'uses' => 'ApiController@doLogin']);
		
		Route::resource('dispositivos', 'DispositivosController');

		Route::get('getParameters', 'ApiController@getParameters');


		Route::group(['prefix' => 'clientes'], function () {
			Route::get('getTickets','ClientesController@ApiGetTickets');
			Route::get('getInvoices', 'ClientesController@ApigetInvoices');
		});		
	});


	Route::get('api/getUser/{id}','ApiController@getUser');
	Route::get('api/getUsers','ApiController@getUsers');

	//REST API
	Route::group(['prefix' => 'api', 'middleware' => ['auth.basic.once']], function(){
		Route::resource('documentos', 'Api\DocumentosController');
		Route::resource('categorias-documentos', 'Api\CategoriasDocumentosController');
		Route::resource('users', 'Api\UsersController');
		
		Route::resource('proceso-masivo-entrada', 'Api\ProcesoMasivoClienteController');
		Route::put('proceso-masivo-entrada','Api\ProcesoMasivoClienteController@updateMany');
		Route::get('proceso-masivo-fields','ProcesoMasivoController@getFields');

		Route::resource('tickets', 'Api\TicketsController');
		Route::put('tickets','Api\TicketsController@updateMany');
		
		Route::resource('clientes', 'Api\ClientesController');
		Route::resource('proveedores', 'Api\ProveedoresController');
		Route::resource('productos', 'Api\ProductosController');
		Route::resource('categorias-productos', 'Api\CategoriasProductosController');
		Route::resource('pedidos', 'Api\PedidosController');
		Route::resource('images', 'Api\ImagesController');
	});




	