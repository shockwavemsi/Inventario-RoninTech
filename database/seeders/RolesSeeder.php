<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RolesSeeder extends Seeder
{
    public function run()
    {
        // Crear roles solo si no existen
        if (!DB::table('roles')->where('id', 1)->exists()) {
            DB::table('roles')->insert(['id' => 1, 'name' => 'admin']);
        }
        if (!DB::table('roles')->where('id', 2)->exists()) {
            DB::table('roles')->insert(['id' => 2, 'name' => 'user']);
        }

        // Crear usuario admin solo si no existe (por email)
        if (!DB::table('users')->where('email', 'admin@admin.com')->exists()) {
            DB::table('users')->insert([
                'name' => 'Administrador',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin123'),
                'role_id' => 1
            ]);
        }

        // Crear usuario user solo si no existe
        if (!DB::table('users')->where('email', 'user@user.com')->exists()) {
            DB::table('users')->insert([
                'name' => 'User',
                'email' => 'user@user.com',
                'password' => Hash::make('user123'),
                'role_id' => 2
            ]);
        }
    }
}