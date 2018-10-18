    <div class="box box-solid box-primary hover">
        <div class="box-header">
            <h5>Historia Clínica de <a href="{{url('admin/ver-paciente/'. $historia->paciente_id)}}"> <span>{{$historia->paciente->full_name}}</a></span></h5>

            <div class="box-tools pull-right">
              <b><i class="fa fa-clock-o"></i> </b> {{\App\Funciones::transdate($historia->fecha , 'l j \d\e F \d\e Y')}}
              <button class="btn btn-xs btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>            
      </div>
      <div class="box-body">

        <h2 class="">Anamnesis</h2>
        <hr>
        <div class="col-md-12">
            <b class="text-uppercase text-primary">Motivo de la Consulta:</b> <p class="text-justify"> {{$historia->motivo_de_consulta}} </p>
        </div>

        <div class="col-md-12">
            <b class="text-uppercase text-primary">Enfermedad Actual:</b> <p class="text-justify">{{$historia->enfermedad_actual}}</p>
        </div>

        <div class="col-md-12">
            <b class="text-uppercase text-primary">Revision por Sistema:</b> <p class="text-justify">{{$historia->revision_por_sistema}}</p>
        </div>

        <h2 class="">Antecedentes</h2>
        <hr>

        <div class="col-md-6">
            <b class="text-uppercase text-primary">Antecedentes Patologicos:</b> <p class="text-justify">{{$historia->patologicos}}</p>
        </div>       

        <div class="col-md-6">
            <b class="text-uppercase text-primary">Antecedentes Quirurjicos:</b> <p class="text-justify">{{$historia->quirurjicos}}</p>
        </div>    

        <div class="col-md-6">
            <b class="text-uppercase text-primary">Antecedentes Farmacologicos:</b> <p class="text-justify">{{$historia->farmacologicos}}</p>
        </div>

        <div class="col-md-6">
            <b class="text-uppercase text-primary">Antecedentes Farmacologicos:</b> <p class="text-justify">{{$historia->farmacologicos}}</p>
        </div>

        <div class="col-md-6">
            <b class="text-uppercase text-primary">Antecedentes Traumaticos:</b> <p class="text-justify">{{$historia->traumaticos}}</p>
        </div>


        <div class="col-md-6">
            <b class="text-uppercase text-primary">Antecedentes Inmunologicos:</b> <p class="text-justify">{{$historia->inmunologicos}}</p>
        </div>


        <div class="col-md-6">
            <b class="text-uppercase text-primary">Antecedentes Hospitalarios:</b> <p class="text-justify">{{$historia->hospitalarios}}</p>
        </div>


        <div class="col-md-6">
            <b class="text-uppercase text-primary">Antecedentes Toxico Alergicos:</b> <p class="text-justify">{{$historia->toxico_alergicos}}</p>
        </div>


        <h2 class="">Examen Físico</h2>
        <hr>

        <div class="col-md-2">
            <b class="text-uppercase text-primary">Frecuencia Cardiaca:</b> <p class="text-justify">{{$historia->frecuencia_cardiaca}}</p>
        </div>       

        <div class="col-md-2">
            <b class="text-uppercase text-primary">Frecuencia Respirtoria:</b> <p class="text-justify">{{$historia->frecuencia_respiratoria}}</p>
        </div>    

        <div class="col-md-2">
            <b class="text-uppercase text-primary">Tensión Arterial:</b> <p class="text-justify">{{$historia->tension_arterial}}</p>
        </div>

        <div class="col-md-2">
            <b class="text-uppercase text-primary">Temperatura:</b> <p class="text-justify">{{$historia->temperatura}}</p>
        </div>

        <div class="col-md-2">
            <b class="text-uppercase text-primary">Peso:</b> <p class="text-justify">{{$historia->peso}}</p>
        </div>


        <div class="col-md-2">
            <b class="text-uppercase text-primary">Talla:</b> <p class="text-justify">{{$historia->talla}}</p>
        </div>


        <div class="col-md-12 text-center">
            <b class="text-uppercase text-primary">Aspecto General:</b> <p class="text-justify">{{$historia->aspecto_general}}</p>
        </div>

           <br> <hr>
        <div class="col-md-4">
            <b class="text-uppercase text-primary">Cabeza/Cuello:</b> <p class="text-justify">{{$historia->cabeza_cuello}}</p>
        </div>
        <div class="col-md-4">
            <b class="text-uppercase text-primary">Orl</b>
            <p class="text-justify">{{$historia->orl}}</p>
        </div>
        <div class="col-md-4">
            <b class="text-uppercase text-primary">Cardio/Pulmonar</b>
            <p class="text-justify">{{$historia->cardio_pulmonar}}</p>
        </div>
        <div class="col-md-4">
            <b class="text-uppercase text-primary">Abdomen</b>
            <p class="text-justify">{{$historia->abdomen}}</p>
        </div>
        <div class="col-md-4">
            <b class="text-uppercase text-primary">Extremidades</b>
            <p class="text-justify">{{$historia->extremidades}}</p>
        </div>
        <div class="col-md-4">
            <b class="text-uppercase text-primary">Piel</b>
            <p class="text-justify">{{$historia->piel}}</p>
        </div>
       <div class="col-md-12">
            <b class="text-uppercase text-primary">Neurologico</b>
            <p class="text-justify">{{$historia->neurologico}}</p>
        </div>

        <div class="col-md-12">
            <b class="text-uppercase text-primary">Notas:</b> <p class="text-justify">{{$historia->notas}}</p>
        </div>
        
        <div class="col-md-12">
            <h2>Resultados</h2>
        </div>
        <hr>
        <div class="col-md-12">
            <b class="text-uppercase text-primary">Analisis:</b> <p class="text-justify">{{$historia->analisis}}</p>
        </div>

        <div class="col-md-12">
            <b class="text-uppercase text-primary">Diagnostico CIE10:</b> <p class="text-justify">@if($historia->cie10){{$historia->cie10->titulo}}@endif</p>
        </div>
    </div>




    <div class="box-footer ">
        <p class="pull-left">
            <b>Ingreso:</b> <span>{{$historia->ingreso}}</span> |
            <b>Egreso:</b> <span>{{$historia->egreso}}</span>
        </p>
        <p class="pull-right">
            <b>Médico:</b>  <span>{{$historia->medico->nombre}}</span>
        </p>
    </div>
</div>