<?php 

Route::group(['middleware' => 'web'], function () {

	//Ajax
	Route::group(['prefix' => 'ajax'], function() {
		Route::get('/tickets/restore/{id}','AjaxController@restoreTicket');
		Route::any('/setEstadoTicket/{id}' , 'AjaxController@setEstadoTicket');
		Route::any('/setInvitadosTickets/{id}' , 'AjaxController@setInvitadosTickets');
		Route::any('/addComentarioTicket' , 'AjaxController@addComentarioTicket');
		Route::any('/editComentarioTicket/{id}' , 'AjaxController@editComentarioTicket');
		Route::any('/deleteComentarioTicket/{id}' , 'AjaxController@deleteComentarioTicket');
		Route::any('/setGuardianTicket/{id}' , 'AjaxController@setGuardianTicket');
		Route::any('/setVencimiento/{id}' , 'AjaxController@setVencimientoTicket');
		Route::any('/getUsersbyCategoria' , 'AjaxController@getUsersbyCategoria');
		Route::any('/getClientsbyProcesosMasivos' , 'AjaxController@getClientsbyProcesosMasivos');
		Route::get('notificaciones/read-all', 'AjaxController@leerTodasNotificaciones');
		Route::any('/eliminar-archivo/{id}','AjaxController@eliminarArchivo');
		Route::any('/email' , 'AjaxController@email');
		Route::any('/query-clientes' , 'AjaxController@queryClientes');
	});

	//Recuros
	Route::group(['middleware' =>['auth']], function() {

		Route::resource('Usuarios', 'UsuarioController');
		Route::get('Usuarios/delete/{id}', [
			'as' => 'usuario.delete',
			'uses' => 'UsuarioController@destroy'
			]);

		Route::resource("tickets", "TicketsController");
		Route::get('tickets/delete/{id}', [
			'as' => 'tickets.delete',
			'uses' => 'TicketsController@destroy',
			]);

		Route::resource("categoriasTickets", "CategoriasTicketsController");
		Route::get('categoriasTickets/delete/{id}', [
			'as' => 'categoriasTickets.delete',
			'uses' => 'CategoriasTicketsController@destroy',
			]);

		Route::resource('documentos', 'DocumentosController');
		Route::get('Documentos/delete/{id}', [
			'as' => 'documentos.delete',
			'uses' => 'DocumentosController@destroy',
			]);
	});

	//Select2 Querys
	Route::group(['prefix' => 'select2'], function() {
		Route::get('/clientes' , 'Select2Controller@clientes');
		Route::get('/users' , 'Select2Controller@users');
		Route::get('/productos' , 'Select2Controller@productos');
	});


});