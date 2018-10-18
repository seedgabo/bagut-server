<template>
  <main class="grey lighten-4">

      <div class="container">
           <form action="" method="POST" class="form-inline" role="form">

             <div class="form-group">
              <label class="sr-only" for=""><i class="fa fa-search"></i></label>
              <input type="search" class="form-control" id="" placeholder="Buscar" v-model="query">
             </div>

              <div class="form-group">
              <label for="filter">Filtrar</label>
                <select v-model="estado_query" class="form-control" id="filter">
                  <option value=""  selected>Todos</option>
                  <option value="abierto"  class="left circle">Abiertos</option>
                  <option value="vencido" class="left circle">Vencidos</option>
                  <option value="en curso" class="left circle">En curso</option>
                </select>
              </div>

              <div class="form-group">
                <label for="order">Orden</label>
                <select v-model="order" class="form-control" id="order">
                  <option value="estado"  selected>Estado </option>
                  <option value="created_at"  class="left circle">Fecha</option>
                  <option value="titulo" class="left circle">Titulo</option>
                  <option value="guardian.nombre" class="left circle">Responsable</option>
                </select>
              </div>
            </form>
          <br>
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-4" v-for="ticket in tickets | filterBy query | filterBy estado_query  in 'estado' | orderBy order " transition="fade" >
                  <div class="box" 
                  v-bind:class="ticket.estado  ==  'completado' ? 'box-success' : 
                  ticket.estado  ==  'abierto' ? 'box-info' : 
                  ticket.estado  ==  'en curso' ? 'box-warning' :
                  ticket.estado  ==  'vencido' ? 'box-danger' : 
                  'box-default'">
                    <div class="box-header with-border">
                        <h3 class="box-title text-center">
                          {{ticket.titulo}}
                        </h3>
                    </div>
                      <div class="box-body">
                        <p><b>Estado:</b> {{ticket.estado}}</p>                     
                                   
                        <p class="text-success"><b>Fecha: </b>{{ new Date(ticket.created_at) | moment "D/MM/YY h:mm:ss a" }}</p>
                        <p class="text-danger"><b>Vence: </b>{{  new Date(ticket.vencimiento) | moment "D/MM/YY h:mm:ss a" }}</p>
                        
                        <a href="clientes/ticket/{{ticket.id}}" class="btn btn-primary btn-outlined btn-block">Ver Caso</a>
                      </div>
                      <div class="box-footer">
                          <p v-html="ticket.contenido"></p>
                          <a class="text-primary pull-right"><small>{{ ticket.guardian.nombre }} </small></a>
                      </div>
                  </div>
            </div>
        </div>    
      </div>
      <style type="text/css" media="screen">
        .fade-transition {
            transition: all .5s ease;
        }
        .fade-enter{
          opacity: 0;
          margin-top: 500px;
        }
        .fade-leave{
            opacity: 0;
            margin-top: 500px;
        }
        .list-complete-item {
          transition: all 1s;
          display: inline-block;
          margin-right: 10px;
        }
        .list-complete-enter, .list-complete-leave-active {
          opacity: 0;
          transform: translateY(30px);
        }
        .list-complete-leave-active {
          position: absolute;
        }
        </style>
  </main>

</template>

<script>
  export default {

    /**
     * Llamar el socket para cargar los contactos conectados
     * @return null 
     */
    ready() {
    },

    methods : {
    },

    data() { return {
            columns: 6,
            query: "",
            img: '../public/img/logo-newton.png',
            estado_query : "",
            order: ""
    }},

    props : ['tickets']

  };
</script>
