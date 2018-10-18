<?php

use Illuminate\Database\Seeder;

class TicketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      App\Models\Tickets::create(['titulo' => 'Decisión de software', 'contenido' => '<h1>  contatar con desarrolladores para contratar el software </h1>', 'user_id' => 1 , 'guardian_id' => 2, 'estado' => 'en curso' , 'categoria_id' => 8 ]);
      App\Models\Tickets::create(['titulo' => 'Cronograma de Capacitación', 'contenido' => '<h1> Seguimiento al programa de capacitación </h1>', 'user_id' => 2 , 'guardian_id' => 1, 'estado' => 'abierto' , 'categoria_id' => 4 ]);
      App\Models\Tickets::create(['titulo' => 'Demanda Empresarial', 'contenido' => '<h1> Demanda de empleados a empresa </h1>', 'user_id' => 2 , 'guardian_id' => 2, 'estado' => 'vencido' , 'categoria_id' => 7 ]);
    }
}
