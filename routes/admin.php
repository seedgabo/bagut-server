<?php 

Route::group(['middleware' => 'web'], function(){



	//Admin
	Route::group(['prefix' => 'admin', 'middleware' => ['isAdmin']], function(){
		Route::get('/', function(){ return redirect('/admin/dashboard');});
		Route::get('dashboard', 'DashboardController@dashboard');
		CRUD::resource('usuarios', 'Admin\UsuariosCrudController');
		CRUD::resource('tickets', 'Admin\TicketsCrudController');
		CRUD::resource('categorias', 'Admin\CategoriasCrudController');
		CRUD::resource('files', 'Admin\ArchivosCrudController');

		CRUD::resource('documentos', 'Admin\DocumentoCrudController');
		CRUD::resource('categoriadocumentos', 'Admin\CategoriaDocumentosCrudController');
		Route::get('eliminar-documento/{id}','AdminController@eliminarDocumento');
		Route::get('categorias-masivas/{categoria}', 'AdminController@categoriasUsuarios');
		Route::put('categorias-masivas/{categoria}', 'AdminController@agregarmasivamente');
		Route::get('archivos-masivos/', 'AdminController@archivosMasivos');

		CRUD::resource('alertas', 'Admin\AlertaCrudController');


		Route::get('email/departamento', 'AdminController@emailPorDepartamento');
		Route::get('email/puesto', 'AdminController@emailPorPuesto');
		Route::get('email/usuarios', 'AdminController@emailAUsuarios');
		Route::get('email/usuarios/departamento', 'AdminController@emailAUsuariosPorDepartamento');


		Route::get('crear-atajo', 'AdminController@agregarAccesoRapido');
		Route::get('eliminar-atajo/{id}', 'AdminController@eliminarAccesoRapido');
	});

	//Clinica
	Route::group(['prefix' => 'admin', 'middleware' => ['isAdmin']], function(){
		// Historias Clinicas
		CRUD::resource('pacientes', 'Admin\PacienteCrudController');
		CRUD::resource('casos-medicos', 'Admin\Casos_medicosCrudController');
		CRUD::resource('historias_clinicas', 'Admin\HistoriaClinicaCrudController');
		CRUD::resource('recomendaciones', 'Admin\RecomendacionCrudController');
		CRUD::resource('incapacidades', 'Admin\IncapacidadCrudController');
		CRUD::resource('puestos', 'Admin\PuestoCrudController');
		CRUD::resource('fondos-de-pensiones', 'Admin\FondosDePensionesCrudController');
		CRUD::resource('eps', 'Admin\EpsCrudController');
		CRUD::resource('arl', 'Admin\ArlCrudController');
		CRUD::resource('cie10', 'Admin\Cie10CrudController');


		Route::get('ver-paciente/{id}', 'ClinicaController@verPaciente');
		Route::get('ver-caso/{id}', 'ClinicaController@verCasoMedico');
		Route::get('ver-historia-clinica/{id}', 'ClinicaController@verHistoriaClinica');
		Route::get('ver-incapacidad/{id}', 'ClinicaController@verIncapacidad');
		Route::get('iniciar-seguimiento/{id}', 'ClinicaController@iniciarSeguimiento');
		Route::get('paciente/buscar', 'ClinicaController@buscarPaciente');
		
		Route::post('cargar-archivo/paciente/{id}','ClinicaController@cargarArchivoPaciente');
		Route::post('cargar-archivo/caso/{id}','ClinicaController@cargarArchivoCaso');
	});

	//Cientes
	Route::group(['prefix' => 'admin', 'middleware' => ['isAdmin']], function(){

		CRUD::resource('clientes', 'Admin\ClienteCrudController');
		CRUD::resource('facturas', 'Admin\InvoiceCrudController');

		CRUD::resource('procesos', 'Admin\ProcesoCrudController');
		CRUD::resource('consultas', 'Admin\ConsultaCrudController');

		Route::get('ver-cliente/{id}', 'ClientesController@verCliente');
		Route::get('ver-proceso/{id}', 'ClientesController@verProceso');
		Route::get('ver-consulta/{id}', 'ClientesController@verConsulta');
		Route::get('ver-invoice/{id}', 'ClientesController@verFactura');
		Route::get('ver-invoice/pdf/{id}', 'ClientesController@verFacturaPdf');

		Route::get('iniciar-seguimiento/proceso/{id}', 'ClientesController@iniciarSeguimientoProceso');
		Route::get('iniciar-seguimiento/consulta/{id}', 'ClientesController@iniciarSeguimientoConsulta');
		
		Route::get('cliente/buscar', 'ClientesController@buscarCliente');

		Route::get('cliente/archivos-masivos','ClientesController@ArchivosMasivosClientes');
		
		Route::post('cargar-archivo/cliente/{id}','ClientesController@cargarArchivoCliente');
		Route::post('cargar-archivo/proceso/{id}','ClientesController@cargarArchivoProceso');
		Route::post('cargar-archivo/consulta/{id}','ClientesController@cargarArchivoConsulta');
		
	});

	// Procesos Masivos
	Route::group(['prefix' => 'admin', 'middleware' => ['isAdmin']], function() {

		CRUD::resource('procesos-masivos', 'Admin\ProcesoMasivoCrudController');
		CRUD::resource('{proceso_masivo_id}/procesos-masivos-clientes', 'Admin\ProcesosMasivosClienteCrudController');
		CRUD::resource('entidades', 'Admin\EntidadCrudController');
		Route::get('proceso-masivo/archivos-masivos','ProcesoMasivoController@ArchivosMasivosClientes');
		Route::get('ver-procesoMasivo/{id}', 'ProcesoMasivoController@verProceso');
		Route::get('ver-documentos-procesoMasivo/{id}', 'ProcesoMasivoController@verDocumentosProceso');
		Route::get('ver-procesoMasivo/{id}/excel', 'ProcesoMasivoController@verProcesoExcel');
		Route::get('ver-documentos-clientes-procesosMasivos/{id}', 'ProcesoMasivoController@verDocumentosClienteProcesos');
	});

	// Evaluaciones
	Route::group(['prefix' => 'admin', 'middleware' => ['isAdmin']], function(){
		CRUD::resource('evaluaciones', 'Admin\EvaluacionCrudController');
		
		CRUD::resource('evaluaciones-proveedores', 'Admin\EvaluacionProveedorCrudController');
		Route::get('ver-evaluacion-proveedor/{evaluacion}', 'AdminController@verEvaluacionProveedor');


		CRUD::resource('vehiculos', 'Admin\VehiculoCrudController');
		CRUD::resource('evaluaciones-vehiculos', 'Admin\EvaluacionVehiculoCrudController');
		Route::get('ver-vehiculo/{vehiculo}', 'AdminController@verVehiculo');
		Route::get('ver-evaluacion-vehiculo/{evaluacion}', 'AdminController@verEvaluacionVehiculo');


		CRUD::resource('conductores', 'Admin\ConductorCrudController');
		CRUD::resource('evaluaciones-conductores', 'Admin\EvaluacionConductorCrudController');
		Route::get('ver-conductor/{conductor}', 'AdminController@verConductor');
		Route::get('ver-evaluacion-conductor/{evaluacion}', 'AdminController@verEvaluacionConductor');

		Route::post('cargar-archivo/proveedor/{id}','UploadController@cargarArchivoProveedor');
		Route::post('cargar-archivo/vehiculo/{id}','UploadController@cargarArchivoVehiculo');
		Route::post('cargar-archivo/conductor/{id}','UploadController@cargarArchivoConductor');

	});

	//Auditoria
	Route::group(['prefix' => 'admin/auditar','middleware' => ['isAdmin']], function(){
		Route::get('usuario/{user_id?}', 'AdminController@auditarUsuario');
		Route::get('resumen', 'AdminController@resumen');	
	});


	//Proveedores
	Route::group(['prefix' => 'admin', 'middleware' => ['isAdmin']], function(){
		CRUD::resource('proveedores', 'Admin\ProveedorCrudController');
		CRUD::resource('invoices-proveedores', 'Admin\InvoiceProveedorCrudController');
		
		Route::get('ver-proveedor/{proveedor}', 'ProveedoresController@verProveedor');
		Route::get('ver-invoiceProveedor/{invoice_id}', 'ProveedoresController@verInvoiceProveedor');

	});

	//Pedidos
	Route::group(['prefix' => 'admin', 'middleware' => ['isAdmin']], function(){
		CRUD::resource('productos', 'Admin\ProductoCrudController');
		CRUD::resource('pedidos', 'Admin\PedidoCrudController');
		CRUD::resource('categoriasproductos', 'Admin\CategoriaProductoCrudController');
		
		Route::get('ver-producto/{producto}', 'ProductoController@verProducto');
		Route::get('ver-producto/{producto}/imagenes', 'ProductoController@verProductoImagenes');
		Route::get('ver-pedido/{invoice_id}', 'PedidoController@verPedido');


		Route::post('producto/{producto}/uploadImage', 'ProductoController@uploadImage');
		Route::get('producto/{producto}/image/{image}/default', 'ProductoController@defaultImage');

	});


	


	//Reportes
	Route::group(['prefix' => 'admin/reportes','middleware' => ['isAdmin']], function(){
		Route::get('ticket/{ticket_id?}', 'ReporteController@ticket');
	});

});
