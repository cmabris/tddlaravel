<?php

use App\Profession;
use App\Skill;
use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $professions = Profession::all();
        $skills = Skill::all();

        $user = factory(User::class)->create([
            'name' => 'Pepe Pérez',
            'email' => 'pepe@mail.es',
            'password' => bcrypt('123456'),
            'role' => 'admin',
            'created_at' => now()->addDay(),
        ]);

        $user->profile()->create([
            'bio' => 'Programador',
            'profession_id' => $professions->where('title', 'Desarrollador Back-End')->first()->id,
        ]);

        factory(User::class, 999)->create()->each(function ($user) use ($professions, $skills) {
            $numSkills = $skills->count();
            $randomSkills = $skills->random(rand(0, $numSkills));

            $user->skills()->attach($randomSkills);

            $user->profile()->create(
                factory(\App\UserProfile::class)->raw([
                    'profession_id' => rand(0, 2) ? $professions->random()->id : null,
                ])
            );
        });
    }
}
