<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $this->call(LanguagesTableSeeder::class);
       $this->call(SettingTableSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(CategoriasTicketsSeeder::class);
        $this->call(rolesPermisosSeeder::class);
        $this->call(SettingTableSeeder::class);
        $this->call(TicketsSeeder::class);
      }
}
