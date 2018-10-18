<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class rolesPermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("roles")->insert(['name' => 'SuperAdmin']);  //1
        DB::table("roles")->insert(['name' => 'Administrar Clinicas']); // 2
        DB::table("roles")->insert(['name' => 'Administrar Casos']);  // 3
        DB::table("roles")->insert(['name' => 'Administrar Usuarios']); // 4
        DB::table("roles")->insert(['name' => 'Administrar Permisos']); // 5
        DB::table("roles")->insert(['name' => 'Administrar Documentos']); // 6
        DB::table("roles")->insert(['name' => 'Correos Masivos']); // 7
        DB::table("roles")->insert(['name' => 'Administrar Respaldos']); // 8
        DB::table("roles")->insert(['name' => 'Administrar Alertas']); // 9
        DB::table("roles")->insert(['name' => 'Administrar Evaluaciones']); // 10
        DB::table("roles")->insert(['name' => 'Administrar Vehiculos']); // 11
        DB::table("roles")->insert(['name' => 'Administrar Conductores']); // 12
        DB::table("roles")->insert(['name' => 'Administrar Proveedores']); // 13
        DB::table("roles")->insert(['name' => 'Administrar Puestos']); // 14
        DB::table("roles")->insert(['name' => 'Administrar Clientes']); // 15
        DB::table("roles")->insert(['name' => 'Administrar Procesos']); // 16
        DB::table("roles")->insert(['name' => 'Administrar Consultas']); // 17
        DB::table("roles")->insert(['name' => 'Administrar Facturas']); // 18
        DB::table("roles")->insert(['name' => 'Administrar Facturas Proveedores']); // 19


        DB::table("permissions")->insert(['name' => 'Agregar Casos']);
        DB::table("permissions")->insert(['name' => 'Editar Casos']);
        DB::table("permissions")->insert(['name' => 'Eliminar Casos']);

        DB::table("permissions")->insert(['name' => 'Agregar Usuarios']);
        DB::table("permissions")->insert(['name' => 'Editar Usuarios']);
        DB::table("permissions")->insert(['name' => 'Eliminar Usuarios']);

        DB::table("permissions")->insert(['name' => 'Agregar Documentos']);
        DB::table("permissions")->insert(['name' => 'Editar Documentos']);
        DB::table("permissions")->insert(['name' => 'Eliminar Documentos']);


        DB::table("permissions")->insert(['name' => 'Agregar Pacientes']);
        DB::table("permissions")->insert(['name' => 'Editar Pacientes']);
        DB::table("permissions")->insert(['name' => 'Eliminar Pacientes']);


        DB::table("permissions")->insert(['name' => 'Agregar Casos Medicos']);
        DB::table("permissions")->insert(['name' => 'Editar Casos Medicos']);
        DB::table("permissions")->insert(['name' => 'Eliminar Casos Medicos']);


        DB::table("permissions")->insert(['name' => 'Agregar Incapacidades']);
        DB::table("permissions")->insert(['name' => 'Editar Incapacidades']);
        DB::table("permissions")->insert(['name' => 'Eliminar Incapacidades']);

        DB::table("permissions")->insert(['name' => 'Agregar Recomendaciones']);
        DB::table("permissions")->insert(['name' => 'Editar Recomendaciones']);
        DB::table("permissions")->insert(['name' => 'Eliminar Recomendaciones']);


        DB::table("permissions")->insert(['name' => 'Agregar Historias Clinicas']);
        DB::table("permissions")->insert(['name' => 'Editar Historias Clinicas']);
        DB::table("permissions")->insert(['name' => 'Eliminar Historias Clinicas']);

        DB::table("permissions")->insert(['name' => 'Administar Tablas Medicos']);



        DB::table("permissions")->insert(['name' => 'Agregar Evaluaciones']);
        DB::table("permissions")->insert(['name' => 'Editar Evaluaciones']);
        DB::table("permissions")->insert(['name' => 'Eliminar Evaluaciones']);

        DB::table("permissions")->insert(['name' => 'Evaluar Proveedores']);
        DB::table("permissions")->insert(['name' => 'Evaluar Conductores']);
        DB::table("permissions")->insert(['name' => 'Evaluar Vehiculos']);



        DB::table("permissions")->insert(['name' => 'Agregar Vehiculos']);
        DB::table("permissions")->insert(['name' => 'Editar Vehiculos']);
        DB::table("permissions")->insert(['name' => 'Eliminar Vehiculos']);


        DB::table("permissions")->insert(['name' => 'Agregar Conductores']);
        DB::table("permissions")->insert(['name' => 'Editar Conductores']);
        DB::table("permissions")->insert(['name' => 'Eliminar Conductores']);

        DB::table("permissions")->insert(['name' => 'Agregar Proveedores']);
        DB::table("permissions")->insert(['name' => 'Editar Proveedores']);
        DB::table("permissions")->insert(['name' => 'Eliminar Proveedores']);


        DB::table("permissions")->insert(['name' => 'Agregar Puestos']);
        DB::table("permissions")->insert(['name' => 'Editar Puestos']);
        DB::table("permissions")->insert(['name' => 'Eliminar Puestos']);


        DB::table("permissions")->insert(['name' => 'Agregar Clientes']);
        DB::table("permissions")->insert(['name' => 'Editar Clientes']);
        DB::table("permissions")->insert(['name' => 'Eliminar Clientes']);


        DB::table("permissions")->insert(['name' => 'Agregar Facturas']);
        DB::table("permissions")->insert(['name' => 'Editar Facturas']);
        DB::table("permissions")->insert(['name' => 'Eliminar Facturas']);

        DB::table("permissions")->insert(['name' => 'Agregar Procesos']);
        DB::table("permissions")->insert(['name' => 'Editar Procesos']);
        DB::table("permissions")->insert(['name' => 'Eliminar Procesos']);


        DB::table("permissions")->insert(['name' => 'Agregar Consultas']);
        DB::table("permissions")->insert(['name' => 'Editar Consultas']);
        DB::table("permissions")->insert(['name' => 'Eliminar Consultas']);

        DB::table("permissions")->insert(['name' => 'Agregar Facturas Proveedores']);
        DB::table("permissions")->insert(['name' => 'Editar Facturas Proveedores']);
        DB::table("permissions")->insert(['name' => 'Eliminar Facturas Proveedores']);
        //Clinica
        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Agregar Casos Medicos');
        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Editar Casos Medicos');
        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Eliminar Casos Medicos');

        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Agregar Pacientes');
        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Editar Pacientes');
        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Eliminar Pacientes');

        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Agregar Incapacidades');
        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Editar Incapacidades');
        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Eliminar Incapacidades');

        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Agregar Recomendaciones');
        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Editar Recomendaciones');
        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Eliminar Recomendaciones');

        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Agregar Historias CLinicas');
        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Editar Historias CLinicas');
        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Eliminar Historias CLinicas');
        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Administar Tablas Medicos');



        // Tickets de Soporte
        Backpack\PermissionManager\app\Models\Role::find(3)->GivePermissionTo('Agregar Casos');
        Backpack\PermissionManager\app\Models\Role::find(3)->GivePermissionTo('Editar Casos');
        Backpack\PermissionManager\app\Models\Role::find(3)->GivePermissionTo('Eliminar Casos');



        //Usuarios
        Backpack\PermissionManager\app\Models\Role::find(4)->GivePermissionTo('Agregar Usuarios');
        Backpack\PermissionManager\app\Models\Role::find(4)->GivePermissionTo('Editar Usuarios');
        Backpack\PermissionManager\app\Models\Role::find(4)->GivePermissionTo('Eliminar Usuarios');



        //Documentos
        Backpack\PermissionManager\app\Models\Role::find(6)->GivePermissionTo('Agregar Documentos');
        Backpack\PermissionManager\app\Models\Role::find(6)->GivePermissionTo('Editar Documentos');
        Backpack\PermissionManager\app\Models\Role::find(6)->GivePermissionTo('Eliminar Documentos');


        //Evaluaciones
        Backpack\PermissionManager\app\Models\Role::find(10)->GivePermissionTo('Agregar Evaluaciones');
        Backpack\PermissionManager\app\Models\Role::find(10)->GivePermissionTo('Editar Evaluaciones');
        Backpack\PermissionManager\app\Models\Role::find(10)->GivePermissionTo('Eliminar Evaluaciones');


        //Vehiculos
        Backpack\PermissionManager\app\Models\Role::find(11)->GivePermissionTo('Agregar Vehiculos');
        Backpack\PermissionManager\app\Models\Role::find(11)->GivePermissionTo('Editar Vehiculos');
        Backpack\PermissionManager\app\Models\Role::find(11)->GivePermissionTo('Eliminar Vehiculos');

        //Conductores
        Backpack\PermissionManager\app\Models\Role::find(12)->GivePermissionTo('Agregar Conductores');
        Backpack\PermissionManager\app\Models\Role::find(12)->GivePermissionTo('Editar Conductores');
        Backpack\PermissionManager\app\Models\Role::find(12)->GivePermissionTo('Eliminar Conductores');

        //Proveedores
        Backpack\PermissionManager\app\Models\Role::find(13)->GivePermissionTo('Agregar Proveedores');
        Backpack\PermissionManager\app\Models\Role::find(13)->GivePermissionTo('Editar Proveedores');
        Backpack\PermissionManager\app\Models\Role::find(13)->GivePermissionTo('Eliminar Proveedores');


        //Puestos
        Backpack\PermissionManager\app\Models\Role::find(14)->GivePermissionTo('Agregar Puestos');
        Backpack\PermissionManager\app\Models\Role::find(14)->GivePermissionTo('Editar Puestos');
        Backpack\PermissionManager\app\Models\Role::find(14)->GivePermissionTo('Eliminar Puestos');


        //CLientes
        Backpack\PermissionManager\app\Models\Role::find(15)->GivePermissionTo('Agregar Clientes');
        Backpack\PermissionManager\app\Models\Role::find(15)->GivePermissionTo('Editar Clientes');
        Backpack\PermissionManager\app\Models\Role::find(15)->GivePermissionTo('Eliminar Clientes');


        Backpack\PermissionManager\app\Models\Role::find(15)->GivePermissionTo('Agregar Facturas');
        Backpack\PermissionManager\app\Models\Role::find(15)->GivePermissionTo('Editar Facturas');
        Backpack\PermissionManager\app\Models\Role::find(15)->GivePermissionTo('Eliminar Facturas');

        //Procesos
        Backpack\PermissionManager\app\Models\Role::find(16)->GivePermissionTo('Agregar Procesos');
        Backpack\PermissionManager\app\Models\Role::find(16)->GivePermissionTo('Editar Procesos');
        Backpack\PermissionManager\app\Models\Role::find(16)->GivePermissionTo('Eliminar Procesos');


        //Consultas
        Backpack\PermissionManager\app\Models\Role::find(17)->GivePermissionTo('Agregar Consultas');
        Backpack\PermissionManager\app\Models\Role::find(17)->GivePermissionTo('Editar Consultas');
        Backpack\PermissionManager\app\Models\Role::find(17)->GivePermissionTo('Eliminar Consultas');


        //Facturas
        Backpack\PermissionManager\app\Models\Role::find(18)->GivePermissionTo('Agregar Facturas');
        Backpack\PermissionManager\app\Models\Role::find(18)->GivePermissionTo('Editar Facturas');
        Backpack\PermissionManager\app\Models\Role::find(18)->GivePermissionTo('Eliminar Facturas');


        //Facturas Proveedores
        Backpack\PermissionManager\app\Models\Role::find(19)->GivePermissionTo('Agregar Facturas Proveedores');
        Backpack\PermissionManager\app\Models\Role::find(19)->GivePermissionTo('Editar Facturas Proveedores');
        Backpack\PermissionManager\app\Models\Role::find(19)->GivePermissionTo('Eliminar Facturas Proveedores');

        foreach (Backpack\PermissionManager\app\Models\Permission::all() as $permiso)
        {
           Backpack\PermissionManager\app\Models\Role::find(1)->GivePermissionTo($permiso);
        }

        DB::table("role_users")->insert(['user_id' => 1, 'role_id' => 1]);
        DB::table("role_users")->insert(['user_id' => 2, 'role_id' => 1]);
        DB::table("role_users")->insert(['user_id' => 3, 'role_id' => 1]);

        DB::table("permissions")->insert(['name' => 'Ocultar Gestion Documental']);


    }
}
