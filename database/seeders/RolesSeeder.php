<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run()
    {
        // Creación de los roles
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'admin'],
            ['id' => 2, 'name' => 'user'],
        ]);

        // Creación del usuario administrador
        DB::table('users')->insert([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin123'),
            'role_id' => 1
        ]);

        // Creación del usuario estandar
        DB::table('users')->insert([
            'name' => 'User',
            'email' => 'user@user.com',
            'password' => bcrypt('user123'),
            'role_id' => 2
        ]);
    }

}
