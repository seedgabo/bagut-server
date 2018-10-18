<link href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css" rel="stylesheet" type="text/css" />

<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js" type="text/javascript"></script>

<script src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/responsive/2.1.0/js/responsive.bootstrap.min.js" type="text/javascript"></script>


<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.bootstrap.min.js" type="text/javascript"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js" type="text/javascript"></script>
<script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js" type="text/javascript"></script>
<script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js" type="text/javascript"></script>
<script src="//cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js" type="text/javascript"></script>
<script src="//cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js" type="text/javascript"></script>
<script src="//cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js" type="text/javascript"></script>
 <script>
  	var table;    
 	$(document).ready(function(){
      $.ajaxSetup({
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
      });
      

      $('.datatable tfoot th').each( function () {
              var title = $(this).text();
              $(this).html( '<input type="search" style="width: 100%;" class="form-control input-sm"/>' );
      } );
      table = $('.datatable').DataTable({
          "lengthMenu": [[10, 25, 50,100, -1], [10, 25, 50,100, "Todos"]],
          "pageLength": 50,
          // responsive: true,
          "language": {
              "sProcessing":     "Procesando...",
              "sLengthMenu":     "Mostrar _MENU_ registros",
              "sZeroRecords":    "No se encontraron resultados",
              "sEmptyTable":     "Ningún dato disponible en esta tabla",
              "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
              "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
              "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
              "sInfoPostFix":    "",
              "sSearch":         "Buscar:",
              "sUrl":            "",
              "sInfoThousands":  ",",
              "sLoadingRecords": "Cargando...",
              "oPaginate": {
                  "sFirst":    "Primero",
                  "sLast":     "Último",
                  "sNext":     "Siguiente",
                  "sPrevious": "Anterior"
              },
              "oAria": {
                  "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                  "sSortDescending": ": Activar para ordenar la columna de manera descendente"
              },
              "decimal": ",",
              "thousands": ".",
          },
          colReorder: true,
           dom: '<"p-l-0 col-md-6"l>B<"p-r-0 col-md-6"f>rt<"col-md-6 p-l-0"i><"col-md-6 p-r-0"p>',
          buttons: [
              {
                  extend:    'copyHtml5',
                  text:      '<i class="fa fa-files-o text-info"></i> Copiar',
                  titleAttr: 'Copiar',
                  className: "btn btn-xs btn-default",
                  exportOptions: {
                      columns: ':visible'
                  }
              },
              {
                  extend:    'excelHtml5',
                  text:      '<i class="fa fa-file-excel-o text-success"></i> Excel',
                  titleAttr: 'Excel',
                  className: "btn btn-xs btn-default",
                  exportOptions: {
                      columns: ':visible'
                  }
              },
              {
                  extend:    'print',
                  text:      '<i class="fa fa-print text-warning"></i> Imprimir',
                  titleAttr: 'Imprimir',
                  className: "btn btn-xs btn-default",                        
                  exportOptions: {
                      columns: ':visible'
                  }
              },
              {
                  extend:    'pdfHtml5',
                  text:      '<i class="fa fa-file-pdf-o text-danger"></i> Pdf',
                  titleAttr: 'PDF',
                  className: "btn btn-xs btn-default",
                  exportOptions: {
                      columns: ':visible'
                  },
                  orientation: 'landscape'
              },
              {
                  extend:    'colvis',
                  text:      '<i class="fa fa-eye text-primary"></i> Mostrar/Ocultar Columnas',
                  className: "btn btn-xs btn-default",
                  titleAttr: 'Mostrar/Ocultar Columnas'
              }
          ]
      });
      
      table.columns().every( function () {
          var that = this;
          $( 'input', this.footer() ).on( 'keyup change', function () {
              if ( that.search() !== this.value ) {
                  that
                      .search( this.value )
                      .draw();
              }
          } );
      } );

  });
 </script>