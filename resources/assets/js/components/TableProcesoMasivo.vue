<template>
    <span class="pull-left">
        <button v-on:click="generarTicketMasivo()" class="btn btn-sm btn-warning">Generar Ticket Para Poderdantes Seleccionados</button>
        <button v-on:click="updateMany()" data-toggle="modal" href='#modal-update' class="btn btn-sm btn-success">Actualizar Datos para  Poderdantes seleccionados</button>
    </span>
    <span class="pull-right">
        <button class="btn btn-xs" v-on:click="changeStatus('rgba(')">Leyenda:</button>
        <button class="btn btn-xs" v-on:click="changeStatus('rgba(0,0,255,0.3)')" style="background:rgba(0,0,255,0.3);  padding: 2px;">PROCEDIMIENTO VÍA GUBERNATIVA</button>
        <button class="btn btn-xs" v-on:click="changeStatus('rgba(0,255,0,0.3)')" style="background:rgba(0,255,0,0.3);  padding: 2px;">ETAPA EXTRAJUDICIAL</button>
        <button class="btn btn-xs" v-on:click="changeStatus('rgba(255,255,0,0.3)')" style="background:rgba(255,255,0,0.3); padding: 2px;">PROCESO ORDINARIO ADMINISTRATIVO LABORAL</button>
    </span>
    <div class="clearfix"></div>
    <div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-condensed table-hover ">
            <caption>Entradas 
                <button v-on:click="selectAll()" class="btn btn-xs">Todos</button>
                <button v-on:click="unselectAll()" class="btn btn-xs">Ninguno</button>
                <button v-on:click="setEditableAll()" class="btn btn-xs">Editar</button>
            </caption>
            <thead>
                <tr>
                    <th><i class="fa fa-check-square"></i></th>
                    <th>CLIENTE</th>
                    <th>ENTIDAD </th>
                    <th>FECHA AGREGADO</th>
                    <th v-for="(field, attr) in fields" v-if="attr.type != 'manual'" v-show="attr.background.indexOf(colorFilter) != -1" v-bind:style="{background:attr.background}">
                        {{ attr.label != undefined ? attr.label : field | spaceCase | uppercase  }} 
                    </th>
                    <th>Acción</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="( index, entrada) in entradas">
                    <td><div style="height: 55px;">
                        <input type="checkbox" v-model="entrada.selected">
                    </div></td>
                    
                    <td>
                        <div style="width:220px !important">                            
                            <a href="{{this.url + '/admin/clientes/' + entrada.cliente.id + '/edit' }}">{{ entrada.cliente.full_name_cedula }}</a> <br> 
                            <button class="btn btn-xs btn-success hidden-print" v-on:click="upload(index,entrada)">Actualizar</button>
                           <button class="btn btn-xs btn-primary hidden-print" v-on:click="generarTicket(entrada)">Generar Ticket </button>
                        </div>
                   </td>
                    
                    <td style="width: 180px !important">             
                        <span  v-if="!entrada.editable">{{entrada.entidad ? entrada.entidad.name :  '-' }}</span>         
                       <v-select name="entrada[entidad_id]" v-if="entrada.editable" :value.sync="entrada.entidad" :options="entidades" label="name"><v-select>
                    </td>

                    <td >
                        <span  v-if="!entrada.editable">{{ entrada.fecha_agregado | moment }}</span>
                        <input v-if="entrada.editable" type="date" class="form-control input-sm" name="entrada[fecha_agregado]" v-model="entrada.fecha_agregado" value="{{entrada.fecha_agregado.substring(0,10)}}">
                    </td >

                    <td v-for="(field, attr) in fields" track-by="$index" v-if="attr.type != 'manual'" v-show="attr.background.indexOf(colorFilter)!=-1" data-toggle="tooltip" title="{{entrada.cliente.full_name}} - {{attr.label != undefined ? attr.label : field | spaceCase|uppercase}}"> 

                        <div v-if="!entrada.editable" style="width: 150px !important;">
                            <span v-if="attr.type== 'text' || attr.type== 'select'">{{entrada[field]}}</span>
                            <span v-if="attr.type== 'date'">{{ entrada[field] | moment }}</span>
                        </div>

                        <div v-show="entrada.editable" style="width: 180px !important;">
                            <input v-if="attr.type == 'text'" class="form-control input-sm" type="text" name="entrada[{{index}}][{{field}}]" v-model="entrada[field]">
                            
                            <input v-if="attr.type == 'date'" class="form-control input-sm" type="date" name="entrada[{{index}}][{{field}}]" v-model="entrada[field]">

                            <select v-if="attr.type == 'select'" class="form-control input-sm" name="entrada[{{index}}][{{field}}]" v-model="entrada[field]">
                                <option v-for="(key,text) in attr.options" value="{{key}}">{{text}}</option>
                            </select>
                        </div>

                    </td>
                    <td>
                        <button class="btn btn-xs btn-success hidden-print" v-on:click="upload(index,entrada)">Actualizar</button>
                    </td>
                </tr>
            </tbody>            

            <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th v-for="(field, attr) in fields" v-if="attr.type != 'manual'" v-show="attr.background.indexOf(colorFilter) != -1"></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="modal fade" id="modal-update">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Actualizar Poderdantes Seleccionados</h4>
                </div>
                <div class="modal-body">
                    <h4>Seleccione los campos a editar de manera masiva:</h4>
                        <v-select :value.sync="seleccionados" :options="clientes" label="full_name_cedula" multiple>
                        </v-select>
                    <br> <br>

                    <div class="col-md-6">
                        <select v-model="fieldUpdate" class="form-control">
                            <option v-for="(field, attr) in fields" v-if="attr.type != 'manual'" value="{{field}}">{{ attr.label != undefined ? attr.label : field | spaceCase | uppercase}}</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input v-model="valueUpdate" class="form-control" type="{{ fields[fieldUpdate].type }}">
                    </div>
                    <br>
                    <br>

                    <div class="col-md-6">
                        <select v-model="fieldUpdate2" class="form-control">
                            <option v-for="(field, attr) in fields" v-if="attr.type != 'manual'" value="{{field}}">{{ attr.label != undefined ? attr.label : field | spaceCase | uppercase}}</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input v-model="valueUpdate2" class="form-control" type="{{ fields[fieldUpdate2].type }}">
                    </div>
                    <br>
                    <br>

                    <div class="col-md-6">
                        <select v-model="fieldUpdate3" class="form-control">
                            <option v-for="(field, attr) in fields" v-if="attr.type != 'manual'" value="{{field}}">{{ attr.label != undefined ? attr.label : field | spaceCase | uppercase}}</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input v-model="valueUpdate3" class="form-control" type="{{ fields[fieldUpdate3].type }}">
                    </div>

                    <br>
                    <br>

                </div>
                <div class="modal-footer">
                    <button  class="btn btn-default"  data-dismiss="modal">Cerrar</button>
                    <button v-on:click="updateManySave()" data-dismiss="modal" type="button" class="btn btn-primary">Actualizar</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import vSelect from "vue-select"
    import $ from "jQuery"
  export default {
    components: {vSelect},
    ready() {
    },

    methods : {
        upload: function(index,entrada){
            entrada.entidad_id = entrada.entidad.id;
            if (!entrada.editable) {
                entrada.editable = !entrada.editable;
                this.entradas.$set(index, JSON.parse(JSON.stringify(entrada)));
                return;
            }
            entrada.editable = !entrada.editable;
            console.log(entrada);
            this.entradas.$set(index, JSON.parse(JSON.stringify(entrada)));
            $.ajax({
               url: this.url + "/api/proceso-masivo-entrada/" + entrada.id,
               type: 'PUT',
               data: entrada,
               success: function(data) {
                 new PNotify({
                     title: "Listo!",
                     text:  "Entrada Actualizada",
                     type: 'success',
                             // delay: 4,
                             desktop: {
                                 desktop: false
                             }
                         });
                },
                 error: function (Err){
                    new PNotify({
                        title: "Error!",
                        text:  "Entrada Error",
                        type: 'error',
                                    // delay: 4,
                                    desktop: {
                                        desktop: false
                                    }
                                });
                }
                });
        },
        changeStatus(backg){
            this.colorFilter = backg;
        },
        generarTicket(entrada){
            window.location.href= this.url + "/agregar-ticket" +"?titulo=Caso de "+ entrada.cliente.full_name + " en proceso " + this.proceso.titulo + "&cliente_id="+ entrada.cliente.id + "&" + $.param({"contenido_array" :entrada}) +"  &ckeditor_height=400px&contenido_titulo=" + entrada.cliente.full_name + " - " + entrada.entidad.name  + " - " + this.proceso.titulo + "&contenido_link=" + this.url + "/ver-procesoMasivo/" + this.proceso.id;
        },
        generarTicketMasivo(){
            var seleccionados = [];
            this.entradas.forEach((entrada)=>{
                if(entrada.selected)
                {
                    console.log(entrada);
                    seleccionados.push(entrada.cliente.id);
                }
            });

            window.location.href= this.url + '/tickets-procesos-masivos-clientes?clientes_id=' +  seleccionados.join() + "&proceso_masivo_id="+ this.proceso.id;   
        },

        updateMany(){
            var seleccionados = [];
            this.entradas.forEach((entrada)=>{
                if(entrada.selected)
                    seleccionados.push(entrada.cliente);
            });
            this.seleccionados = seleccionados;
        },

        updateManySave(){
            var ids = [];
            this.seleccionados.forEach((cliente)=>{
                ids.push(cliente.id)
            });
            var data = { ids:ids };                
            data[this.fieldUpdate] = this.valueUpdate;
            
            if(this.valueUpdate2 != "")
                data[this.fieldUpdate2] = this.valueUpdate2;

            if(this.valueUpdate3 != "")
                data[this.fieldUpdate3] = this.valueUpdate3;

            console.log(data);
            $.ajax({
                url: this.url + "/api/proceso-masivo-entrada",
                type: 'PUT',
                data: data
            })
            .done(function(results) {
                window.location.reload();
            })
            .fail(function(e) {
                alert("ocurrio un error al guardar");
            });
        },
        selectAll(){
            for (var i = 0; i < this.entradas.length; i++) {
                Vue.set(this.entradas[i], 'selected', true)
            }
        },
        unselectAll(){
            for (var i = 0; i < this.entradas.length; i++) {
                Vue.set(this.entradas[i], 'selected', false)
            }

        },
        setEditableAll(){
            for (var i = 0; i < this.entradas.length; i++) {
                Vue.set(this.entradas[i], 'editable', true)
            }

        },
    },

data() { return {
    editable: false,
    colorFilter: 'rgba',
    fieldUpdate: "fecha_agregado",
    valueUpdate: "",
    fieldUpdate2: "fecha_agregado",
    valueUpdate2: "",
    fieldUpdate3: "fecha_agregado",
    valueUpdate3: "",
    seleccionados: [],
}},

props: ['entradas','fields', 'url', 'entidades','proceso', 'clientes'],

};
</script>
