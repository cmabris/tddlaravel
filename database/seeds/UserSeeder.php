<?php

use App\Profession;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Pepe Pérez',
            'email' => 'pepe@mail.es',
            'password' => bcrypt('123456'),
            'profession_id' => Profession::whereTitle('Desarrollador Back-End')->value('id'),
        ]);
    }
}
