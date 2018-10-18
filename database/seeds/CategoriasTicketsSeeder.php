<?php

use Illuminate\Database\Seeder;

class CategoriasTicketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\CategoriasTickets::create([
        	'nombre' => 'Soporte',
        	'descripción'  => 'Soporte',
        	]);
        \App\Models\CategoriasTickets::create([
        	'nombre' => 'Nomina',
        	'descripción'  => 'Nomina',
        	]);
        \App\Models\CategoriasTickets::create([
        	'nombre' => 'Ventas',
        	'descripción'  => 'Ventas',
        	]);
        \App\Models\CategoriasTickets::create([
        	'nombre' => 'Recursos Humanos',
        	'descripción'  => 'Recursos Humanos',
        	]);
          \App\Models\CategoriasTickets::create([
            'nombre' => 'Juridico',
            'descripción'  => 'Juridico',
            ]);
       	\App\Models\CategoriasTickets::create([
        	'nombre' => 'Planeación',
        	'descripción'  => 'Planeación',
        	]);
        \App\Models\CategoriasTickets::create([
            'nombre' => 'Clínica',
            'descripción'  => 'Deparamento de Salud',
            ]);
          \App\Models\CategoriasTickets::create([
                'nombre' => 'Gerencia',
                'descripción'  => '',
                ]);
          \App\Models\CategoriasTickets::create([
                'nombre' => 'Finanzas',
                'descripción'  => '',
                ]);
                \App\Models\CategoriasTickets::create([
                      'nombre' => 'Contabilidad',
                      'descripción'  => '',
                      'parent_id' => 9
                      ]);
          \App\Models\CategoriasTickets::create([
                'nombre' => 'Operaciones',
                'descripción'  => '',
                ]);

          \App\Models\CategoriasTickets::create([
                  'nombre' => 'Contabilidad',
                  'descripción'  => '',
                  ]);
        \App\Models\CategoriasTickets::create([
            'id' => 99,
            'nombre' => 'Casos Medicos',
            'descripción'  => 'Departamento de Salud - Casos Médicos Automaticos del Sistema',
            ]);
    }
}
