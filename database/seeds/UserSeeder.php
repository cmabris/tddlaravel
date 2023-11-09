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
        factory(User::class)->create([
            'name' => 'Pepe Pérez',
            'email' => 'pepe@mail.es',
            'password' => bcrypt('123456'),
            'profession_id' => Profession::whereTitle('Desarrollador Back-End')->value('id'),
            'is_admin' => true,
        ]);

        factory(User::class)->create([
            'profession_id' => Profession::whereTitle('Desarrollador Back-End')->value('id'),
        ]);

        factory(User::class, 48)->create();
    }
}
