<?php

use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Team::class)->create([
            'name' => 'IES Ingeniero',
        ]);

        factory(\App\Team::class)->times(99)->create()->unique();
    }
}
