<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'password_hint' => 'admin',
            'role' => 'admin',
        ]);
        
        \App\Models\User::factory()->create([
            'name' => 'superadmin',
            'username' => 'superadmin',
            'password' => bcrypt('superadmin'),
            'password_hint' => 'superadmin',
            'role' => 'superadmin',
        ]);
    }
}