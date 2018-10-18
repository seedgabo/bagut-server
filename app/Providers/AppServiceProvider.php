<?php

namespace App\Providers;

use App\Models\Auditorias;
use App\Models\Casos_medicos;
use App\Models\ComentariosTickets;
use App\Models\HistoriaClinica;
use App\Models\Paciente;
use App\Models\Tickets;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Tickets::created(function ($ticket) {
            Auditorias::create(['tipo' => 'Creación', 'user_id' =>$ticket->user_id, 'ticket_id' => $ticket->id ]);
        });
      
        Tickets::deleting(function ($ticket) {
            Auditorias::create(['tipo' => 'Eliminación', 'user_id' =>$ticket->user_id, 'ticket_id' => $ticket->id ]);
        });

        ComentariosTickets::created(function($comentario){
            Auditorias::create(['tipo' => 'Seguimiento', 'user_id' =>$comentario->user_id, 'ticket_id' => $comentario->ticket->id ]);
        });

        ComentariosTickets::deleting(function($comentario){
            Auditorias::create(['tipo' => 'Seguimiento Eliminado', 'user_id' =>$comentario->user_id, 'ticket_id' => $comentario->ticket->id ]);
        });


        Paciente::created(function ($paciente) {
            Auditorias::create(['tipo' => 'Creación', 'user_id' => Auth::user()->id, 'paciente_id' => $paciente->id ]);
        });

        Paciente::updated(function ($paciente) {
            Auditorias::create(['tipo' => 'Actualización', 'user_id' => Auth::user()->id, 'paciente_id' => $paciente->id ]);
        });

        Paciente::deleting(function ($paciente) {
            Auditorias::create(['tipo' => 'Eliminación', 'user_id' => Auth::user()->id, 'paciente_id' => $paciente->id ]);
        });


        HistoriaClinica::created(function ($historia) {
            Auditorias::create(['tipo' => 'Creación', 'user_id' => Auth::user()->id, 'historia_id' => $historia->id ]);
        });

        HistoriaClinica::updated(function ($historia) {
            Auditorias::create(['tipo' => 'Actualización', 'user_id' => Auth::user()->id, 'historia_id' => $historia->id ]);
        });

        HistoriaClinica::deleting(function ($historia) {
            Auditorias::create(['tipo' => 'Eliminación', 'user_id' => Auth::user()->id, 'historia_id' => $historia->id ]);
        });
    

    
        Casos_medicos::created(function ($caso_medico) {
            Auditorias::create(['tipo' => 'Creación', 'user_id' => Auth::user()->id, 'caso_medico_id' => $caso_medico->id ]);
        });

        Casos_medicos::updated(function ($caso_medico) {
            Auditorias::create(['tipo' => 'Actualización', 'user_id' => Auth::user()->id, 'caso_medico_id' => $caso_medico->id ]);
        });

        Casos_medicos::deleting(function ($caso_medico) {
            Auditorias::create(['tipo' => 'Eliminación', 'user_id' => Auth::user()->id, 'caso_medico_id' => $caso_medico->id ]);
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
