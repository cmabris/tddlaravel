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
        $user = factory(User::class)->create([
            'name' => 'Pepe Pérez',
            'email' => 'pepe@mail.es',
            'password' => bcrypt('123456'),
            'role' => 'admin',
        ]);

        $user->profile()->create([
            'bio' => 'Programador',
            'profession_id' => Profession::whereTitle('Desarrollador Back-End')->value('id'),
        ]);

        factory(User::class, 49)->create()->each(function ($user) {
            $user->profile()->create(
                factory(\App\UserProfile::class)->raw()
            );
        });
    }
}
