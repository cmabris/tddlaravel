<?php

namespace Tests\Feature\Admin;

use App\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListSkillsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_shows_the_skills_list()
    {
        factory(Skill::class)->create(['name' => 'HTML']);
        factory(Skill::class)->create(['name' => 'PHP']);
        factory(Skill::class)->create(['name' => 'CSS']);

        $this->get('habilidades')
            ->assertStatus(200)
            ->assertSeeInOrder([
                'CSS',
                'HTML',
                'PHP'
            ]);
    }
}
