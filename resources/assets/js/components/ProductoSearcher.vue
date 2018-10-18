<template>

<div class="modal fade" id="modal-id">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Buscar Productos</h4>
            </div>
            <div class="modal-body">
                <div class="box box-primary ">    
                 <div class="box-header">
                     <div class="input-group">
                       <input type="text" class="form-control" placeholder="BUSCAR PRODUCTOS" v-model="query" v-on:keyup="search() | debounce 500">
                       <div class="input-group-btn">
                            <a class="btn btn-default" >
                           <i class="glyphicon glyphicon-search"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class=" box-body table-responsive">
                    <table class="table table-bordered table-stripped table-compact">
                        <thead>
                            <tr>
                                <th>Imagen</th>
                                <th>Referencia</th>
                                <th>Nombre</th>
                                <th>Stocks</th>
                                <th>Categoría</th>
                                <th>Precio</th>
                                <th>Agregar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in productos">
                                <td>{{item.image_html}}</td>
                                <td>{{item.referencia}}</td>
                                <td>{{item.name}}</td>
                                <td>{{item.stock}}</td>
                                <td>{{item.categoria ? item.categoria.nombre : 'Ninguna'}}</td>
                                <td>{{item.precio}}</td>
                                <td>
                                <div class="input-group">
                                        <input type="number" value="1" max="{{item.stock}}" v-model="item.cantidad_pedidos" class="form-control" style="width:70px;" form="null">
                                      <span class="input-group-btn">
                                        <a href="#!" v-on:click="addItem(item)" class="btn btn-primary" :disabled="item.cantidad_pedidos > item.stock"> <i class="fa fa-plus"></i></a>
                                      </span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>



    <div class="col-md-12">        
        <h4 class="text-center"> Items </h4>
        <div class="table-responsive">
            <table class="table table-bordered table-stripped table-compact">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Referencia</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Descuento</th>
                        <th>Cantidad</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(index,item) in items">
                        <input type="hidden" name="items[{{index}}][producto_id]" v-if="item.producto_id != undefined" v-model="item.producto_id">
                        <input type="hidden" name="items[{{index}}][producto_id]" v-else v-model="item.id">
                        <input type="hidden" name="items[{{index}}][iva]" v-model="item.iva">
                        <input type="hidden" name="items[{{index}}][cantidad_despachado]" v-model="item.cantidad_despachado">
                        <input type="hidden" name="items[{{index}}][fecha_entrega]" v-model="item.fecha_entrega">
                        <input type="hidden" name="items[{{index}}][fecha_envio]" v-model="item.fecha_envio">
                        <input type="hidden" name="items[{{index}}][fecha_pedido]" v-model="item.fecha_pedido">                   
                        <td>
                            <input type="hidden" class="form-control input-sm" name="items[{{index}}][image_id]" v-model="item.image_id">
                            {{item.image_html}}
                        </td>
                        <td>
                            <input type="hidden" class="form-control input-sm" name="items[{{index}}][referencia]" v-model="item.referencia">
                            {{item.referencia}}
                        </td>
                        <td>
                             <input type="hidden" class="form-control input-sm" name="items[{{index}}][name]" v-model="item.name">
                             {{item.name}}
                        </td>
                        <td>
                            <input style="width:100px;" type="number" class="form-control input-sm" name="items[{{index}}][precio]" v-model="item.precio" min="1">
                        </td>
                        <td>
                            <input style="width:100px;" type="number" class="form-control input-sm" name="items[{{index}}][descuento]" v-model="item.descuento" min="0" value="item.precio">
                        </td>
                        <td>
                            <input style="width:100px;" type="number" class="form-control input-sm" name="items[{{index}}][cantidad_pedidos]" v-model="item.cantidad_pedidos" min="1" max="item.stock">
                        </td>
                        <td><a href="#!" v-on:click="deleteItem(index)" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <a class="btn btn-primary pull-right" v-on:click="search()" data-toggle="modal" href='#modal-id'><i class="fa fa-plus"></i> </a>
    </div>
</template>

<script>
  export default {
    ready() {
        $(document).on("keypress", "form", function(event) { 
            return event.keyCode != 13;
        });
    },

    methods : {
        search: function(){
            $.ajax({
                url: this.url,
                data: {
                    orWhereLike:{
                        name: this.query,
                        referencia: this.query,

                    },
                    with:{
                        0: 'Categoria',
                        1: 'Image',
                    },
                    limit: 50
                },
            })
            .done((data) =>{
                Vue.set(this,'productos', data);
            })
            .fail((err)=> {
                console.error(err);
            });      
        },

        addItem(item){
            var index =this.items.findIndex((old)=>{
               return item.id == old.id;
           });
            if(index == -1){
                this.items.push(item);
                // Vue.set(this,'productos', []);
                // this.query = "";
            }else{
                alert("Item ya agregado");

            }
        },
        deleteItem(index){
            console.log(index);
            this.items.splice(index,1);
        },
},

data() { return {
    query: "",
}},

props: {productos:{ default: function(){return []} }, producto: {}, url:{} ,items: {default: function(){return []} }},


mounted: function () {
    var vm = this
    $(".chosen")
    .val(this.value)
          // init select2
          .select2({})
          // emit event on change.
          .on('change', function () {
            vm.$emit('input', this.value)
        })
      },
      watch: {
        value: function (value) {
          // update value
          $(".chosen").select2('val', value)
      }
  }

};
</script>
