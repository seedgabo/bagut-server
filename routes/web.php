<?php
use Carbon\Carbon;
Jenssegers\Date\Date::setLocale('es');
Carbon::setLocale('es');


Route::auth();
Route::get('logout', 'HomeController@logout');

Route::group(['middleware' => 'web'], function () {

	//Home
	Route::group([], function() {
		Route::get('/', 'HomeController@index');
		Route::get('/home', 'HomeController@home');
		Route::any('/menu',   ['middleware' => [], 'uses' =>'HomeController@menu']);
		Route::any('/calendar',   ['middleware' => ['auth'], 'uses' =>'HomeController@verCalendario']);

		Route::get('profile' ,['middleware' => ['auth'], 'uses' => "HomeController@profile"]);
		Route::put('profile' ,['middleware' => ['auth'], 'uses' => "HomeController@profileUpdate"]);

		Route::any('agregar-ticket', ['middleware' => ['auth'], 'uses' =>'HomeController@ticketAgregar']);
		Route::get('/ticket',   ['middleware' => ['auth'], 'uses' =>'HomeController@tickets']);
		Route::get('/mis-tickets',   ['middleware' => ['auth'], 'uses' =>'HomeController@misTickets']);
		Route::get('/tickets/todos',   ['middleware' => ['auth'], 'uses' =>'HomeController@Todostickets']);
		Route::get('/tickets/categoria/{categoria}',   ['middleware' => ['auth'], 'uses' =>'HomeController@porCategoria']);
		Route::get('/ticket/ver/{id}',   ['middleware' => ['auth'], 'uses' =>'HomeController@ticketVer']);
		Route::get('ticket/eliminar/{id}', ['middleware' => ['auth'], 'uses' =>'HomeController@ticketEliminar']);
		Route::get('ticket/excel/{id}', ['middleware' => ['auth'], 'uses' =>'HomeController@ticketExcel']);
		Route::put('editar-ticket/{id}', ['middleware' => ['auth'], 'uses' => 'HomeController@ticketEditar']);

		Route::get('ver-documentos', ['midleware' => ['auth'], 'uses' => 'HomeController@listarCategorias']);
		Route::get('ver-documentos/{categoria}', ['midleware' => ['auth'], 'uses' => 'HomeController@listarDocumentos']);


		Route::post('agregar-alerta', ['midleware' => ['auth'], 'uses' => 'HomeController@agregarAlerta']);

		Route::get('archivo/{id}',['midleware' => ['auth'], 'uses' => 'HomeController@descargarArchivo']);

		Route::get('getDocumento/{id}', ['middleware' => ['auth'], 'uses' =>'HomeController@getDocumento']);
		Route::get('getEncryptedFile/ticket/{id}/{clave}', ['middleware' => ['auth'], 'uses' =>'HomeController@getFileTicketEncrypted']);
		Route::get('getEncryptedFile/comentario/{id}/{clave}', ['middleware' => ['auth'], 'uses' =>'HomeController@getFileComentarioTicketEncrypted']);

		Route::get('notificaciones/', ['middleware' => ['auth'], 'uses' =>'HomeController@verNotificaciones']);
		Route::get('notificacion/{id}', ['middleware' => ['auth'], 'uses' =>'HomeController@verNotificacion']);
		Route::get('notificaciones/leer-todas', ['middleware' => ['auth'], 'uses' =>'HomeController@NotificacionesLeerTodas']);
		Route::get('notificacion/{id}/leida', ['middleware' => ['auth'], 'uses' =>'HomeController@Leernotificacion']);
		Route::get('notificacion/{id}/noleida', ['middleware' => ['auth'], 'uses' =>'HomeController@NoLeernotificacion']);

		Route::get('crear-atajo', 'AdminController@agregarAccesoRapido');
		Route::get('eliminar-atajo/{id}', 'AdminController@eliminarAccesoRapido');
		Route::get('busqueda',['middleware' => ['auth'], 'uses' => "HomeController@busqueda"]);
	});

	//Miscelaneo
	Route::group(['middleware' =>['auth']], function(){

		Route::get('getListaCategoriasDocumentos', 'AdminController@getListaCategoriasDocumentos');
		Route::get('getListaCategoriasTickets', 'AdminController@getListaCategoriasTickets');
		Route::get('emitir-alerta','AdminController@emitirAlerta');

		Route::get('excel/load', 'UploadController@loadExcel');
		Route::post('excel/load', 'UploadController@seeExcel');
	});


	if(config("modulos.clientes")){
		Route::get('ver-cliente/{id}', 'ClientesController@verCliente');
		Route::get('ver-proceso/{id}', 'ClientesController@verProceso');
		Route::get('ver-consulta/{id}', 'ClientesController@verConsulta');
		Route::get('ver-invoice/{id}', 'ClientesController@verFactura');
		Route::get('clientes-todos', 'ClientesController@ClientesTodos');
	}

	if (config("modulos.proveedores")){
		Route::get('ver-proveedor/{proveedor}', 'ProveedoresController@verProveedor');
		Route::get('ver-invoice-proveedor/{invoice_id}', 'ProveedoresController@verInvoiceProveedor');
	}

	if (config("modulos.procesos_masivos")){
		Route::get('ver-procesoMasivo/{id}', 'ProcesoMasivoController@verProceso');
		Route::get('ver-documentos-procesoMasivo/{id}', 'ProcesoMasivoController@verDocumentosProceso');
		Route::get('ver-documentos-clientes-procesosMasivos/{id}', 'ProcesoMasivoController@verDocumentosClienteProcesos');
		Route::get('add-cliente-to-proceso-masivo','ProcesoMasivoController@addClientetoProceso');
		Route::post('admin/procesos_masivos/cargar-archivo', 'ProcesoMasivoController@CargarArchivosProcesosMasivos');
		Route::post('admin/procesos_masivos/cargar-archivo/{cliente_id}/{proceso_id}', 'ProcesoMasivoController@CargarArchivosProcesosMasivosCliente');
	}

	if (config("modulos.pedidos")) {
		Route::get('ver-producto/{producto}', 'ProductoController@verProducto');
		Route::get('ver-pedido/{pedido}', 'PedidoController@VerPedido');
		Route::get('ver-combo/{combo_id}', 'ProductoController@verCombo');
		Route::get('confirmacion-pedido/{pedido_id}','PedidoController@confirmarPedido');
	}


	// Modulo de Clientes Pedidos
	Route::get("administrar-productos-clientes/{cliente_cod}","AdministracionController@administrarProductos");
	

	// Cargada de Archivos
	Route::post('cargar-archivo', 'AdminController@cargarArchivosMasivos');
	Route::post('admin/clientes/cargar-archivo', 'AdminController@CargarArchivosClientes');
});


Route::get('tickets-procesos-masivos-clientes', 'HomeController@ticketsconclientes');

Route::get('impersonate/{id}','HomeController@impersonate');

Route::any('test/{aux?}', 'TestController@test');
Route::any('test2/{aux?}', 'TestController@test2');
Route::any('test3/{aux?}', 'TestController@test3');
Route::any('test4/{aux?}', 'TestController@test4');
Route::any('test5/{aux?}', 'TestController@test5');
Route::any('test6/{aux?}', 'TestController@test6');
Route::any('test7/{aux?}', 'TestController@test7');
Route::any('test8/{aux?}', 'TestController@test8');
Route::any('test9/{aux?}', 'TestController@test9');
Route::any('test10/{aux?}', 'TestController@test10');

Route::any('pages/{subs?}', ['uses' => 'PageController@index'])->where(['subs' => '.*']);
