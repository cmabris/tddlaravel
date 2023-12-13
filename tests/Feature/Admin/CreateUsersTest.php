<?php

namespace Tests\Feature\Admin;

use App\Profession;
use App\Skill;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateUsersTest extends TestCase
{
    use RefreshDatabase;

    protected $defaultData = [
        'first_name' => 'Pepe',
        'last_name' => 'Pérez',
        'email' => 'pepe@mail.es',
        'password' => '123456',
        'bio' => "Programador de Laravel y VueJS",
        'twitter' => 'https://twitter.com/pepe',
        'profession_id' => '',
        'role' => 'user',
        'state' => 'active',
    ];

    /** @test */
    function it_loads_the_new_user_page()
    {
        $profession = factory(Profession::class)->create();
        $skillA = factory(Skill::class)->create();
        $skillB = factory(Skill::class)->create();

        $this->get('usuarios/nuevo')
            ->assertStatus(200)
            ->assertSee('Crear nuevo usuario')
            ->assertViewHas('professions', function ($professions) use ($profession) {
                return $professions->contains($profession);
            })
            ->assertViewHas('skills', function ($skills) use ($skillA, $skillB) {
                return $skills->contains($skillA && $skills->contains($skillB));
            });
    }

    /** @test */
    function it_creates_a_new_user()
    {
        $profession = factory(Profession::class)->create();

        $skillA = factory(Skill::class)->create();
        $skillB = factory(Skill::class)->create();
        $skillC = factory(Skill::class)->create();

        $this->post('usuarios', $this->withData([
            'skills' => [$skillA->id, $skillB->id],
            'profession_id' => $profession->id,
        ]))
            ->assertRedirect('usuarios');

        $this->assertCredentials([
            'first_name' => 'Pepe',
            'last_name' => 'Pérez',
            'email' => 'pepe@mail.es',
            'password' => '123456',
            'role' => 'user',
            'active' => true
        ]);

        $user = User::findByEmail('pepe@mail.es');

        $this->assertDatabaseHas('user_profiles', [
            'bio' => "Programador de Laravel y VueJS",
            'twitter' => 'https://twitter.com/pepe',
            'user_id' => User::findByEmail('pepe@mail.es')->id,
            'profession_id' => $profession->id,
        ]);

        $this->assertDatabaseHas('skill_user', [
            'user_id' => $user->id,
            'skill_id' => $skillA->id,
        ]);
        $this->assertDatabaseHas('skill_user', [
            'user_id' => $user->id,
            'skill_id' => $skillB->id,
        ]);
        $this->assertDatabaseMissing('skill_user', [
            'user_id' => $user->id,
            'skill_id' => $skillC->id,
        ]);
    }

    /** @test */
    function the_user_is_redirected_to_the_previous_page_when_the_validation_fails()
    {
        $this->handleValidationExceptions();

        $this->from('usuarios/nuevo')
            ->post('usuarios', [])
            ->assertRedirect('usuarios/nuevo');

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_first_name_is_required()
    {
        $this->handleValidationExceptions();

        $this->from('usuarios/nuevo')
            ->post('usuarios', $this->withData([
                'first_name' => ''
            ]))
            ->assertSessionHasErrors([
                'first_name' => 'El campo nombre es obligatorio'
            ]);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_last_name_is_required()
    {
        $this->handleValidationExceptions();

        $this->from('usuarios/nuevo')
            ->post('usuarios', $this->withData([
                'last_name' => ''
            ]))
            ->assertSessionHasErrors([
                'last_name' => 'El campo apellidos es obligatorio'
            ]);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_email_is_required()
    {
        $this->handleValidationExceptions();

        $this->from('usuarios/nuevo')
            ->post('usuarios', $this->withData([
                'email' => ''
            ]))
            ->assertSessionHasErrors([
                'email' => 'El campo email es obligatorio'
            ]);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_password_is_required()
    {
        $this->handleValidationExceptions();

        $this->from('usuarios/nuevo')
            ->post('usuarios', $this->withData([
                'password' => ''
            ]))
            ->assertSessionHasErrors([
                'password' => 'El campo contraseña es obligatorio'
            ]);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_email_must_be_valid()
    {
        $this->handleValidationExceptions();

        $this->from('usuarios/nuevo')
            ->post('usuarios', $this->withData([
                'email' => 'correo-no-valido',
            ]))
            ->assertSessionHasErrors('email');

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_email_must_be_unique()
    {
        $this->handleValidationExceptions();

        factory(User::class)->create([
            'email' => 'pepe@mail.es',
        ]);

        $this->from('usuarios/nuevo')
            ->post('usuarios', $this->withData([
                'email' => 'pepe@mail.es'
            ]))
            ->assertSessionHasErrors('email');

        $this->assertEquals(1, User::count());
    }

    /** @test */
    function the_twitter_field_is_optional()
    {
        $this->handleValidationExceptions();

        $this->post('usuarios', $this->withData([
            'twitter' => null
        ]))->assertRedirect('usuarios');

        $this->assertCredentials([
            'first_name' => 'Pepe',
            'email' => 'pepe@mail.es',
            'password' => '123456',
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'bio' => "Programador de Laravel y VueJS",
            'twitter' => null,
            'user_id' => User::findByEmail('pepe@mail.es')->id
        ]);
    }

    /** @test */
    function the_profession_id_field_is_optional()
    {
        $this->handleValidationExceptions();

        $this->post('usuarios', $this->withData([
            'profession_id' => null
        ]))->assertRedirect('usuarios');

        $this->assertCredentials([
            'first_name' => 'Pepe',
            'email' => 'pepe@mail.es',
            'password' => '123456',
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'bio' => "Programador de Laravel y VueJS",
            'user_id' => User::findByEmail('pepe@mail.es')->id,
            'profession_id' => null,
        ]);
    }

    /** @test */
    function the_profession_id_must_be_valid()
    {
        $this->handleValidationExceptions();

        $this->from('usuarios/nuevo')
            ->post('usuarios', $this->withData([
                'profession_id' => '999'
            ]))
            ->assertSessionHasErrors(['profession_id']);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function only_not_deleted_professions_can_be_selected()
    {
        $this->handleValidationExceptions();

        $deletedProfession = factory(Profession::class)->create([
            'deleted_at' => now()->format('Y-m-d'),
        ]);

        $this->from('usuarios/nuevo')
            ->post('usuarios', $this->withData([
                'profession_id' => $deletedProfession->id
            ]))
            ->assertSessionHasErrors(['profession_id']);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_skills_must_be_an_array()
    {
        $this->handleValidationExceptions();

        $this->from('usuarios/nuevo')
            ->post('usuarios', $this->withData([
                'skills' => 'PHP, JS'
            ]))
            ->assertSessionHasErrors(['skills']);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_skills_must_be_valid()
    {
        $this->handleValidationExceptions();

        $skillA = factory(Skill::class)->create();
        $skillB = factory(Skill::class)->create();

        $this->from('usuarios/nuevo')
            ->post('usuarios', $this->withData([
                'skills' => [$skillA->id, $skillB->id + 1]
            ]))
            ->assertSessionHasErrors(['skills']);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_role_field_is_optional()
    {
        $this->handleValidationExceptions();

        $this->from('usuarios/nuevo')
            ->post('usuarios', $this->withData([
                'role' => null
            ]))
            ->assertRedirect('usuarios');

        $this->assertDatabaseHas('users', [
            'email' => 'pepe@mail.es',
            'role' => 'user',
        ]);
    }

    /** @test */
    function the_role_field_must_be_valid()
    {
        $this->handleValidationExceptions();

        $this->from('usuarios/nuevo')
            ->post('usuarios', $this->withData([
                'role' => 'invalid-role'
            ]))
            ->assertSessionHasErrors(['role']);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_state_must_be_valid()
    {
        $this->handleValidationExceptions();

        $this->post('usuarios', $this->withData([
            'state' => 'invalid-state',
        ]))->assertSessionHasErrors('state');

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_state_is_required()
    {
        $this->handleValidationExceptions();

        $this->post('usuarios', $this->withData([
            'state' => null,
        ]))->assertSessionHasErrors('state');

        $this->assertDatabaseEmpty('users');
    }
}
