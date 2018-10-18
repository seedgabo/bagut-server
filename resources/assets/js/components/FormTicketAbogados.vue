<template>
    <div class="form-group">
        <label for="cliente_id">Cliente:</label>
        <select name="cliente_id" class="form-control chosen" v-select="cliente_selected" v-model="cliente_selected">
            <!-- <option value="">--</option> -->
            <option value="{{ cliente.id }}" v-for="cliente in clientes">{{cliente.nombres}}</option>
        </select>
        <small class="text-danger"></small>
    </div>

    <div class="form-group" v-if="cliente_selected != null && cliente_selected != ''">
        <label for="tipo">Tipo de Caso:</label>
        <select name="tipo" class="form-control" v-model="tipo_selected">
            <option value="consulta"> Consulta </option>
            <option value="proceso"> Proceso </option>
        </select>
    </div>

    <div v-if="cliente_selected != null && cliente_selected != '' && tipo_selected != null && tipo_selected != '' && tipo_selected == 'proceso'">
        <div class="form-group">
            <label for="proceso[radicado]">Radicado</label>
            <input type="text" name="proceso[radicado]" class="form-control">
        </div>
        <div class="form-group">
            <label for="proceso[juzgado_instancia_1]">Primera instancia</label>
            <textarea name="proceso[juzgado_instancia_1]" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="proceso[juzgado_instancia_2]">Segunda instancia</label>
            <textarea name="proceso[juzgado_instancia_2]" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="proceso[fecha_proceso]">Fecha Proceso</label> 
            <input type="date" name="proceso[fecha_proceso]" class="form-control datepicker">
        </div>
        <div class="form-group">
            <label for="proceso[demandante]">Demandante</label> 
            <input type="text" name="proceso[demandante]" class="form-control datepicker">
        </div>
        <div class="form-group">
            <label for="proceso[demandado]">Demandado</label> 
            <input type="text" name="proceso[demandado]" class="form-control datepicker">
        </div>
    </div>


    <div v-if="cliente_selected != null && cliente_selected != '' && tipo_selected != null && tipo_selected != '' && tipo_selected == 'consulta'">
        <div class="form-group">
            <label for="consulta[fecha_consulta]">Fecha de la Consulta</label>
            <input type="date" name="consulta[fecha_consulta]" class="form-control">
        </div>
        <div class="form-group">
            <label for="consulta[consulta]">Consulta*</label>
            <textarea name="consulta[consulta]" class="form-control" required="required"></textarea>
        </div>
        <div class="form-group">
            <label for="consulta[respuesta]">Respuesta</label>
            <textarea name="consulta[respuesta]" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="consulta[detalles]">Detalles</label>
            <textarea name="consulta[detalles]" class="form-control"></textarea>
        </div>
    </div>

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
    }},

    props: ['clientes', 'tipo_selected', 'cliente_selected'],


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
