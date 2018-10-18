<?php

use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
  public function run()
  {
      DB::table("settings")->delete();
      DB::table('settings')->insert([
          'key'           => 'contact_email_resume',
          'name'          => 'Emails de Resumen',
          'description'   => 'Emails a enviar el resumen semanal de efectividad, separados por coma',
          'value'         => 'seedgabo@gmail.com',
          'field'         => '{"name":"value","label":"Value","type":"email"}',
          'active'        => 1,
      ]);

      DB::table('settings')->insert([
          'key'           => 'contact_email_evaluations_proveedores',
          'name'          => 'Emails de Evaluaciones Proveedores',
          'description'   => 'Emails a enviar las alertas de evaluaciones a proveedores, separados por coma',
          'value'         => 'seedgabo@gmail.com',
          'field'         => '{"name":"value","label":"Value","type":"email"}',
          'active'        => 1,

      ]);

      DB::table('settings')->insert([
          'key'           => 'contact_email_evaluations_conductores',
          'name'          => 'Emails de Evaluaciones Conductores',
          'description'   => 'Emails a enviar las alertas de evaluaciones a conductores, separados por coma',
          'value'         => 'seedgabo@gmail.com',
          'field'         => '{"name":"value","label":"Value","type":"email"}',
          'active'        => 1,

      ]);

      DB::table('settings')->insert([
          'key'           => 'contact_email_evaluations_vehiculos',
          'name'          => 'Emails de Evaluaciones Vehiculos',
          'description'   => 'Emails a enviar las alertas de evaluaciones a vehiculos, separados por coma',
          'value'         => 'seedgabo@gmail.com',
          'field'         => '{"name":"value","label":"Value","type":"email"}',
          'active'        => 1,

      ]);

      DB::table('settings')->insert([
          'key'           => 'days_alerts',
          'name'          => 'Dias antes de alertar',
          'description'   => 'Cuantos Dias antes se debe enviar alertas para las evaluaciones ',
          'value'         => 8,
          'field'         => '{"name":"value","label":"Value","type":"number"}',
          'active'        => 1,

      ]);

      DB::table('settings')->insert([
          'key'           => 'nombre_empresa',
          'name'          => 'Nombre de la Empresa',
          'description'   => 'Nombre que se desea mostrar de la aplicación',
          'value'         => 'Newton',
          'field'         => '{"name":"value","label":"Value","type":"text"}',
          'active'        => 1,

      ]);

      DB::table('settings')->insert([
          'key'           => 'regimen',
          'name'          => 'Regimen',
          'description'   => 'Regimen de la empresa',
          'value'         => 'Regimen Común',
          'field'         => '{"name":"value","label":"Value","type":"text"}',
          'active'        => 1,
      ]);

      DB::table('settings')->insert([
          'key'           => 'resolucion_dian',
          'name'          => 'Numero de Resolución de la DIAN',
          'description'   => 'Nombre que se desea mostrar de la aplicación',
          'value'         => '0000000',
          'field'         => '{"name":"value","label":"Value","type":"text"}',
          'active'        => 1,
      ]);

      DB::table('settings')->insert([
          'key'           => 'cabecera_factura',
          'name'          => 'Cabecera de La factura',
          'description'   => "",
          'value'         => 'Configure este parametro',
          'field'         => '{"name":"value","label":"Value","type":"textarea"}',
          'active'        => 1,
      ]);

      DB::table('settings')->insert([
          'key'           => 'pie_factura',
          'name'          => 'Pie de pagina  de La factura',
          'description'   => "",
          'value'         => 'Configure este parametro',
          'field'         => '{"name":"value","label":"Value","type":"textarea"}',
          'active'        => 1,
      ]);

      DB::table('settings')->insert([
          'key'           => 'allow_view_profile',
          'name'          => 'Permitir Ver Perfiles entre usuarios',
          'description'   => 'Permitir Ver Perfiles entre usuarios',
          'value'         =>  1,
          'field'         => '{"name":"value","label":"Value","type":"checkbox"}',
          'active'        => 1,

      ]);
  }
}
